@extends('layouts.master')
@section('content')
    <h3>{{ $pageTitle }}</h3>
    <div class="card col-lg-6 p-3 shadow">
        <form action="{{ route('faculty.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label>Kode Fakultas</label>
                <input type="text" name="code" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nama Fakultas</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <button class="btn btn-primary btn-sm">Simpan</button>
            <a href="{{ route('faculty.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </form>
    </div>
@endsection
