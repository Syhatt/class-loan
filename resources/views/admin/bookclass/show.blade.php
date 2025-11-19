{{-- @extends('layouts.master')
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
                                <a href="{{ asset($booking->nodin->file_path) }}" target="_blank"
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
@endsection --}}

@extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <a href="{{ route('bookclass.index') }}" class="btn btn-danger btn-sm mb-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="container mb-5">
        <div class="d-flex align-items-center justify-content-center">
            <div class="col-lg-8">
                <table class="table table-hover table-striped table-bordered">
                    <tr>
                        <td><strong>Kelas</strong></td>
                        <td>:</td>
                        <td>{{ $booking->classmodel->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Mulai Kegiatan</strong></td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($booking->start_date)->translatedFormat('l, d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Selesai Kegiatan</strong></td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($booking->end_date)->translatedFormat('l, d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jam Mulai Kegiatan</strong></td>
                        <td>:</td>
                        <td>{{ $booking->start_time }} WIB</td>
                    </tr>
                    <tr>
                        <td><strong>Jam Selesai Kegiatan</strong></td>
                        <td>:</td>
                        <td>{{ $booking->end_time }} WIB</td>
                    </tr>
                    <tr>
                        <td><strong>Nama Organisasi</strong></td>
                        <td>:</td>
                        <td>{{ $booking->organization }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama Kegiatan</strong></td>
                        <td>:</td>
                        <td>{{ $booking->activity_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama Lengkap Mahasiswa</strong></td>
                        <td>:</td>
                        <td>{{ $booking->full_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIM</strong></td>
                        <td>:</td>
                        <td>{{ $booking->nim }}</td>
                    </tr>
                    <tr>
                        <td><strong>Semester</strong></td>
                        <td>:</td>
                        <td>{{ $booking->semester }}</td>
                    </tr>
                    <tr>
                        <td><strong>Prodi</strong></td>
                        <td>:</td>
                        <td>{{ $booking->prodi }}</td>
                    </tr>
                    <tr>
                        <td><strong>No Telp</strong></td>
                        <td>:</td>
                        <td>{{ $booking->telp }}</td>
                    </tr>
                    <tr>
                        <td><strong>No Surat Permohonan Izin Kegiatan</strong></td>
                        <td>:</td>
                        <td>{{ $booking->no_letter }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Surat Permohonan</strong></td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($booking->date_letter)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Surat Permohonan Izin Kegiatan</strong></td>
                        <td>:</td>
                        <td>
                            <a href="{{ asset('storage/' . $booking->apply_letter) }}" class="btn btn-primary btn-sm"
                                target="_blank">
                                <i class="fas fa-download"></i> Download File
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Proposal Kegiatan</strong></td>
                        <td>:</td>
                        <td>
                            <a href="{{ asset('storage/' . $booking->activity_proposal) }}" class="btn btn-primary btn-sm"
                                target="_blank">
                                <i class="fas fa-download"></i> Download File
                            </a>
                        </td>
                    </tr>
                </table>

                {{-- BAGIAN FORM APPROVAL / HASIL --}}
                <div class="container text-center mt-4">
                    @if ($booking->status === 'pending')
                        {{-- FORM APPROVAL --}}
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Form Approval Peminjaman Ruangan</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('bookclass.update', $booking->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="status" value="approved">

                                    <div class="form-group text-left">
                                        <label>No Surat Nota Dinas <span class="text-danger">*</span></label>
                                        <input type="text" name="no_surat" class="form-control" 
                                               placeholder="Contoh: 001/FST/2025" required>
                                        @error('no_surat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group text-left">
                                        <label>Nama Wakil Dekan <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_wadek" class="form-control" 
                                               placeholder="Contoh: Dr. H. Ahmad, M.Pd" required>
                                        @error('nama_wadek')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group text-left">
                                        <label>Upload Tanda Tangan (TTD) <span class="text-danger">*</span></label>
                                        <input type="file" name="ttd_admin" class="form-control" accept="image/*" required>
                                        <small class="form-text text-muted">Format: PNG, JPG, JPEG. Max: 2MB</small>
                                        @error('ttd_admin')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-success btn-sm mt-3">
                                        <i class="far fa-check-circle"></i> Approve & Generate Nota Dinas
                                    </button>
                                </form>

                                <form action="{{ route('bookclass.update', $booking->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menolak peminjaman ini?')">
                                        <i class="fas fa-times-circle"></i> Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif ($booking->status === 'approved')
                        {{-- SUDAH APPROVED: TAMPILKAN DOWNLOAD NODIN --}}
                        @php
                            $nodin = $booking->nodin ?? \App\Models\Nodin::where('booking_class_id', $booking->id)->first();
                        @endphp
                        @if ($nodin && $nodin->file_path)
                            <div class="alert alert-success">
                                <h5 class="mb-3">✅ Peminjaman telah disetujui</h5>
                                <a href="{{ asset($nodin->file_path) }}" target="_blank"
                                    class="btn btn-primary">
                                    <i class="fas fa-file-download"></i> Download Nota Dinas (PDF)
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> File Nota Dinas belum tersedia.
                            </div>
                        @endif
                    @elseif ($booking->status === 'rejected')
                        {{-- REJECTED --}}
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i> Peminjaman ini telah ditolak.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
