<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Barang';

        if (auth()->user()->role === 'superadmin') {
            $items = Item::with('faculty')
                ->when(request('faculty_id'), function ($q) {
                    $q->where('faculty_id', request('faculty_id'));
                })
                ->get();

            $faculties = Faculty::all();

            return view('admin.item.index', compact('pageTitle', 'items', 'faculties'));
        }

        $items = Item::where('faculty_id', auth()->user()->faculty_id)->get();

        return view('admin.item.index', compact('pageTitle', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Tambah Barang';

        if (auth()->user()->role === 'superadmin') {
            $faculties = Faculty::all();
            return view('admin.item.create', compact('pageTitle', 'faculties'));
        }

        return view('admin.item.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // tentukan faculty_id yang dipakai
        $facultyId = auth()->user()->role === 'superadmin'
            ? $request->faculty_id
            : auth()->user()->faculty_id;

        $request->validate([
            'name' => [
                'required',
                function ($attribute, $value, $fail) use ($facultyId) {
                    if (!$facultyId) {
                        return;
                    }

                    $exists = Item::where('name', $value)
                        ->where('faculty_id', $facultyId)
                        ->exists();

                    if ($exists) {
                        $fail("Barang dengan nama '$value' sudah ada di fakultas tersebut.");
                    }
                },
            ],
            'desc' => 'required',
            'stock' => 'required|integer|min:0',
            'faculty_id' => auth()->user()->role === 'superadmin'
                ? 'required|exists:faculties,id'
                : 'nullable',
        ], [
            'name.required'       => 'Nama barang wajib diisi.',
            'desc.required'       => 'Deskripsi barang wajib diisi.',
            'stock.required'      => 'Stok barang wajib diisi.',
            'stock.integer'       => 'Stok barang harus berupa angka.',
            'stock.min'           => 'Stok barang minimal 0.',
            'faculty_id.required' => 'Fakultas wajib dipilih.',
            'faculty_id.exists'   => 'Fakultas tidak valid.',
        ]);

        Item::create([
            'faculty_id' => $facultyId,
            'name'       => $request->name,
            'desc'       => $request->desc,
            'stock'      => $request->stock,
        ]);

        return redirect()->route('item.index')->with('success', 'Data barang berhasil disimpan!');
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
        $pageTitle = 'Edit Barang';
        $item = Item::findOrFail($id);

        if (auth()->user()->role === 'superadmin') {
            $faculties = Faculty::all();
            return view('admin.item.edit', compact('pageTitle', 'item', 'faculties'));
        }

        return view('admin.item.edit', compact('pageTitle', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        // tentukan faculty_id baru
        $facultyId = auth()->user()->role === 'superadmin'
            ? $request->faculty_id
            : auth()->user()->faculty_id;

        $request->validate([
            'name' => [
                'required',
                function ($attribute, $value, $fail) use ($facultyId, $id) {
                    if (!$facultyId) {
                        return;
                    }

                    $exists = Item::where('name', $value)
                        ->where('faculty_id', $facultyId)
                        ->where('id', '!=', $id) // abaikan dirinya sendiri
                        ->exists();

                    if ($exists) {
                        $fail("Barang dengan nama '$value' sudah ada di fakultas tersebut.");
                    }
                },
            ],
            'desc' => 'required',
            'stock' => 'required|integer|min:0',
            'faculty_id' => auth()->user()->role === 'superadmin'
                ? 'required|exists:faculties,id'
                : 'nullable',
        ], [
            'name.required'       => 'Nama barang wajib diisi.',
            'desc.required'       => 'Deskripsi barang wajib diisi.',
            'stock.required'      => 'Stok barang wajib diisi.',
            'stock.integer'       => 'Stok barang harus berupa angka.',
            'stock.min'           => 'Stok barang minimal 0.',
            'faculty_id.required' => 'Fakultas wajib dipilih.',
            'faculty_id.exists'   => 'Fakultas tidak valid.',
        ]);

        $item->update([
            'faculty_id' => $facultyId,
            'name'       => $request->name,
            'desc'       => $request->desc,
            'stock'      => $request->stock,
        ]);

        return redirect()->route('item.index')->with('success', 'Data barang berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Item::findOrFail($id)->delete();

        return redirect()->route('item.index')->with(['success' => 'Data barang berhasil dihapus!']);
    }
}
