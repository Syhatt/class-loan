<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\StudyPrograms;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $pageTitle = 'Pengguna';
        $users = User::with(['faculty', 'studyProgram'])->latest()->paginate(10);
        return view('admin.user.index', compact('pageTitle', 'users'));
    }

    public function create()
    {
        $pageTitle = 'Tambah Pengguna';
        $faculties = Faculty::all();
        $studyPrograms = StudyPrograms::all();
        return view('admin.user.create', compact('pageTitle', 'faculties', 'studyPrograms'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'faculty_id'        => 'required|exists:faculties,id',
                'study_program_id'  => 'required|exists:study_programs,id',
                'name'              => 'required|string|max:255|unique:users,name',
                'email'             => 'required|email|unique:users,email',
                'password'          => 'required|min:8',
                // superadmin tidak boleh dibuat di sini
                'role'              => 'required|in:admin_ruangan,admin_barang,user,dosen',
                'nim'               => 'required|string|max:20',
                'semester'          => 'required|string|max:10',
            ],
            [
                'name.unique'       => 'Nama tersebut sudah terdaftar.',
                'email.unique'      => 'Email tersebut sudah digunakan.',
                'email.email'       => 'Format email tidak valid.',
                'required'          => 'Field :attribute wajib diisi.',
            ]
        );

        if ($request->role === 'superadmin') {
            return back()
                ->withErrors(['role' => 'Anda tidak diperbolehkan membuat akun superadmin.'])
                ->withInput();
        }

        User::create([
            'faculty_id'        => $request->faculty_id,
            'study_program_id'  => $request->study_program_id,
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => $request->role,
            'nim'               => $request->nim,
            'semester'          => $request->semester,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $pageTitle = 'Edit Pengguna';
        $faculties = Faculty::all();
        $studyPrograms = StudyPrograms::all();
        return view('admin.user.edit', compact('pageTitle', 'user', 'faculties', 'studyPrograms'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate(
            [
                'faculty_id'        => 'required|exists:faculties,id',
                'study_program_id'  => 'required|exists:study_programs,id',
                'name'              => 'required|string|max:255|unique:users,name,' . $user->id,
                'email'             => 'required|email|unique:users,email,' . $user->id,
                'role'              => 'required|in:admin_ruangan,admin_barang,user,dosen',
                'nim'               => 'required|string|max:20',
                'semester'          => 'required|string|max:10',
            ],
            [
                'name.unique'       => 'Nama tersebut sudah terdaftar.',
                'email.unique'      => 'Email tersebut sudah digunakan.',
                'email.email'       => 'Format email tidak valid.',
                'required'          => 'Field :attribute wajib diisi.',
            ]
        );

        if ($request->role === 'superadmin') {
            return back()
                ->withErrors(['role' => 'Anda tidak diperbolehkan mengubah role menjadi superadmin.'])
                ->withInput();
        }

        $user->update([
            'faculty_id'        => $request->faculty_id,
            'study_program_id'  => $request->study_program_id,
            'name'              => $request->name,
            'email'             => $request->email,
            'role'              => $request->role,
            'nim'               => $request->nim,
            'semester'          => $request->semester,
            'password'          => $request->password
                ? Hash::make($request->password)
                : $user->password,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }
}
