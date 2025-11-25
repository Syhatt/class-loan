@extends('layouts.master')
@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
</div>

<div class="col-lg-6">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ route('class.index') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-body">

            {{-- ALERT ERROR GLOBAL --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Oops! Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('class.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                {{-- Fakultas --}}
                <div class="form-group">
                    <label>Pilih Fakultas</label>
                    <select name="faculty_id" class="form-control @error('faculty_id') is-invalid @enderror">
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

                {{-- Nama --}}
                <div class="form-group">
                    <label>Nama Kelas</label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="desc"
                              cols="30"
                              rows="5"
                              class="form-control @error('desc') is-invalid @enderror">{{ old('desc') }}</textarea>
                    @error('desc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Foto --}}
                <div class="form-group">
                    <label>Foto Kelas (bisa pilih banyak)</label>
                    <input type="file"
                           name="image[]"
                           class="form-control @error('image.*') is-invalid @enderror"
                           multiple>
                    @error('image.*')
                        <div class="text-danger small">{{ $message }}</div>
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
