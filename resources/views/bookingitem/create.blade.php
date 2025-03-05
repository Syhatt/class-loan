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
                <a href="{{ route('bookingitem.index') }}" class="btn btn-danger btn-sm"><i class="fas fa-arrow-left"></i>
                    Kembali</a>
            </div>
            <div class="card-body">
                <form action="{{ route('bookingitem.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" value="{{ $items->name }}" readonly>
                        <input type="text" name="item_id" value="{{ $items->id }}">
                        @error('item_id')
                            <div class="text-danger"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" name="qty" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
