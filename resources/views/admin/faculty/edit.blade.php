@extends('layouts.master')
@section('content')
    <h3>{{ $pageTitle }}</h3>
    <div class="card col-lg-6 p-3 shadow">
        <form action="{{ route('faculty.update', $faculty->id) }}" method="post">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Kode Fakultas</label>
                <input type="text" name="code" class="form-control" value="{{ $faculty->code }}" required>
            </div>
            <div class="form-group">
                <label>Nama Fakultas</label>
                <input type="text" name="name" class="form-control" value="{{ $faculty->name }}" required>
            </div>
            <button class="btn btn-primary btn-sm">Update</button>
            <a href="{{ route('faculty.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </form>
    </div>
@endsection
