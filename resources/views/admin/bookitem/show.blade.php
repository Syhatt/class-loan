@extends('layouts.master')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <a href="{{ route('bookitem.index') }}" class="btn btn-danger btn-sm mb-3"><i class="fas fa-arrow-left"></i>
        Kembali</a>

    <div class="card shadow mb-4">
        <div class="card-body">
            <h5>Detail Peminjaman Barang</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Nama Mahasiswa</th>
                    <td>{{ $booking->user->name }}</td>
                </tr>
                <tr>
                    <th>Barang</th>
                    <td>{{ $booking->item->name }}</td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td>{{ $booking->qty }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst($booking->status) }}</td>
                </tr>
            </table>

            <h5 class="mt-4">Detail Ruangan (Acara)</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Nama Kegiatan</th>
                    <td>{{ $booking->bookingClass->activity_name }}</td>
                </tr>
                <tr>
                    <th>Tanggal Mulai</th>
                    <td>{{ $booking->bookingClass->start_date }}</td>
                </tr>
                <tr>
                    <th>Tanggal Selesai</th>
                    <td>{{ $booking->bookingClass->end_date }}</td>
                </tr>
                <tr>
                    <th>Nodin Peminjaman Ruangan</th>
                    <td>
                        @if ($booking->bookingClass->nodin)
                            <a href="{{ asset($booking->bookingClass->nodin->file_path) }}" target="_blank"
                                class="btn btn-sm btn-primary">Download</a>
                        @else
                            <span class="text-danger">Belum ada</span>
                        @endif
                    </td>
                </tr>
            </table>

            <h5 class="mt-4">Approval Peminjaman Barang</h5>
            <form action="{{ route('bookitem.update', $booking->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Status Approval</label>
                    <select name="status" class="form-control" required>
                        <option value="approved">Approve</option>
                        <option value="rejected">Reject</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Upload Nodin Peminjaman Barang</label>
                    <input type="file" name="nodin_barang" class="form-control">
                    @if ($booking->nodin_barang)
                        <p class="mt-2">File saat ini: <a
                                href="{{ asset('uploads/nodin_barang/' . $booking->nodin_barang) }}"
                                target="_blank">Download</a></p>
                    @endif
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
@endsection
