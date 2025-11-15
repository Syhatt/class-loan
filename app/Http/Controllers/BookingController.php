<?php

namespace App\Http\Controllers;

use App\Models\BookingClass;
use App\Models\Classmodel;
use App\Models\Faculty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Peminjaman';

        if (auth()->user()->role === 'superadmin') {
            $class = Classmodel::where('is_available', true)
                ->with('faculty')
                ->get();

            $faculties = Faculty::all(); // Untuk filter fakultas
            return view('booking.index', compact('pageTitle', 'class', 'faculties'));
        }

        $class = Classmodel::where('faculty_id', auth()->user()->faculty_id)->where('is_available', true)->get();

        return view('booking.index', compact('pageTitle', 'class'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        $pageTitle = 'Form Peminjaman';
        $class = Classmodel::findOrFail($id);

        return view('booking.create', compact('pageTitle', 'class'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'classmodel_id' => 'required|exists:classmodels,id',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'organization' => 'required',
            'activity_name' => 'required',
            'full_name' => 'required',
            'nim' => 'required',
            'semester' => 'required',
            'prodi' => 'required',
            'telp' => 'required',
            'no_letter' => 'required',
            'date_letter' => 'required|date',
            'apply_letter' => 'required|file|max:2048',
            'activity_proposal' => 'required|file|max:2048',
        ]);

        $user = auth()->user();

        // parsing datetimes
        $newStart = Carbon::parse($request->start_datetime);
        $newEnd = Carbon::parse($request->end_datetime);

        // role priority: smaller = higher priority
        $rolePriority = [
            'superadmin' => 1,
            'admin_fakultas' => 2,
            'dosen' => 3,
            'user' => 4,
        ];
        $currentPriority = $rolePriority[$user->role] ?? 99;

        // lakukan semua pemeriksaan kritikal di DB transaction agar aman dari race
        $result = DB::transaction(function () use (
            $request,
            $user,
            $newStart,
            $newEnd,
            $rolePriority,
            $currentPriority
        ) {
            // lock class row agar tidak ada race antara create lain untuk class yang sama
            $class = Classmodel::where('id', $request->classmodel_id)->lockForUpdate()->first();
            if (!$class || !$class->is_available) {
                return [
                    'status' => false,
                    'message' => 'Kelas tidak tersedia atau tidak ditemukan.'
                ];
            }

            // ambil semua booking candidate yang rentang tanggalnya overlap (filter awal di DB)
            $candidates = BookingClass::where('classmodel_id', $request->classmodel_id)
                ->whereIn('status', ['pending', 'approved'])
                ->whereDate('start_date', '<=', $newEnd->toDateString())
                ->whereDate('end_date', '>=', $newStart->toDateString())
                ->lockForUpdate()
                ->get();

            // filter kandidat jadi benar-benar overlap pada level datetime
            $overlaps = $candidates->filter(function ($b) use ($newStart, $newEnd) {
                $existingStart = Carbon::parse($b->start_date . ' ' . $b->start_time);
                $existingEnd   = Carbon::parse($b->end_date . ' ' . $b->end_time);
                // overlap condition: existingStart <= newEnd && existingEnd >= newStart
                return $existingStart->lte($newEnd) && $existingEnd->gte($newStart);
            });

            // jika ada overlap, bandingkan prioritas
            if ($overlaps->isNotEmpty()) {
                // cari apakah ada booking existing yang punya prioritas lebih tinggi atau sama
                foreach ($overlaps as $ex) {
                    $exPriority = $rolePriority[$ex->user->role] ?? 99;

                    // kalau existing punya prioritas lebih tinggi atau sama => tolak
                    if ($exPriority <= $currentPriority) {
                        return [
                            'status' => false,
                            'message' => 'Booking gagal — waktu tersebut sudah dibooking oleh user dengan prioritas sama/lebih tinggi.'
                        ];
                    }
                }

                // sampai sini: semua overlap punya prioritas LEBIH RENDAH (angka lebih besar)
                // Hanya override booking yang masih 'pending' (tidak override 'approved' yang berprioritas lebih rendah,
                // kecuali Anda memang ingin override approved juga — saat ini kita hanya reject pending)
                $overrides = $overlaps->where('status', 'pending');
                foreach ($overrides as $ov) {
                    $ov->status = 'rejected';
                    $ov->save();
                    // optional: log atau buat alasan/notes di kolom lain agar admin tahu kenapa di-reject
                }
                // NOTE: jika ada approved bookings yang lebih rendah prioritas, kita tidak override mereka.
                // Jika ingin override approved juga, ubah kebijakan ini.
            }

            // setelah lock & pengecekan, simpan file (masih dalam transaction)
            $filePaths = [];
            if ($request->hasFile('apply_letter')) {
                $filePaths['apply_letter'] = $request->file('apply_letter')->store('booking_class', 'public');
            }
            if ($request->hasFile('activity_proposal')) {
                $filePaths['activity_proposal'] = $request->file('activity_proposal')->store('booking_class', 'public');
            }

            // buat record booking (status pending)
            $booking = BookingClass::create([
                'faculty_id' => $request->faculty_id ?? $class->faculty_id,
                'user_id' => $user->id,
                'classmodel_id' => $request->classmodel_id,
                'start_date' => $newStart->toDateString(),
                'end_date' => $newEnd->toDateString(),
                'start_time' => $newStart->format('H:i'),
                'end_time' => $newEnd->format('H:i'),
                'organization' => $request->organization,
                'activity_name' => $request->activity_name,
                'full_name' => $request->full_name,
                'nim' => $request->nim,
                'semester' => $request->semester,
                'prodi' => $request->prodi,
                'telp' => $request->telp,
                'no_letter' => $request->no_letter,
                'date_letter' => $request->date_letter,
                'apply_letter' => $filePaths['apply_letter'] ?? null,
                'activity_proposal' => $filePaths['activity_proposal'] ?? null,
                'status' => 'pending',
            ]);

            return [
                'status' => true,
                'message' => 'Peminjaman berhasil disimpan!',
                'booking' => $booking
            ];
        }, 5); // retry up to 5 times bila deadlock

        if (!$result['status']) {
            return redirect()->back()->withInput()->with(['error' => $result['message']]);
        }

        return redirect()->route('mybook')->with(['success' => $result['message']]);
    }
}
