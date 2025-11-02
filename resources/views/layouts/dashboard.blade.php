@extends('layouts.master')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Fakultas</h1>
    </div>

    {{-- === Statistik Card === --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Kelas Aktif</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kelasAktif }}</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Kelas Nonaktif</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kelasNonaktif }}</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Mahasiswa Terdaftar</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMahasiswa }}</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Peminjaman</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBooking }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- === Kalender === --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Kalender Peminjaman (Approved)</h5>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            {{-- === Grafik Tren Peminjaman === --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary">Tren Peminjaman Tahun {{ now()->year }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartPeminjaman"></canvas>
                </div>
            </div>
        </div>
        <div class="col">
            {{-- === Top Kelas Terbanyak Dipinjam === --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary">Top 5 Kelas yang Sering Dipinjam</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse ($topKelas as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $item->classmodel->name }}
                                <span class="badge badge-primary badge-pill">{{ $item->total }}x</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Belum ada data</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>




    {{-- === Peminjaman Terbaru === --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Peminjaman Terbaru</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Ruangan</th>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal Mulai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjamanTerbaru as $p)
                        <tr>
                            <td>{{ $p->classmodel->name }}</td>
                            <td>{{ $p->activity_name }}</td>
                            <td>{{ $p->start_date }}</td>
                            <td>
                                @if ($p->status == 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif ($p->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">Belum ada data peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // === Kalender ===
        document.addEventListener('DOMContentLoaded', function() {
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                locale: 'id',
                events: "{{ route('bookings.data') }}",
                eventClick: function(info) {
                    alert(info.event.title);
                }
            });
            calendar.render();
        });

        // === Grafik Tren Peminjaman ===
        const ctx = document.getElementById('chartPeminjaman');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: @json(array_values($bookingTren)),
                    borderWidth: 2,
                    borderColor: '#007bff',
                    fill: false,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
