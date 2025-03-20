@extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h2>Kalender Peminjaman Ruangan</h2>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id', // Bahasa Indonesia
                events: "{{ route('bookings.data') }}", // Ambil data dari backend
                eventClick: function(info) {
                    alert('Ruangan: ' + info.event.title);
                }
            });

            calendar.render();
        });
    </script>
@endpush
