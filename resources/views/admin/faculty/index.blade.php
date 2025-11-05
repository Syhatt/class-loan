@extends('layouts.master')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>{{ $pageTitle }}</h3>
        <a href="{{ route('faculty.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Fakultas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($faculties as $faculty)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $faculty->code }}</td>
                            <td>{{ $faculty->name }}</td>
                            <td>
                                <a href="{{ route('faculty.edit', $faculty->id) }}" class="btn btn-warning btn-sm"><i
                                        class="fas fa-edit"></i></a>

                                <form action="{{ route('faculty.destroy', $faculty->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Yakin hapus fakultas ini?')"
                                        class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
