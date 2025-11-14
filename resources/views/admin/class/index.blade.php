@extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    @if (auth()->user()->role === 'superadmin')
        <form method="GET" class="mb-3">
            <select name="faculty_id" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Fakultas</option>
                @foreach (\App\Models\Faculty::all() as $f)
                    <option value="{{ $f->id }}" {{ request('faculty_id') == $f->id ? 'selected' : '' }}>
                        {{ $f->name }}
                    </option>
                @endforeach
            </select>
        </form>
    @endif


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
                            <th>Fakultas</th>
                            <th>Image</th>
                            <th>Is Available</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classes as $class)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $class->name }}</td>
                                <td>{{ $class->desc }}</td>
                                <td>{{ $class->faculty->name }}</td>
                                <td>
                                    @php
                                        $images = explode(',', $class->image);
                                    @endphp
                                    <div class="d-flex flex-wrap justify-content-center gap-2">
                                        @foreach ($images as $index => $img)
                                            @if ($index < 3)
                                                <img src="{{ asset('storage/' . $img) }}" width="70" height="70"
                                                    class="img-thumbnail previewable"
                                                    data-images="{{ implode(',', $images) }}"
                                                    data-index="{{ $index }}"
                                                    style="object-fit: cover; cursor: pointer;">
                                            @endif
                                        @endforeach
                                        @if (count($images) > 3)
                                            <small class="text-muted">+{{ count($images) - 3 }} lagi</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($class->is_available == true)
                                        <span class="badge badge-success">Tersedia</span>
                                    @else
                                        <span class="badge badge-danger">Kosong</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('class.toggle', $class->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        @if ($class->is_available)
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-ban"></i> Nonaktifkan
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Aktifkan
                                            </button>
                                        @endif
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('class.destroy', $class->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('class.edit', $class->id) }}" class="btn btn-warning btn-sm"><i
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

    <!-- Modal Preview Foto -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark text-center">
                <div class="modal-body p-0">
                    <img id="previewImage" src="" class="img-fluid" style="max-height: 80vh;">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light btn-sm" id="prevBtn">Sebelumnya</button>
                    <button type="button" class="btn btn-light btn-sm" id="nextBtn">Berikutnya</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let images = [];
        let currentIndex = 0;

        document.querySelectorAll('.previewable').forEach(img => {
            img.addEventListener('click', e => {
                images = e.target.dataset.images.split(',');
                currentIndex = parseInt(e.target.dataset.index);
                document.getElementById('previewImage').src = '/storage/' + images[currentIndex];
                $('#imagePreviewModal').modal('show');
            });
        });

        document.getElementById('prevBtn').addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            document.getElementById('previewImage').src = '/storage/' + images[currentIndex];
        });

        document.getElementById('nextBtn').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % images.length;
            document.getElementById('previewImage').src = '/storage/' + images[currentIndex];
        });
    </script>
@endsection
