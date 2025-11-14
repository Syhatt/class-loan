@extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    @if (auth()->user()->role === 'superadmin')
        <form method="GET" class="mb-3">
            <select name="faculty_id" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Fakultas</option>
                @foreach ($faculties as $f)
                    <option value="{{ $f->id }}" {{ request('faculty_id') == $f->id ? 'selected' : '' }}>
                        {{ $f->name }}
                    </option>
                @endforeach
            </select>
        </form>
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
                            <th>Name</th>
                            <th>Fakultas</th>
                            <th>Desc</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($item as $item)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->faculty->name }}</td>
                                <td>{{ $item->desc }}</td>
                                <td>{{ $item->stock }}</td>
                                <td>
                                    <form action="{{ route('item.destroy', $item->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('item.edit', $item->id) }}" class="btn btn-warning btn-sm"><i
                                                class="fas fa-edit"></i></a>
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
