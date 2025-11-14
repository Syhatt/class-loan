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
            'classmodel_id' => 'required',
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
            'date_letter' => 'required',
            'apply_letter' => 'required|file|max:2048',
            'activity_proposal' => 'required|file|max:2048',
        ]);

        $start = Carbon::parse($request->start_datetime);
        $end = Carbon::parse($request->end_datetime);
        $date = $start->toDateString();

        // 🔒 Cek apakah ruangan sudah dibooking pada waktu yang bertabrakan
        $isConflict = BookingClass::where('classmodel_id', $request->classmodel_id)
            ->whereDate('start_date', $date)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    // Kasus 1: waktu baru di tengah waktu lama
                    $q->where('start_time', '<=', $start->format('H:i'))
                        ->where('end_time', '>', $start->format('H:i'));
                })
                    ->orWhere(function ($q) use ($start, $end) {
                        // Kasus 2: waktu lama di tengah waktu baru
                        $q->where('start_time', '<', $end->format('H:i'))
                            ->where('end_time', '>=', $end->format('H:i'));
                    })
                    ->orWhere(function ($q) use ($start, $end) {
                        // Kasus 3: waktu lama di-cover penuh oleh waktu baru
                        $q->where('start_time', '>=', $start->format('H:i'))
                            ->where('end_time', '<=', $end->format('H:i'));
                    });
            })
            ->exists();

        if ($isConflict) {
            return redirect()->back()
                ->withInput()
                ->with(['error' => 'Ruangan ini sudah dipinjam pada waktu tersebut. Silakan pilih waktu lain.']);
        }

        // 🔒 Cegah user yang sama pinjam ruangan sama di tanggal sama (walau waktu beda)
        $alreadyBookedByUser = BookingClass::where('classmodel_id', $request->classmodel_id)
            ->whereDate('start_date', $date)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($alreadyBookedByUser) {
            return redirect()->back()
                ->withInput()
                ->with(['error' => 'Anda sudah melakukan peminjaman ruangan ini pada tanggal tersebut.']);
        }

        // Upload file
        $filePaths = [];
        if ($request->hasFile('apply_letter')) {
            $filePaths['apply_letter'] = $request->file('apply_letter')->store('booking_class', 'public');
        }
        if ($request->hasFile('activity_proposal')) {
            $filePaths['activity_proposal'] = $request->file('activity_proposal')->store('booking_class', 'public');
        }

        // Simpan data
        BookingClass::create([
            'faculty_id' => $request->faculty_id,
            'user_id' => auth()->user()->id,
            'classmodel_id' => $request->classmodel_id,
            'start_date' => $date,
            'end_date' => $end->toDateString(),
            'start_time' => $start->format('H:i'),
            'end_time' => $end->format('H:i'),
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

        return redirect()->route('mybook')->with(['success' => 'Peminjaman berhasil disimpan!']);
    }
}
