@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ route('user.index') }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">

                {{-- ALERT ERROR GLOBAL --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user.store') }}" method="POST">
                    @csrf

                    {{-- NAMA --}}
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NIM --}}
                    <div class="mb-3">
                        <label>NIM</label>
                        <input type="text"
                               name="nim"
                               class="form-control @error('nim') is-invalid @enderror"
                               value="{{ old('nim') }}">
                        @error('nim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SEMESTER --}}
                    <div class="mb-3">
                        <label>Semester</label>
                        <input type="text"
                               name="semester"
                               class="form-control @error('semester') is-invalid @enderror"
                               value="{{ old('semester') }}">
                        @error('semester')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ROLE --}}
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role"
                                class="form-control @error('role') is-invalid @enderror">
                            <option value="">-- Pilih Role --</option>
                            @foreach (['admin_fakultas', 'user', 'dosen'] as $role)
                                <option value="{{ $role }}"
                                    {{ old('role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- FAKULTAS --}}
                    <div class="mb-3">
                        <label>Fakultas</label>
                        <select name="faculty_id"
                                class="form-control @error('faculty_id') is-invalid @enderror">
                            <option value="">-- Pilih Fakultas --</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}"
                                    {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('faculty_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PRODI --}}
                    <div class="mb-3">
                        <label>Program Studi</label>
                        <select name="study_program_id"
                                class="form-control @error('study_program_id') is-invalid @enderror">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($studyPrograms as $sp)
                                <option value="{{ $sp->id }}"
                                    {{ old('study_program_id') == $sp->id ? 'selected' : '' }}>
                                    {{ $sp->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('study_program_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
