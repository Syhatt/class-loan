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
        $newStart = Carbon::parse($request->start_datetime);
        $newEnd   = Carbon::parse($request->end_datetime);

        $rolePriority = [
            'superadmin' => 1,
            'admin_fakultas' => 2,
            'dosen' => 3,
            'user' => 4,
        ];
        $currentPriority = $rolePriority[$user->role] ?? 99;

        $result = DB::transaction(function () use ($request, $user, $newStart, $newEnd, $rolePriority, $currentPriority) {
            // lock class row
            $class = Classmodel::where('id', $request->classmodel_id)->lockForUpdate()->first();
            if (!$class || !$class->is_available) {
                return ['status' => false, 'message' => 'Kelas tidak tersedia atau tidak ditemukan.'];
            }

            // ambil kandidat booking yg rentang tanggal overlap (jarak tanggal) lalu lock
            $candidates = BookingClass::where('classmodel_id', $request->classmodel_id)
                ->whereIn('status', ['pending', 'approved'])
                ->whereDate('start_date', '<=', $newEnd->toDateString())
                ->whereDate('end_date', '>=', $newStart->toDateString())
                ->lockForUpdate()
                ->get();

            // filter true datetime overlap (start/end times)
            $overlaps = $candidates->filter(function ($b) use ($newStart, $newEnd) {
                $existingStart = Carbon::parse($b->start_date . ' ' . $b->start_time);
                $existingEnd   = Carbon::parse($b->end_date . ' ' . $b->end_time);
                return $existingStart->lte($newEnd) && $existingEnd->gte($newStart);
            });

            if ($overlaps->isNotEmpty()) {
                // jika ada existing APPROVED dengan prioritas <= current => reject (tidak bisa override approved)
                foreach ($overlaps as $ex) {
                    if ($ex->status === 'approved') {
                        $exPriority = $rolePriority[$ex->user->role] ?? 99;
                        if ($exPriority <= $currentPriority) {
                            return ['status' => false, 'message' => 'Waktu tersebut sudah dikunci oleh peminjaman yang disetujui.'];
                        }
                    }
                }

                // untuk existing PENDING: jika current user memiliki prioritas lebih tinggi daripada existing => override pending (reject pending)
                $pending = $overlaps->where('status', 'pending');
                foreach ($pending as $p) {
                    $pPriority = $rolePriority[$p->user->role] ?? 99;
                    if ($currentPriority < $pPriority) {
                        // current lebih prioritas -> override pending
                        $p->status = 'rejected';
                        $p->save();
                    } else {
                        // existing pending punya prioritas sama atau lebih tinggi -> tolak
                        return ['status' => false, 'message' => 'Waktu tersebut telah dipesan oleh pemohon lain dengan prioritas sama/lebih tinggi.'];
                    }
                }
            }

            // upload file (masih di transaction, file storage normal)
            $filePaths = [];
            if ($request->hasFile('apply_letter')) {
                $filePaths['apply_letter'] = $request->file('apply_letter')->store('booking_class', 'public');
            }
            if ($request->hasFile('activity_proposal')) {
                $filePaths['activity_proposal'] = $request->file('activity_proposal')->store('booking_class', 'public');
            }

            // buat booking baru (pending)
            $booking = BookingClass::create([
                'faculty_id' => $request->faculty_id ?? $class->faculty_id,
                'user_id' => $user->id,
                'classmodel_id' => $request->classmodel_id,
                'start_date' => $newStart->toDateString(),
                'end_date' => $newEnd->toDateString(),
                'start_time' => $newStart->format('H:i'),
                'end_time' => $newEnd->format('H:i'),
                'start_datetime' => $newStart,
                'end_datetime' => $newEnd,
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

            return ['status' => true, 'message' => 'Peminjaman berhasil disimpan!', 'booking' => $booking];
        }, 5);

        if (!$result['status']) {
            return redirect()->back()->withInput()->with(['error' => $result['message']]);
        }

        return redirect()->route('mybook')->with(['success' => $result['message']]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'classmodel_id' => 'required|exists:classmodels,id',
    //         'start_datetime' => 'required|date',
    //         'end_datetime' => 'required|date|after:start_datetime',
    //         'organization' => 'required',
    //         'activity_name' => 'required',
    //         'full_name' => 'required',
    //         'nim' => 'required',
    //         'semester' => 'required',
    //         'prodi' => 'required',
    //         'telp' => 'required',
    //         'no_letter' => 'required',
    //         'date_letter' => 'required|date',
    //         'apply_letter' => 'required|file|max:2048',
    //         'activity_proposal' => 'required|file|max:2048',
    //     ]);

    //     $user = auth()->user();

    //     $newStart = Carbon::parse($request->start_datetime);
    //     $newEnd   = Carbon::parse($request->end_datetime);

    //     // PRIORITAS HANYA UNTUK RACE CONDITION
    //     $rolePriority = [
    //         'superadmin'     => 1,
    //         'admin_fakultas' => 2,
    //         'dosen'          => 3,
    //         'user'           => 4,
    //     ];
    //     $currentPriority = $rolePriority[$user->role] ?? 99;

    //     $result = DB::transaction(function () use (
    //         $request,
    //         $user,
    //         $newStart,
    //         $newEnd,
    //         $rolePriority,
    //         $currentPriority
    //     ) {

    //         // LOCK DATA KELAS
    //         $class = Classmodel::where('id', $request->classmodel_id)->lockForUpdate()->first();
    //         if (!$class || !$class->is_available) {
    //             return [
    //                 'status' => false,
    //                 'message' => 'Kelas tidak tersedia atau tidak ditemukan.'
    //             ];
    //         }

    //         // Ambil booking yang overlap pada level tanggal
    //         $candidates = BookingClass::where('classmodel_id', $request->classmodel_id)
    //             ->whereIn('status', ['pending', 'approved'])
    //             ->whereDate('start_date', '<=', $newEnd->toDateString())
    //             ->whereDate('end_date', '>=', $newStart->toDateString())
    //             ->lockForUpdate()
    //             ->get();

    //         // Filter overlap pada level datetime
    //         $overlaps = $candidates->filter(function ($b) use ($newStart, $newEnd) {
    //             $existingStart = Carbon::parse($b->start_date . ' ' . $b->start_time);
    //             $existingEnd   = Carbon::parse($b->end_date . ' ' . $b->end_time);

    //             return $existingStart->lte($newEnd) && $existingEnd->gte($newStart);
    //         });

    //         /* 
    //     ===================================================
    //     1️⃣ FIRST RULE — EXISTING BOOKING SELALU MENANG
    //     ===================================================
    //     Jika sudah ada booking lebih dulu (pending/approved),
    //     siapapun role-nya (bahkan superadmin) tetap GAGAL.
    //     ===================================================
    //     */
    //         if ($overlaps->isNotEmpty()) {

    //             // CEK APAKAH SITUASI INI RACE CONDITION
    //             // race jika booking existing masih BELUM committed
    //             // tetapi karena lockForUpdate, jika kita bisa melihat row,
    //             // itu berarti row sudah commit → dia dibuat lebih DULU
    //             // maka role TIDAK BOLEH override
    //             return [
    //                 'status' => false,
    //                 'message' => 'Ruangan ini sudah dibooking lebih dulu untuk waktu tersebut.'
    //             ];
    //         }

    //         /*
    //     ===================================================
    //     2️⃣ RACE CONDITION MODE
    //     ===================================================
    //     Jika overlaps kosong karena record sedang dibuat
    //     secara bersamaan di transaksi paralel,
    //     maka perbandingan prioritas akan digunakan.
    //     ===================================================
    //     */

    //         // Deteksi race condition: 
    //         // Jika overlaps kosong tapi sebenarnya ada transaksi lain sedang create,
    //         // maka record masih belum terlihat (uncommitted).
    //         // Laravel tidak bisa "melihat" row uncommitted transaksi lain.
    //         // Jadi PRIORITAS otomatis berlaku karena kondisi overlaps == kosong.

    //         // Tidak perlu kode tambahan — ini otomatis oleh database.

    //         // Upload file
    //         $filePaths = [];
    //         if ($request->hasFile('apply_letter')) {
    //             $filePaths['apply_letter'] = $request->file('apply_letter')->store('booking_class', 'public');
    //         }
    //         if ($request->hasFile('activity_proposal')) {
    //             $filePaths['activity_proposal'] = $request->file('activity_proposal')->store('booking_class', 'public');
    //         }

    //         // Save booking baru
    //         $booking = BookingClass::create([
    //             'faculty_id' => $request->faculty_id ?? $class->faculty_id,
    //             'user_id' => $user->id,
    //             'classmodel_id' => $request->classmodel_id,
    //             'start_date' => $newStart->toDateString(),
    //             'end_date'   => $newEnd->toDateString(),
    //             'start_time' => $newStart->format('H:i'),
    //             'end_time'   => $newEnd->format('H:i'),
    //             'organization' => $request->organization,
    //             'activity_name' => $request->activity_name,
    //             'full_name' => $request->full_name,
    //             'nim' => $request->nim,
    //             'semester' => $request->semester,
    //             'prodi' => $request->prodi,
    //             'telp' => $request->telp,
    //             'no_letter' => $request->no_letter,
    //             'date_letter' => $request->date_letter,
    //             'apply_letter' => $filePaths['apply_letter'] ?? null,
    //             'activity_proposal' => $filePaths['activity_proposal'] ?? null,
    //             'status' => 'pending',
    //         ]);

    //         return [
    //             'status' => true,
    //             'message' => 'Peminjaman berhasil disimpan!',
    //             'booking' => $booking
    //         ];
    //     }, 5);

    //     if (!$result['status']) {
    //         return redirect()->back()->withInput()->with(['error' => $result['message']]);
    //     }

    //     return redirect()->route('mybook')->with(['success' => $result['message']]);
    // }
}
