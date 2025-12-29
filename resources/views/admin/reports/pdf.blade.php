<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #e8e8e8;
        }

        h3 {
            text-align: center;
            margin: 0;
        }

        .periode {
            text-align: center;
            margin-bottom: 10px;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <h3>LAPORAN PEMINJAMAN RUANGAN & BARANG</h3>

    <p class="periode">
        Periode:
        @if ($month)
            {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}
        @else
            Tahun {{ $year }}
        @endif
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Organisasi</th>
                <th>Ruangan</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp

            @forelse ($data as $loan)
                @if ($loan->bookingItems->count() > 0)
                    @foreach ($loan->bookingItems as $item)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $loan->user->name }}</td>
                            <td>{{ $loan->organization }}</td>
                            <td>{{ $loan->classmodel->name }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($loan->start_date)->format('d-m-Y') }}
                                s/d
                                {{ \Carbon\Carbon::parse($loan->end_date)->format('d-m-Y') }}
                            </td>
                            <td>{{ $item->item->name }}</td>
                            <td>{{ $item->qty }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->organization }}</td>
                        <td>{{ $loan->classmodel->name }}</td>
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
                    <td colspan="7" style="text-align:center;">
                        Tidak ada data peminjaman
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
