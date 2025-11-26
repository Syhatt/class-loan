<!DOCTYPE html>
<html>

<head>
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

        th,
        td {
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
    </style>
</head>

<body>
    @php
        $monthName = $month ? DateTime::createFromFormat('!m', $month)->format('F') : 'Semua Bulan';

        $facultyName = $faculty ?? 'Semua Fakultas';
    @endphp

    <h4 style="text-align:center;">
        Laporan Peminjaman Ruangan & Barang<br>
        {{ $monthName }} {{ $year }} <br>
        Fakultas: {{ $facultyName }}
    </h4>

    <hr>

    <p style="text-align:center; font-size:14px;">
        Periode: {{ $monthName }} {{ $year }}
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
            @foreach ($data as $loan)
                @php $items = $loan->bookingItems; @endphp

                @if ($items->count() > 0)
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $loan->user->name }}</td>
                            <td>{{ $loan->organization }}</td>
                            <td>{{ $loan->classmodel->name }}</td>
                            <td>{{ $loan->start_date }} s/d {{ $loan->end_date }}</td>
                            <td>{{ $item->item->name }}</td>
                            <td>{{ $item->qty }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
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

</body>

</html>
