@extends('layouts.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Laporan Peminjaman Barang - Bulan {{ date('F', mktime(0, 0, 0, $month, 1)) }}</h2>

        @if ($itemBookings->isEmpty())
            <div class="alert alert-warning">Tidak ada data peminjaman barang untuk bulan ini.</div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peminjam</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Dipinjam</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itemBookings as $key => $booking)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->item->name }}</td>
                            <td>{{ $booking->qty }}</td>
                            <td>{{ $booking->booking_date }}</td>
                            <td>
                                @if ($booking->status == 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($booking->status == 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-warning">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('report.item.download', ['month' => $month]) }}" class="btn btn-danger mt-3">
                Download PDF
            </a>
        @endif
    </div>
@endsection
