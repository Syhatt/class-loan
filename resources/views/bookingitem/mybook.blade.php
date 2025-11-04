@extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            {{-- <a href="{{ route('item.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a> --}}
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
                            <th>Nodin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookitem as $bookitem)
                            {{-- mengambil value dari class --}}
                            {{-- dd($bookitem->bookingClass->classmodel->name) --}}
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $bookitem->faculty->name }}</td>
                                <td>{{ $bookitem->user->name }}</td>
                                <td>{{ $bookitem->bookingClass->classmodel->name }}</td>
                                <td>{{ $bookitem->item->name }}</td>
                                <td>{{ $bookitem->qty }}</td>
                                <td>
                                    @if ($bookitem->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif ($bookitem->status == 'rejected')
                                        <span class="badge badge-danger">rejected</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($bookitem->nodin_barang)
                                        <a href="{{ asset($bookitem->nodin_barang) }}" class="btn btn-primary btn-sm"
                                            target="_blank">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @else
                                        <span class="text-muted">Belum tersedia</span>
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
