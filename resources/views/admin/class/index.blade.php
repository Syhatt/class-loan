@extends('layouts.master')
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ route('class.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Name</th>
                            <th>Desc</th>
                            <th>Image</th>
                            <th>Is Available</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classes as $class)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $class->name }}</td>
                                <td>{{ $class->desc }}</td>
                                <td>{{ $class->image }}</td>
                                <td>
                                    @if ($class->is_available == true)
                                        <span class="badge text-bg-success">Tersedia</span>
                                    @else
                                        <span class="badge text-bg-danger">Kosong</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
