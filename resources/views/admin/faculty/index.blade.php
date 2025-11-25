@extends('layouts.master')

@section('content')
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">{{ $pageTitle }}</h3>
            <p class="text-muted mb-0">
                Kelola fakultas dan program studi dalam sistem ini.
            </p>
        </div>

        <a href="{{ route('faculty.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Tambah Fakultas
        </a>
    </div>

    {{-- FLASH SUCCESS --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- JIKA BELUM ADA FAKULTAS --}}
    @if ($faculties->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="fas fa-university fa-3x mb-3"></i>
            <p class="mb-2">Belum ada data fakultas.</p>
            <a href="{{ route('faculty.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Fakultas Pertama
            </a>
        </div>
    @else
        {{-- GRID KARTU FAKULTAS --}}
        <div class="row">
            @foreach ($faculties as $faculty)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body d-flex flex-column">

                            {{-- HEADER FAKULTAS --}}
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge badge-primary mb-1">
                                        {{ $faculty->code }}
                                    </span>
                                    <h5 class="card-title mb-0">
                                        {{ $faculty->name }}
                                    </h5>
                                </div>

                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('faculty.edit', $faculty->id) }}"
                                       class="btn btn-outline-warning"
                                       title="Edit fakultas">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('faculty.destroy', $faculty->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus fakultas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Hapus fakultas">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- INFORMASI JUMLAH PRODI --}}
                            <p class="text-muted small mb-3">
                                <i class="fas fa-stream mr-1"></i>
                                {{ $faculty->studyPrograms->count() }}
                                program studi terdaftar
                            </p>

                            {{-- LIST PRODI --}}
                            @if ($faculty->studyPrograms->isEmpty())
                                <div class="flex-grow-1 d-flex align-items-center">
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Belum ada program studi.
                                    </p>
                                </div>
                            @else
                                <ul class="list-group list-group-flush mb-3 flex-grow-1">
                                    @foreach ($faculty->studyPrograms as $sp)
                                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                            <span class="text-truncate mr-2">
                                                {{ $sp->name }}
                                            </span>
                                            <span class="btn-group btn-group-sm">
                                                <a href="{{ route('study_program.edit', $sp->id) }}"
                                                   class="btn btn-light border"
                                                   title="Edit prodi">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <form action="{{ route('study_program.destroy', $sp->id) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Hapus prodi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-light border" title="Hapus prodi">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            {{-- TOMBOL TAMBAH PRODI --}}
                            <div class="mt-auto">
                                <a href="{{ route('study_program.create', ['faculty_id' => $faculty->id]) }}"
                                   class="btn btn-success btn-sm btn-block">
                                    <i class="fas fa-plus mr-1"></i> Tambah Program Studi
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
