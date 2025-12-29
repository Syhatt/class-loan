<?php

namespace App\Http\Controllers;

use App\Models\BookingClass;
use App\Models\Classmodel;
use App\Models\Faculty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

            $faculties = Faculty::all(); // filter fakultas
            return view('booking.index', compact('pageTitle', 'class', 'faculties'));
        }

        $class = Classmodel::where('faculty_id', auth()->user()->faculty_id)
            ->where('is_available', true)
            ->get();

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
        // 1) VALIDASI AWAL
        $request->validate([
            'classmodel_id'        => 'required|exists:classmodels,id',
            'start_datetime'       => 'required|date',
            'end_datetime'         => 'required|date',
            'organization'         => 'required',
            'activity_name'        => 'required',
            'full_name'            => 'required',
            'nim'                  => 'required',
            'semester'             => 'required',
            'prodi'                => 'required',
            'telp'                 => 'required',
            'no_letter'            => 'required',
            'date_letter'          => 'required|date',
            'apply_letter'         => 'required|file|max:2048',
            'activity_proposal'    => 'required|file|max:2048',
        ], [
            'start_datetime.required' => 'Tanggal dan waktu mulai wajib diisi.',
            'end_datetime.required'   => 'Tanggal dan waktu selesai wajib diisi.',
            'start_datetime.date'     => 'Format tanggal/waktu mulai tidak valid.',
            'end_datetime.date'       => 'Format tanggal/waktu selesai tidak valid.',
        ]);

        $user     = auth()->user();
        $newStart = Carbon::parse($request->start_datetime);
        $newEnd   = Carbon::parse($request->end_datetime);

        // 2) VALIDASI: SELESAI HARUS SETELAH MULAI
        if ($newEnd->lte($newStart)) {
            $msg = 'Waktu selesai harus setelah waktu mulai.';
            return back()
                ->withInput()
                ->with('error', $msg)
                ->withErrors(['end_datetime' => $msg]);
        }

        // 3) VALIDASI: TIDAK BOLEH MASA LALU
        if ($newStart->lt(Carbon::now())) {
            $msg = 'Tanggal dan waktu mulai tidak boleh di masa lalu.';
            return back()
                ->withInput()
                ->with('error', $msg)
                ->withErrors(['start_datetime' => $msg]);
        }

        // (opsional) end juga tidak boleh masa lalu
        if ($newEnd->lt(Carbon::now())) {
            $msg = 'Tanggal dan waktu selesai tidak boleh di masa lalu.';
            return back()
                ->withInput()
                ->with('error', $msg)
                ->withErrors(['end_datetime' => $msg]);
        }

        // 4) PRIORITAS ROLE (hanya untuk override PENDING)
        $rolePriority = [
            'superadmin'     => 1,
            'admin_fakultas' => 2,
            'dosen'          => 3,
            'user'           => 4,
        ];
        $currentPriority = $rolePriority[$user->role] ?? 99;

        $result = DB::transaction(function () use ($request, $user, $newStart, $newEnd, $rolePriority, $currentPriority) {

            // lock class row
            $class = Classmodel::where('id', $request->classmodel_id)
                ->lockForUpdate()
                ->first();

            if (!$class || !$class->is_available) {
                return ['status' => false, 'message' => 'Kelas tidak tersedia atau tidak ditemukan.'];
            }

            // Kandidat booking overlap tanggal (lock) + eager load user
            $candidates = BookingClass::where('classmodel_id', $request->classmodel_id)
                ->whereIn('status', ['pending', 'approved'])
                ->whereDate('start_date', '<=', $newEnd->toDateString())
                ->whereDate('end_date', '>=', $newStart->toDateString())
                ->with('user')
                ->lockForUpdate()
                ->get();

            // Filter overlap full datetime
            $overlaps = $candidates->filter(function ($b) use ($newStart, $newEnd) {
                $existingStart = Carbon::parse($b->start_date . ' ' . $b->start_time);
                $existingEnd   = Carbon::parse($b->end_date . ' ' . $b->end_time);

                // overlap jika: startA <= endB && endA >= startB
                return $existingStart->lte($newEnd) && $existingEnd->gte($newStart);
            });

            // Jika ada overlap dengan APPROVED => SELALU TOLAK (tanpa prioritas)
            if ($overlaps->contains('status', 'approved')) {
                return [
                    'status' => false,
                    'message' => 'Waktu tersebut masih ada pengguna yang meminjam dan sudah disetujui.'
                ];
            }

            // Kalau overlap cuma PENDING => boleh pakai prioritas untuk override
            $pending = $overlaps->where('status', 'pending');
            foreach ($pending as $p) {
                $pRole = $p->user->role ?? null;
                $pPriority = $rolePriority[$pRole] ?? 99;

                if ($currentPriority < $pPriority) {
                    // current lebih tinggi => reject pending lama
                    $p->status = 'rejected';
                    $p->save();
                } else {
                    // pending lama prioritas sama/lebih tinggi => tolak
                    return [
                        'status' => false,
                        'message' => 'Waktu tersebut telah dipesan oleh pemohon lain dengan prioritas sama/lebih tinggi.'
                    ];
                }
            }

            // Upload file
            $filePaths = [];
            if ($request->hasFile('apply_letter')) {
                $filePaths['apply_letter'] = $request->file('apply_letter')->store('booking_class', 'public');
            }
            if ($request->hasFile('activity_proposal')) {
                $filePaths['activity_proposal'] = $request->file('activity_proposal')->store('booking_class', 'public');
            }

            // Create booking baru (pending)
            $booking = BookingClass::create([
                'faculty_id'         => $request->faculty_id ?? $class->faculty_id,
                'user_id'            => $user->id,
                'classmodel_id'      => $request->classmodel_id,

                'start_date'         => $newStart->toDateString(),
                'end_date'           => $newEnd->toDateString(),
                'start_time'         => $newStart->format('H:i'),
                'end_time'           => $newEnd->format('H:i'),

                'start_datetime'     => $newStart,
                'end_datetime'       => $newEnd,

                'organization'       => $request->organization,
                'activity_name'      => $request->activity_name,
                'full_name'          => $request->full_name,
                'nim'                => $request->nim,
                'semester'           => $request->semester,
                'prodi'              => $request->prodi,
                'telp'               => $request->telp,
                'no_letter'          => $request->no_letter,
                'date_letter'        => $request->date_letter,

                'apply_letter'       => $filePaths['apply_letter'] ?? null,
                'activity_proposal'  => $filePaths['activity_proposal'] ?? null,

                'status'             => 'pending',
            ]);

            return ['status' => true, 'message' => 'Peminjaman berhasil disimpan!', 'booking' => $booking];
        }, 5);

        if (!$result['status']) {
            return back()
                ->withInput()
                ->with('error', $result['message']);
        }

        return redirect()->route('mybook')->with('success', $result['message']);
    }
}
