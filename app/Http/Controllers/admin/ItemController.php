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
            $item = Item::with('faculty')->get(); // tampil semua + fakultas
        } else {
            $item = Item::where('faculty_id', auth()->user()->faculty_id)->get();
        }

        return view('admin.item.index', compact('pageTitle', 'item'));
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
        $request->validate([
            'name' => 'required',
            'desc' => 'required',
            'stock' => 'required',
        ]);

        $facultyId = auth()->user()->role === 'superadmin'
            ? $request->faculty_id
            : auth()->user()->faculty_id;

        Item::create([
            'faculty_id' => $facultyId,
            'name' => $request->name,
            'desc' => $request->desc,
            'stock' => $request->stock,
        ]);

        return redirect()->route('item.index')->with(['success' => 'Data Berhasil Disimpan!']);
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
        $item = Item::find($id);

        if (auth()->user()->role === 'superadmin') {
            $faculties = Faculty::all();
            return view('admin.item.edit', compact('pageTitle', 'item', 'faculties'));
        }

        return view('admin.item.edit', compact('pageTitle', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'desc' => 'required',
            'stock' => 'required',
        ]);

        $item = Item::findOrFail($id);

        $facultyId = auth()->user()->role === 'superadmin'
            ? $request->faculty_id
            : auth()->user()->faculty_id;

        $item->update([
            'faculty_id' => $facultyId,
            'name' => $request->name,
            'desc' => $request->desc,
            'stock' => $request->stock,
        ]);

        return redirect()->route('item.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Item::findOrFail($id)->delete();

        return redirect()->route('item.index')->with(['success' => 'Data Berhasil Diubah!']);
    }
}
