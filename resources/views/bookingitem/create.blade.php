@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ route('bookingitem.index') }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">

                {{-- pesan error umum --}}
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('bookingitem.store') }}" method="post">
                    @csrf

                    {{-- PILIH RUANGAN --}}
                    <div class="form-group">
                        <label>Pilih Ruangan / Acara</label>
                        <select name="booking_class_id" class="form-control" required>
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach ($approvedRooms as $room)
                                <option value="{{ $room->id }}"
                                    {{ old('booking_class_id') == $room->id ? 'selected' : '' }}>
                                    {{ $room->activity_name }} - {{ $room->classmodel->name }}
                                    ({{ $room->start_date }} s/d {{ $room->end_date }})
                                </option>
                            @endforeach
                        </select>
                        @error('booking_class_id')
                            <div class="text-danger"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    {{-- NAMA BARANG --}}
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="hidden" name="faculty_id" value="{{ $items->faculty_id }}">
                        <input type="text" class="form-control" value="{{ $items->name }}" readonly>
                        <input type="hidden" name="item_id" value="{{ $items->id }}">
                        @error('item_id')
                            <div class="text-danger"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    {{-- JUMLAH --}}
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input
                            type="number"
                            name="qty"
                            class="form-control @error('qty') is-invalid @enderror"
                            value="{{ old('qty') }}"
                            required
                        >
                        <small class="text-muted">Stok tersedia: {{ $items->stock }}</small>
                        @error('qty')
                            <div class="text-danger"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    <hr>

                    <h5>Jadwal Pengembalian Barang</h5>

                    {{-- TANGGAL (otomatis tentukan hari di backend) --}}
                    <div class="form-group">
                        <label>
                            Tanggal Pengembalian
                            <small class="text-muted">(hanya Senin–Jumat)</small>
                        </label>
                        <input
                            type="date"
                            name="tanggal_pengembalian"
                            class="form-control @error('tanggal_pengembalian') is-invalid @enderror"
                            value="{{ old('tanggal_pengembalian') }}"
                            required
                        >
                        @error('tanggal_pengembalian')
                            <div class="text-danger"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    {{-- JAM --}}
                    <div class="form-group">
                        <label>
                            Jam Pengembalian
                            <small class="text-muted">(07.00–15.00)</small>
                        </label>
                        <input
                            type="time"
                            name="jam_pengembalian"
                            class="form-control @error('jam_pengembalian') is-invalid @enderror"
                            value="{{ old('jam_pengembalian') }}"
                            required
                        >
                        @error('jam_pengembalian')
                            <div class="text-danger"><small>{{ $message }}</small></div>
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
