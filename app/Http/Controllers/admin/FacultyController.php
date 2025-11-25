<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index()
    {
    $pageTitle = 'Data Fakultas';
    $faculties = Faculty::with('studyPrograms')->get(); // penting: with()

    return view('admin.faculty.index', compact('pageTitle', 'faculties'));
    }


    public function create()
    {
        $pageTitle = 'Tambah Fakultas';

        return view('admin.faculty.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'code' => 'required|unique:faculties,code',
                'name' => 'required|unique:faculties,name',
            ],
            [
                'code.required' => 'Kode fakultas wajib diisi.',
                'code.unique'   => 'Kode fakultas sudah digunakan.',
                'name.required' => 'Nama fakultas wajib diisi.',
                'name.unique'   => 'Nama fakultas sudah ada.',
            ]
        );

        Faculty::create($request->only('code', 'name'));

        return redirect()
            ->route('faculty.index')
            ->with('success', 'Fakultas berhasil ditambahkan!');
    }

    public function edit(Faculty $faculty)
    {
        $pageTitle = 'Edit Fakultas';

        return view('admin.faculty.edit', compact('pageTitle', 'faculty'));
    }

    public function update(Request $request, Faculty $faculty)
    {
        $request->validate(
            [
                'code' => 'required|unique:faculties,code,' . $faculty->id,
                'name' => 'required|unique:faculties,name,' . $faculty->id,
            ],
            [
                'code.required' => 'Kode fakultas wajib diisi.',
                'code.unique'   => 'Kode fakultas sudah digunakan.',
                'name.required' => 'Nama fakultas wajib diisi.',
                'name.unique'   => 'Nama fakultas sudah ada.',
            ]
        );

        $faculty->update($request->only('code', 'name'));

        return redirect()
            ->route('faculty.index')
            ->with('success', 'Data fakultas berhasil diperbarui!');
    }

    public function destroy(Faculty $faculty)
    {
        $faculty->delete();

        return redirect()
            ->route('faculty.index')
            ->with('success', 'Fakultas berhasil dihapus!');
    }
}
