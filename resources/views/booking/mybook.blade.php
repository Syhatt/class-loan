@extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ route('item.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Ruangan</th>
                            <th>Tanggal Peminjaman</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Keperluan</th>
                            <th>Jaminan</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking as $booking)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->classmodel->name }}</td>
                                <td>{{ $booking->start_date }}</td>
                                <td>{{ $booking->start_time }}</td>
                                <td>{{ $booking->end_time }}</td>
                                <td>{{ $booking->activity_name }}</td>
                                <td>{{ $booking->organization }}</td>
                                {{-- <td>{{ $booking->status }}</td> --}}
                                <td>
                                    @if ($booking->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif ($booking->status == 'rejected')
                                        <span class="badge badge-danger">rejected</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('booking.destroy', $booking->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('booking.edit', $booking->id) }}"
                                            class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <button type="submit"
                                            onclick="return confirm('Apakah anda yakin mengahpus data ini?')"
                                            class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
