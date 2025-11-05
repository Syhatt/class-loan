<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Classmodel;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Kelas';

        if (auth()->user()->role == 'superadmin') {
            $classes = Classmodel::with('faculty')->get();
        } else {
            $classes = Classmodel::where('faculty_id', auth()->user()->faculty_id)->get();
        }

        return view('admin.class.index', compact('pageTitle', 'classes'));
    }

    public function toggleStatus($id)
    {
        $class = Classmodel::findOrFail($id);
        $class->is_available = !$class->is_available; // toggle true/false
        $class->save();

        $status = $class->is_available ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('class.index')->with('success', "Kelas {$class->name} berhasil {$status}!");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Tambah Kelas';

        if (auth()->user()->role == 'superadmin') {
            $faculties = Faculty::all();
            return view('admin.class.create_superadmin', compact('pageTitle', 'faculties'));
        }

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
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePaths = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('images', $filename, 'public');
                $imagePaths[] = $path;
            }
        }

        Classmodel::create([
            'faculty_id' => $request->faculty_id,
            'name' => $request->name,
            'desc' => $request->desc,
            'image' => implode(',', $imagePaths),
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
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $class = Classmodel::findOrFail($id);

        // ambil semua gambar lama
        $imagePaths = explode(',', $class->image);

        // kalau ada file baru, tambahkan
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('images', $filename, 'public');
                $imagePaths[] = $path;
            }
        }

        $class->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'image' => implode(',', $imagePaths),
        ]);

        return redirect()->route('class.edit', $id)->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Hapus salah satu gambar dari kelas
     */
    public function deleteImage($id, $index)
    {
        $class = Classmodel::findOrFail($id);
        $images = explode(',', $class->image);

        if (isset($images[$index])) {
            // Hapus dari storage
            Storage::disk('public')->delete($images[$index]);

            // Hapus dari array
            unset($images[$index]);

            // Simpan ulang string baru
            $class->image = implode(',', array_values($images));
            $class->save();
        }

        return back()->with('success', 'Foto berhasil dihapus!');
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
