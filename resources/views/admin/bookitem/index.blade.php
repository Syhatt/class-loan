@extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            {{-- <a href="{{ route('bookitem.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Fakultas</th>
                            <th>User</th>
                            <th>Kelas</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th width="70px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking as $booking)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->faculty->name }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $booking->bookingClass->classmodel->name }}</td>
                                <td>{{ $booking->item->name }}</td>
                                <td>{{ $booking->qty }}</td>
                                <td>
                                    @if ($booking->status == 'approved')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif($booking->status == 'rejected')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('bookitem.show', $booking->id) }}" class="btn btn-info btn-sm"><i
                                            class="fas fa-search"></i> Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
