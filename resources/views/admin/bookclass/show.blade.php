@extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <a href="{{ route('bookclass.index') }}" class="btn btn-danger btn-sm mb-3"><i class="fas fa-arrow-left"></i>
        Kembali</a>

    <div class="container mb-5">
        <div class="d-flex align-items-center justify-content-center">
            <div class="col-lg-8">
                <table class="table table-hover table-striped table-bordered">
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $booking->classmodel->name }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Mulai Kegiatan</td>
                        <td>:</td>
                        <td>{{ $booking->start_date }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Selesai Kegiatan</td>
                        <td>:</td>
                        <td>{{ $booking->end_date }}</td>
                    </tr>
                    <tr>
                        <td>Jam Mulai Kegiatan</td>
                        <td>:</td>
                        <td>{{ $booking->start_time }}</td>
                    </tr>
                    <tr>
                        <td>Jam Selesai Kegiatan</td>
                        <td>:</td>
                        <td>{{ $booking->end_time }}</td>
                    </tr>
                    <tr>
                        <td>Nama Organisasi</td>
                        <td>:</td>
                        <td>{{ $booking->organization }}</td>
                    </tr>
                    <tr>
                        <td>Nama Kegiatan</td>
                        <td>:</td>
                        <td>{{ $booking->activity_name }}</td>
                    </tr>
                    <tr>
                        <td>Nama Lengkap Mahasiswa</td>
                        <td>:</td>
                        <td>{{ $booking->full_name }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $booking->nim }}</td>
                    </tr>
                    <tr>
                        <td>Semester</td>
                        <td>:</td>
                        <td>{{ $booking->semester }}</td>
                    </tr>
                    <tr>
                        <td>Prodi</td>
                        <td>:</td>
                        <td>{{ $booking->prodi }}</td>
                    </tr>
                    <tr>
                        <td>No Telp</td>
                        <td>:</td>
                        <td>{{ $booking->telp }}</td>
                    </tr>
                    <tr>
                        <td>No Surat Permohonan Izin Kegiatan</td>
                        <td>:</td>
                        <td>{{ $booking->no_letter }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Surat Permohonan Izin Kegiatan</td>
                        <td>:</td>
                        <td>{{ $booking->date_letter }}</td>
                    </tr>
                    <tr>
                        <td>Surat Permohonan Izin Kegiatan</td>
                        <td>:</td>
                        <td>
                            <a href="{{ asset('storage/' . $booking->apply_letter) }}" class="btn btn-primary btn-sm"
                                download>Download File</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Proposal Kegiatan</td>
                        <td>:</td>
                        <td>
                            <a href="{{ asset('storage/' . $booking->activity_proposal) }}" class="btn btn-primary btn-sm"
                                download>Download File</a>
                        </td>
                    </tr>
                </table>

                <div class="container text-center mt-4">
                    @if ($booking->status === 'pending')
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <form action="{{ route('bookclass.update', $booking->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="status" value="approved">

                                    <div class="form-group mt-2">
                                        <label>No Surat Nodin</label>
                                        <input type="text" name="no_surat" class="form-control" required>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label>Nama Wakil Dekan</label>
                                        <input type="text" name="nama_wadek" class="form-control" required>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label>Upload Tanda Tangan (TTD)</label>
                                        <input type="file" name="ttd_admin" class="form-control" accept="image/*"
                                            required>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-sm mt-3">
                                        <i class="far fa-check-circle"></i> Approve & Generate Nodin
                                    </button>
                                </form>

                                <form action="{{ route('bookclass.update', $booking->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-times-circle"></i> Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif ($booking->status === 'approved')
                        @php
                            $nodin =
                                $booking->nodin ?? \App\Models\Nodin::where('booking_class_id', $booking->id)->first();
                        @endphp
                        @if ($nodin && $nodin->file_path)
                            <div class="alert alert-success text-center">
                                <h5 class="mb-3">Peminjaman telah disetujui ✅</h5>
                                <a href="{{ asset('storage/' . $nodin->file_path) }}" target="_blank"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-file-download"></i> Download Nota Dinas (PDF)
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                File Nota Dinas belum tersedia.
                            </div>
                        @endif
                    @elseif ($booking->status === 'rejected')
                        <div class="alert alert-danger text-center">
                            <i class="fas fa-times-circle"></i> Peminjaman ini telah ditolak.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
