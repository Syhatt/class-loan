<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Classmodel;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Kelas';

        // Jika superadmin → bisa lihat semua + filter fakultas
        if (auth()->user()->role === 'superadmin') {

            // Ambil daftar fakultas untuk dropdown filter
            $faculties = Faculty::all();

            // Jika ada request filter fakultas
            $classes = Classmodel::with('faculty')
                ->when($request->faculty_id, function ($query) use ($request) {
                    $query->where('faculty_id', $request->faculty_id);
                })
                ->get();

            return view('admin.class.index', compact('pageTitle', 'classes', 'faculties'));
        }

        // Jika admin fakultas → hanya lihat fakultasnya
        $classes = Classmodel::where('faculty_id', auth()->user()->faculty_id)->get();

        return view('admin.class.index', compact('pageTitle', 'classes'));
    }

    /**
     * Toggle status aktif / tidak aktif kelas
     */
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
    $request->validate(
        [
            'faculty_id' => 'required|exists:faculties,id',
            'name'       => [
                'required',
                // nama kelas unik per fakultas
                Rule::unique('classmodels')->where(function ($q) use ($request) {
                    return $q->where('faculty_id', $request->faculty_id);
                }),
            ],
            'desc'       => 'required',
            'image.*'    => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],
        [
            'faculty_id.required' => 'Fakultas wajib dipilih.',
            'faculty_id.exists'   => 'Fakultas tidak valid.',
            'name.required'       => 'Nama kelas wajib diisi.',
            'name.unique'         => 'Nama kelas tersebut sudah ada di fakultas ini.',
            'desc.required'       => 'Deskripsi kelas wajib diisi.',
            'image.*.required'    => 'Foto kelas wajib diunggah.',
            'image.*.image'       => 'File foto harus berupa gambar.',
            'image.*.mimes'       => 'Foto harus berformat jpeg, png, jpg, atau gif.',
            'image.*.max'         => 'Ukuran foto maksimal 2MB.',
        ]
    );

    $imagePaths = [];

    if ($request->hasFile('image')) {
        foreach ($request->file('image') as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $path     = $file->storeAs('images', $filename, 'public');
            $imagePaths[] = $path;
        }
    }

    Classmodel::create([
        'faculty_id' => $request->faculty_id,
        'name'       => $request->name,
        'desc'       => $request->desc,
        'image'      => implode(',', $imagePaths),
    ]);

    return redirect()
        ->route('class.index')
        ->with('success', 'Data kelas berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // jika suatu saat butuh detail kelas
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Kelas';
        $classes   = Classmodel::findOrFail($id);

        return view('admin.class.edit', compact('pageTitle', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $class = Classmodel::findOrFail($id);

        $request->validate(
            [
                'name' => [
                    'required',
                    // nama kelas harus unik per fakultas, kecuali dirinya sendiri
                    Rule::unique('classmodels')
                        ->where(function ($q) use ($class) {
                            return $q->where('faculty_id', $class->faculty_id);
                        })
                        ->ignore($class->id),
                ],
                'desc'    => 'required',
                'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'name.required'   => 'Nama kelas wajib diisi.',
                'name.unique'     => 'Nama kelas tersebut sudah ada di fakultas ini.',
                'desc.required'   => 'Deskripsi kelas wajib diisi.',
                'image.*.image'   => 'File foto harus berupa gambar.',
                'image.*.mimes'   => 'Foto harus berformat jpeg, png, jpg, atau gif.',
                'image.*.max'     => 'Ukuran foto maksimal 2MB.',
            ]
        );

        // Ambil semua gambar lama (kalau kosong, jadikan array kosong)
        $imagePaths = [];

        if (!empty($class->image)) {
            $imagePaths = explode(',', $class->image);
        }

        // Kalau ada file baru, tambahkan
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path     = $file->storeAs('images', $filename, 'public');
                $imagePaths[] = $path;
            }
        }

        $class->update([
            'name'  => $request->name,
            'desc'  => $request->desc,
            'image' => implode(',', $imagePaths),
        ]);

        return redirect()->route('class.edit', $id)->with('success', 'Data kelas berhasil diperbarui!');
    }

    /**
     * Hapus salah satu gambar dari kelas
     */
    public function deleteImage($id, $index)
    {
        $class = Classmodel::findOrFail($id);

        $images = [];
        if (!empty($class->image)) {
            $images = explode(',', $class->image);
        }

        if (isset($images[$index])) {
            // Hapus dari storage
            Storage::disk('public')->delete($images[$index]);

            // Hapus dari array
            unset($images[$index]);

            // Susun ulang index & simpan string baru
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
        $class = Classmodel::findOrFail($id);

        // Opsional: sekalian hapus file gambarnya dari storage
        if (!empty($class->image)) {
            $images = explode(',', $class->image);
            foreach ($images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $class->delete();

        return redirect()->route('class.index')->with(['success' => 'Data kelas berhasil dihapus!']);
    }
}
