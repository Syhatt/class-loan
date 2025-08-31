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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Pengguna';
        $users = User::with(['faculty', 'studyProgram'])->latest()->paginate(10);
        // dd($users);
        return view('admin.user.index', compact('pageTitle', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Tambah Pengguna';
        $faculties = Faculty::all();
        $studyPrograms = StudyPrograms::all();
        return view('admin.user.create', compact('pageTitle', 'faculties', 'studyPrograms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'study_program_id' => 'required|exists:study_programs,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:superadmin,admin_ruangan,admin_barang,user,dosen',
            'nim' => 'required|string|max:20',
            'semester' => 'required|string|max:10',
        ]);

        User::create([
            'faculty_id' => $request->faculty_id,
            'study_program_id' => $request->study_program_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nim' => $request->nim,
            'semester' => $request->semester,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
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
    public function edit(User $user)
    {
        $pageTitle = 'Edit Pengguna';
        $faculties = Faculty::all();
        $studyPrograms = StudyPrograms::all();
        return view('admin.user.edit', compact('pageTitle', 'user', 'faculties', 'studyPrograms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'study_program_id' => 'required|exists:study_programs,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:superadmin,admin_ruangan,admin_barang,user,dosen',
            'nim' => 'required|string|max:20',
            'semester' => 'required|string|max:10',
        ]);

        $user->update([
            'faculty_id' => $request->faculty_id,
            'study_program_id' => $request->study_program_id,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'nim' => $request->nim,
            'semester' => $request->semester,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }
}
