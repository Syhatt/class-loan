@extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

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
                            <th>Fakultas</th>
                            <th>User</th>
                            <th>Kelas</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            {{-- <th>Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookitem as $bookitem)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $bookitem->faculty->name }}</td>
                                <td>{{ $bookitem->user->name }}</td>
                                <td>{{ $bookitem->booking_classes_id }}</td>
                                <td>{{ $bookitem->item->name }}</td>
                                <td>{{ $bookitem->qty }}</td>
                                <td>
                                    @if ($bookitem->status == "approved")
                                        <span class="badge badge-success">Approved</span>
                                    @elseif ($bookitem->status == "rejected")
                                        <span class="badge badge-danger">rejected</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                {{-- <td>
                                    <form action="{{ route('bookitem.destroy', $bookitem->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('bookitem.edit', $bookitem->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <button type="submit" onclick="return confirm('Apakah anda yakin mengahpus data ini?')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
