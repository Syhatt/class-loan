@extends('layouts.master')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    @if ($class->isEmpty())
        <div class="alert alert-warning">Tidak ada kelas yang aktif untuk saat ini.</div>
    @endif

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

        @php
            if (request('faculty_id')) {
                $class = $class->where('faculty_id', request('faculty_id'));
            }
        @endphp
    @endif


    <div class="row">
        @foreach ($class as $cls)
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    @php
                        $images = explode(',', $cls->image);
                    @endphp

                    <div class="d-flex flex-wrap gap-1 p-2">
                        @foreach ($images as $i => $img)
                            @if ($i < 3)
                                <img src="{{ asset('storage/' . $img) }}" class="img-thumbnail previewable"
                                    data-images="{{ implode(',', $images) }}" data-index="{{ $i }}"
                                    width="100" height="100" style="object-fit: cover; cursor: pointer;">
                            @endif
                        @endforeach
                        @if (count($images) > 3)
                            <div class="text-center w-100"><small>+{{ count($images) - 3 }} foto lainnya</small></div>
                        @endif
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">{{ $cls->name }}</h5>
                        <p class="card-text text-muted">{{ $cls->desc }}</p>
                        <a href="{{ route('booking.tambah', $cls->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-bookmark"></i> Booking
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal preview -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark text-center">
                <div class="modal-body p-0">
                    <img id="previewImage" src="" class="img-fluid">
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
