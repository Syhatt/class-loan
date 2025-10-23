@extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <!-- Basic Card Example -->
    <div class="d-flex align-items-center justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a href="{{ route('booking.index') }}" class="btn btn-danger btn-sm"><i class="fas fa-arrow-left"></i>
                        Kembali</a>
                </div>

                {{-- @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif --}}

                <div class="card-body">
                    <form action="{{ route('booking.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="form-group">
                            <label>Kelas</label>
                            <input type="hidden" name="faculty_id" value="{{ $class->faculty_id }}">
                            <input type="text" class="form-control" value="{{ $class->name }}" readonly>
                            <input type="hidden" name="classmodel_id" value="{{ $class->id }}">
                            @error('classmodel_id')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Waktu Peminjaman (Mulai - Selesai)</label>
                            <div class="input-group">
                                <input type="datetime-local" name="start_datetime" class="form-control" required>
                                <span class="input-group-text">s.d.</span>
                                <input type="datetime-local" name="end_datetime" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nama Organisasi</label>
                            <input type="text" name="organization" class="form-control"
                                value="{{ old('organization') }}">
                            @error('organization')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Nama Kegiatan</label>
                            <input type="text" name="activity_name" class="form-control"
                                value="{{ old('activity_name') }}">
                            @error('activity_name')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Nama Lengkap Mahasiswa</label>
                            <input type="text" name="full_name" class="form-control" value="{{ auth()->user()->name }}"
                                readonly>
                            @error('full_name')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>NIM</label>
                            <input type="text" name="nim" class="form-control" value="{{ auth()->user()->nim }}"
                                readonly>
                            @error('nim')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Semester</label>
                            <input type="text" name="semester" class="form-control"
                                value="{{ auth()->user()->semester }}" readonly>
                            @error('semester')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Prodi</label>
                            <input type="text" name="prodi" class="form-control"
                                value="{{ auth()->user()->studyProgram->name }}" readonly>
                            @error('prodi')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>No Telp</label>
                            <input type="text" name="telp" class="form-control" value="{{ old('telp') }}">
                            @error('telp')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>No Surat Permohonan Izin Kegiatan</label>
                            <input type="text" name="no_letter" class="form-control" value="{{ old('no_letter') }}">
                            @error('no_letter')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Tanggal Surat Permohonan Izin Kegiatan</label>
                            <input type="date" name="date_letter" class="form-control" value="{{ old('date_letter') }}">
                            @error('date_letter')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Surat Permohonan Izin Kegiatan</label>
                            <input type="file" name="apply_letter" class="form-control">
                            @error('apply_letter')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Proposal Kegiatan</label>
                            <input type="file" name="activity_proposal" class="form-control">
                            @error('activity_proposal')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
