@extends('layouts.master')
@section('content')
    <h3>{{ $pageTitle }}</h3>

    {{-- NOTIF ERROR --}}
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
        <form action="{{ route('faculty.store') }}" method="post">
            @csrf

            <div class="form-group">
                <label>Kode Fakultas</label>
                <input type="text"
                       name="code"
                       class="form-control @error('code') is-invalid @enderror"
                       value="{{ old('code') }}">
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Nama Fakultas</label>
                <input type="text"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary btn-sm">Simpan</button>
            <a href="{{ route('faculty.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </form>
    </div>
@endsection
