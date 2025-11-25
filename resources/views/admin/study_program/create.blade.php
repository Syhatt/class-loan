@extends('layouts.master')

@section('content')
    <h3>{{ $pageTitle }}</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card col-lg-6 p-3 shadow">
        <form action="{{ route('study_program.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Fakultas</label>
                <select name="faculty_id" class="form-control">
                    <option value="">-- Pilih Fakultas --</option>
                    @foreach ($faculties as $faculty)
                        <option value="{{ $faculty->id }}"
                            {{ old('faculty_id', $facultyId) == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Nama Program Studi</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
            </div>

            <button class="btn btn-primary btn-sm">Simpan</button>
            <a href="{{ route('faculty.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </form>
    </div>
@endsection
