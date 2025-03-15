<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Classmodel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Kelas';
        $classes = Classmodel::where('faculty_id', auth()->user()->faculty_id)->get();

        return view('admin.class.index', compact('pageTitle', 'classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Tambah Kelas';
        $facultyId = Auth::user()->faculty_id;

        return view('admin.class.create', compact('pageTitle', 'facultyId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'faculty_id' => 'required',
            'name' => 'required',
            'desc' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Simpan gambar ke storage
        $filename = time() . '.' . $request->image->extension();
        $path = $request->file('image')->storeAs('images', $filename, 'public');

        Classmodel::create([
            'faculty_id' => $request->faculty_id,
            'name' => $request->name,
            'desc' => $request->desc,
            'image' => $path,
        ]);

        return redirect()->route('class.index')->with(['success' => 'Data Berhasil Disimpan!']);
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
        $pageTitle = 'Edit Kelas';
        $classes = Classmodel::find($id);

        return view('admin.class.edit', compact('pageTitle', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'desc' => 'required',
            'image' => 'required',
        ]);

        $classes = Classmodel::findOrFail($id);
        $classes->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'image' => $request->image,
        ]);

        return redirect()->route('class.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Classmodel::findOrFail($id)->delete();

        return redirect()->route('class.index')->with(['success' => 'Data Berhasil Hapus!']);
    }
}
