<?php

namespace App\Http\Controllers;

use App\Models\BookingClass;
use App\Models\Classmodel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Peminjaman';
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
            'start_date' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'organization' => 'required',
            'activity_name' => 'required',
            'full_name' => 'required',
            'nim' => 'required',
            'semester' => 'required',
            'prodi' => 'required',
            'telp' => 'required',
            'no_letter' => 'required',
            'date_letter' => 'required',
            'signature' => 'required|file|max:2048',
            'apply_letter' => 'required|file|max:2048',
            'activity_proposal' => 'required|file|max:2048',
        ]);

        $filePaths = [];

        if ($request->hasFile('signature')) {
            $filePaths['signature'] = $request->file('signature')->store('booking_class', 'public');
        }

        if ($request->hasFile('apply_letter')) {
            $filePaths['apply_letter'] = $request->file('apply_letter')->store('booking_class', 'public');
        }

        if ($request->hasFile('activity_proposal')) {
            $filePaths['activity_proposal'] = $request->file('activity_proposal')->store('booking_class', 'public');
        }

        $user = auth()->user();

        // --- PRIORITAS ROLE ---
        $rolePriority = [
            'superadmin' => 1,
            'admin_ruangan' => 2,
            'admin_barang' => 2,
            'dosen' => 3,
            'user' => 4,
        ];

        $currentPriority = $rolePriority[$user->role] ?? 5;

        // --- CEK BOOKING YANG OVERLAP ---
        $overlaps = BookingClass::where('classmodel_id', $request->classmodel_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->whereDate('start_date', $request->start_date)
            ->get();

        // Kalau ada bentrok
        foreach ($overlaps as $booking) {
            $bookerPriority = $rolePriority[$booking->user->role] ?? 5;

            if ($bookerPriority <= $currentPriority) {
                // Ada booking dengan prioritas lebih tinggi atau sama → tolak
                return redirect()->back()->with(['error' => 'Booking gagal, ruangan sudah dipakai oleh user dengan prioritas lebih tinggi.']);
            }
        }

        // dd($request->all());
        BookingClass::create([
            'faculty_id' => $request->faculty_id,
            'user_id' => auth()->user()->id,
            'classmodel_id' => $request->classmodel_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'organization' => $request->organization,
            'activity_name' => $request->activity_name,
            'full_name' => $request->full_name,
            'nim' => $request->nim,
            'semester' => $request->semester,
            'prodi' => $request->prodi,
            'telp' => $request->telp,
            'no_letter' => $request->no_letter,
            'date_letter' => $request->date_letter,
            'signature' => $filePaths['signature'] ?? null,
            'apply_letter' => $filePaths['apply_letter'] ?? null,
            'activity_proposal' => $filePaths['activity_proposal'] ?? null,
        ]);

        return redirect()->route('mybook')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
