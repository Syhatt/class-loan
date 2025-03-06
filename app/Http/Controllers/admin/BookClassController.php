<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BookingClass;
use Illuminate\Http\Request;

class BookClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Data Peminjaman Kelas';
        $booking = BookingClass::where('faculty_id', auth()->user()->faculty_id)->get();

        return view('admin.bookclass.index', compact('pageTitle', 'booking'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Detail Data Peminjaman Kelas';
        $booking = BookingClass::findOrFail($id);

        return view('admin.bookclass.show', compact('pageTitle', 'booking'));
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
        $booking = BookingClass::findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $booking->update([
            'status' => $request->status
        ]);

        return redirect()->route('bookclass.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
