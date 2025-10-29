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
                        <label>Foto Kelas Saat Ini</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach (explode(',', $classes->image) as $index => $img)
                                <div class="position-relative m-2" style="display:inline-block;">
                                    <img src="{{ asset('storage/' . $img) }}" width="120" class="rounded border">
                                    <form action="{{ route('class.deleteImage', [$classes->id, $index]) }}" method="POST"
                                        style="position:absolute; top:0; right:0;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Hapus foto ini?')">&times;</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tambah Foto Baru (bisa lebih dari 1)</label>
                        <input type="file" name="image[]" class="form-control" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
