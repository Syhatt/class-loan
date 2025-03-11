<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Laporan Peminjaman Barang - Bulan {{ date('F', mktime(0, 0, 0, $month, 1)) }}</h2>
    <table>
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
                    <td>{{ ucfirst($booking->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
