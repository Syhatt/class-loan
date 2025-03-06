<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BookingItem;
use Illuminate\Http\Request;

class BookItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Data Peminjaman Kelas';
        $booking = BookingItem::where('faculty_id', auth()->user()->faculty_id)->get();

        return view('admin.bookitem.index', compact('pageTitle', 'booking'));
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
        $booking = BookingItem::findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $booking->update([
            'status' => $request->status
        ]);

        return redirect()->route('bookitem.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
