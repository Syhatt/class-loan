@extends('layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ $pageTitle }}</h1>

        <form class="form-inline" method="GET" action="{{ route('report.index') }}">
            {{-- FILTER BULAN (boleh kosong = semua bulan) --}}
            <select name="month" class="form-control mr-2">
                <option value="">Semua Bulan</option>
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}" {{ (string)$m === (string)$month ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endforeach
            </select>

            {{-- FILTER TAHUN (default: tahun ini) --}}
            <select name="year" class="form-control mr-2">
                @foreach (range(date('Y') - 3, date('Y')) as $y)
                    <option value="{{ $y }}" {{ (string)$y === (string)$year ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>

            <button class="btn btn-primary">Filter</button>
        </form>

        <a href="{{ route('report.export.pdf', ['month' => $month, 'year' => $year]) }}" class="btn btn-danger ml-2">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Peminjam</th>
                        <th>Organisasi</th>
                        <th>Nama Ruangan</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $loan)
                        @php $barang = $loan->bookingItems; @endphp

                        @if ($barang->count() > 0)
                            @foreach ($barang as $item)
                                <tr class="text-center">
                                    <td>{{ $loop->parent->iteration }}</td>
                                    <td>{{ $loan->user->name }}</td>
                                    <td>{{ $loan->organization }}</td>
                                    <td>{{ $loan->classmodel->name }}</td>
                                    <td>{{ $loan->start_date }} s/d {{ $loan->end_date }}</td>
                                    <td>{{ $item->item->name }}</td>
                                    <td>{{ $item->qty }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $loan->user->name }}</td>
                                <td>{{ $loan->organization }}</td>
                                <td>{{ $loan->classmodel->name }}</td>
                                <td>{{ $loan->start_date }} s/d {{ $loan->end_date }}</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
