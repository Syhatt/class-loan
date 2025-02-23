@extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <!-- Basic Card Example -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ route('class.index') }}" class="btn btn-danger btn-sm"><i class="fas fa-arrow-left"></i>
                    Kembali</a>
            </div>
            <div class="card-body">
                <form action="{{ route('class.update', $classes->id) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" value="{{ $classes->name }}" class="form-control">
                        @error('name')
                            <div class="text-danger"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="desc" cols="30" rows="10" class="form-control">{{ $classes->desc }}</textarea>
                        @error('desc')
                            <div class="text-danger"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Gambar</label>
                        <input type="text" name="image" value="{{ $classes->image }}" class="form-control">
                        @error('image')
                            <div class="text-danger"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    {{-- <div class="form-group">
                        <label>Gambar</label>
                        <input type="file" name="image" class="form-control">
                    </div> --}}
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
