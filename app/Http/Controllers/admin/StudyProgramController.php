<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\StudyPrograms;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudyProgramController extends Controller
{
    public function create(Request $request)
    {
        $pageTitle = 'Tambah Program Studi';
        $faculties = Faculty::all();
        $facultyId = $request->faculty_id; // dari parameter link

        return view('admin.study_program.create', compact('pageTitle', 'faculties', 'facultyId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'name'       => [
                'required',
                Rule::unique('study_programs')->where(function ($q) use ($request) {
                    return $q->where('faculty_id', $request->faculty_id);
                }),
            ],
        ], [
            'faculty_id.required' => 'Fakultas wajib dipilih.',
            'faculty_id.exists'   => 'Fakultas tidak valid.',
            'name.required'       => 'Nama program studi wajib diisi.',
            'name.unique'         => 'Prodi dengan nama tersebut sudah ada di fakultas ini.',
        ]);

        StudyPrograms::create([
            'faculty_id' => $request->faculty_id,
            'name'       => $request->name,
        ]);

        return redirect()->route('faculty.index')->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function edit(StudyPrograms $study_program)
    {
        $pageTitle = 'Edit Program Studi';
        $faculties = Faculty::all();

        return view('admin.study_program.edit', [
            'pageTitle' => $pageTitle,
            'faculties' => $faculties,
            'studyProgram' => $study_program,
        ]);
    }

    public function update(Request $request, StudyPrograms $study_program)
    {
        $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'name'       => [
                'required',
                Rule::unique('study_programs')->where(function ($q) use ($request) {
                    return $q->where('faculty_id', $request->faculty_id);
                })->ignore($study_program->id),
            ],
        ], [
            'faculty_id.required' => 'Fakultas wajib dipilih.',
            'faculty_id.exists'   => 'Fakultas tidak valid.',
            'name.required'       => 'Nama program studi wajib diisi.',
            'name.unique'         => 'Prodi dengan nama tersebut sudah ada di fakultas ini.',
        ]);

        $study_program->update([
            'faculty_id' => $request->faculty_id,
            'name'       => $request->name,
        ]);

        return redirect()->route('faculty.index')->with('success', 'Program studi berhasil diperbarui.');
    }

    public function destroy(StudyPrograms $study_program)
    {
        $study_program->delete();

        return redirect()->route('faculty.index')->with('success', 'Program studi berhasil dihapus.');
    }
}
