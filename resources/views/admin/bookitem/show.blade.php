@extends('layouts.master')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <a href="{{ route('bookitem.index') }}" class="btn btn-danger btn-sm mb-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <div class="card shadow mb-4">
        <div class="card-body">
            <h5>Detail Peminjaman Barang</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Nama Mahasiswa</th>
                    <td>{{ $booking->user->name }}</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td>{{ $booking->user->nim }}</td>
                </tr>
                <tr>
                    <th>Barang</th>
                    <td>{{ $booking->item->name }}</td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td>{{ $booking->qty }} unit</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if ($booking->status == 'approved')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($booking->status == 'rejected')
                            <span class="badge badge-danger">Ditolak</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                </tr>
            </table>

            <h5 class="mt-4">Detail Ruangan / Acara</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Nama Kegiatan</th>
                    <td>{{ $booking->bookingClass->activity_name }}</td>
                </tr>
                <tr>
                    <th>Organisasi</th>
                    <td>{{ $booking->bookingClass->organization }}</td>
                </tr>
                <tr>
                    <th>Ruangan</th>
                    <td>{{ $booking->bookingClass->classmodel->name }}</td>
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
                                class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i> Download Nodin Ruangan
                            </a>
                        @else
                            <span class="text-danger">Belum ada</span>
                        @endif
                    </td>
                </tr>
            </table>

            <h5 class="mt-4">Jadwal Pengembalian Barang</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Hari</th>
                    <td>{{ $booking->hari_pengembalian }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ $booking->tanggal_pengembalian }}</td>
                </tr>
                <tr>
                    <th>Jam</th>
                    <td>{{ $booking->jam_pengembalian }}</td>
                </tr>
            </table>

            @if ($booking->status === 'pending')
                <div class="card mt-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">Form Approval Peminjaman Barang</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('bookitem.update', $booking->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="status" value="approved">

                            <div class="form-group">
                                <label>Nama Kabag Fakultas <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kabag" class="form-control" required>
                                @error('nama_kabag')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>NIP Kabag Fakultas <span class="text-danger">*</span></label>
                                <input type="text" name="nip_kabag" class="form-control" required>
                                @error('nip_kabag')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Upload Tanda Tangan Kabag <span class="text-danger">*</span></label>
                                <input type="file" name="ttd_kabag" class="form-control" accept="image/*" required>
                                <small class="form-text text-muted">Format: PNG, JPG, JPEG. Max: 2MB</small>
                                @error('ttd_kabag')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle"></i> Approve & Generate Surat
                                </button>
                            </div>
                        </form>

                        <form action="{{ route('bookitem.update', $booking->id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Yakin ingin menolak peminjaman ini?')">
                                <i class="fas fa-times-circle"></i> Reject
                            </button>
                        </form>
                    </div>
                </div>
            @elseif ($booking->status === 'approved')
                <div class="alert alert-success mt-4">
                    <h5 class="mb-3">✅ Peminjaman Telah Disetujui</h5>
                    @if ($booking->nodin_barang)
                        <a href="{{ asset($booking->nodin_barang) }}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-file-download"></i> Download Surat Pernyataan Kesanggupan
                        </a>
                    @else
                        <p class="text-warning">Surat belum tersedia</p>
                    @endif
                </div>
            @elseif ($booking->status === 'rejected')
                <div class="alert alert-danger mt-4 text-center">
                    <i class="fas fa-times-circle"></i> Peminjaman ini telah ditolak.
                </div>
            @endif
        </div>
    </div>
@endsection
