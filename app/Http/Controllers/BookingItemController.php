<?php

namespace App\Http\Controllers;

use App\Models\BookingClass;
use App\Models\BookingItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\alert;

class BookingItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Peminjaman Barang';
        $item = Item::where('faculty_id', auth()->user()->faculty_id)->where('stock', '>', 0)->get();

        return view('bookingitem.index', compact('pageTitle', 'item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $pageTitle = 'Form Peminjaman Barang';
        $items = Item::findOrFail($id);

        return view('bookingitem.create', compact('pageTitle', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'qty' => 'required|integer|min:1'
        ]);

        $user = auth()->user();

        // Cek apakah user sudah memiliki peminjaman ruangan yang disetujui
        $approvedRoomLoan = BookingClass::where('user_id', $user->id)
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$approvedRoomLoan) {
            // return back()->with('error', 'Anda harus memiliki peminjaman ruangan yang disetujui untuk meminjam barang.');
            return alert('gaisok cak');
        }

        $item = Item::findOrFail($request->item_id);
        if ($item->stock < $request->qty) {
            return redirect()->back()->with('error', 'Stok barang tidak mencukupi.');
        }

        // Kurangi stok barang
        $item->stock -= $request->qty;
        $item->save();

        BookingItem::create([
            'faculty_id' => $request->faculty_id,
            'user_id' => $user->id,
            'booking_classes_id' => $approvedRoomLoan->id, // Hubungkan dengan peminjaman ruangan
            'item_id' => $request->item_id,
            'qty' => $request->qty,
            'status' => 'pending',
        ]);

        // $item = Item::findOrFail($request->item_id);

        // if ($request->quantity > $item->stock) {
        //     // return back()->with('error', 'Stok barang tidak mencukupi.');
        //     return alert('Stok barang tidak mencukupi.');
        // }

        // BookingItem::create([
        //     'user_id' => $user->id,
        //     'classmodel_id' => BookingClass::where('user_id', $user->id)->where('status', 'approved')->latest()->first()->classmodel_id,
        //     'item_id' => $request->item_id,
        //     'qty' => $request->qty,
        //     'status' => 'pending'
        // ]);

        return redirect()->route('bookingitem.index')->with(['success' => 'Data Berhasil Disimpan!']);
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
