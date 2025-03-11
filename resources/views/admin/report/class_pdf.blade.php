<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Ruangan</title>
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
    <h2>Laporan Peminjaman Ruangan - Bulan {{ date('F', mktime(0, 0, 0, $month, 1)) }}</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Nama Ruangan</th>
                <th>Tanggal Peminjaman</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roomBookings as $key => $booking)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->classmodel->name }}</td>
                    <td>{{ $booking->start_date }}</td>
                    <td>{{ ucfirst($booking->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
