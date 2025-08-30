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
        $pageTitle = 'Detail Peminjaman Barang';

        $booking = BookingItem::with(['item', 'user', 'bookingClass'])
            ->findOrFail($id);

        return view('admin.bookitem.show', compact('pageTitle', 'booking'));
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
            'nodin_barang' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        $data = ['status' => $request->status];

        if ($request->hasFile('nodin_barang')) {
            $filename = time() . '_' . $request->file('nodin_barang')->getClientOriginalName();
            $request->file('nodin_barang')->move(public_path('uploads/nodin_barang'), $filename);
            $data['nodin_barang'] = $filename;
        }

        $booking->update($data);

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
