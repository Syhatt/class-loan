@extends('layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">{{ $pageTitle }}</h1>

    {{-- FORM FILTER --}}
    <form class="form-inline" method="GET" action="{{ route('report.index') }}">
        {{-- FILTER BULAN (opsional) --}}
        <select name="month" class="form-control mr-2">
            <option value="">Semua Bulan</option>
            @foreach (range(1, 12) as $m)
                <option value="{{ $m }}" {{ (string)$m === (string)$month ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>

        {{-- FILTER TAHUN --}}
        <select name="year" class="form-control mr-2">
            @foreach (range(date('Y'), date('Y') - 3) as $y)
                <option value="{{ $y }}" {{ (string)$y === (string)$year ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endforeach
        </select>

        <button class="btn btn-primary">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>

    {{-- EXPORT PDF --}}
    <a href="{{ route('report.export.pdf', ['year' => $year, 'month' => $month]) }}"
       class="btn btn-danger ml-2" target="_blank">
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
                @php $no = 1; @endphp

                @forelse ($data as $loan)
                    @if ($loan->bookingItems && $loan->bookingItems->count() > 0)
                        @foreach ($loan->bookingItems as $item)
                            <tr class="text-center">
                                <td>{{ $no++ }}</td>
                                <td>{{ $loan->user->name ?? '-' }}</td>
                                <td>{{ $loan->organization ?? '-' }}</td>
                                <td>{{ $loan->classmodel->name ?? '-' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($loan->start_date)->format('d-m-Y') }}
                                    s/d
                                    {{ \Carbon\Carbon::parse($loan->end_date)->format('d-m-Y') }}
                                </td>
                                <td>{{ $item->item->name ?? '-' }}</td>
                                <td>{{ $item->qty ?? '-' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td>{{ $no++ }}</td>
                            <td>{{ $loan->user->name ?? '-' }}</td>
                            <td>{{ $loan->organization ?? '-' }}</td>
                            <td>{{ $loan->classmodel->name ?? '-' }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($loan->start_date)->format('d-m-Y') }}
                                s/d
                                {{ \Carbon\Carbon::parse($loan->end_date)->format('d-m-Y') }}
                            </td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Tidak ada data peminjaman
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
