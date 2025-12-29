@extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            {{-- kosong --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Fakultas</th>
                            <th>User</th>
                            <th>Ruangan</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Nodin</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($bookitem as $row)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->faculty->name }}</td>
                                <td>{{ $row->user->name }}</td>
                                <td>{{ $row->bookingClass->classmodel->name }}</td>
                                <td>{{ $row->item->name }}</td>
                                <td>{{ $row->qty }}</td>

                                <td>
                                    @if ($row->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif ($row->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif ($row->status == 'returned')
                                        <span class="badge badge-primary">Dikembalikan</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>

                                <td>
                                    @if (!empty($row->nodin_barang))
                                        <a href="{{ asset('storage/' . $row->nodin_barang) }}" class="btn btn-primary btn-sm"
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
