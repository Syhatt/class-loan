@extends('layouts.master')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Fakultas</th>
                            <th>User</th>
                            <th>Kelas</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking as $b)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $b->faculty->name ?? '-' }}</td>
                                <td>{{ $b->user->name }}</td>
                                <td>{{ $b->bookingClass->classmodel->name }}</td>
                                <td>{{ $b->item->name }}</td>
                                <td>{{ $b->qty }}</td>
                                <td>
                                    @if ($b->status == 'approved')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif($b->status == 'rejected')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @elseif($b->status == 'returned')
                                        <span class="badge badge-info">Dikembalikan</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('bookitem.show', $b->id) }}" class="btn btn-info btn-sm"><i
                                            class="fas fa-search"></i></a>
                                    @if ($b->status == 'approved')
                                        <form action="{{ route('bookitem.update', $b->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="returned">
                                            <button class="btn btn-success btn-sm"
                                                onclick="return confirm('Konfirmasi barang sudah dikembalikan?')">Konfirmasi Pengembalian</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


{{-- @extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
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
@endsection --}}
