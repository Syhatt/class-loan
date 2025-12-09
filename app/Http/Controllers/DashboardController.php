<?php

namespace App\Http\Controllers;

use App\Models\BookingClass;
use App\Models\Classmodel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->role === 'superadmin'; // sesuaikan dengan nama kolom/role-mu

        // =========================
        // STATISTIK KELAS & USER
        // =========================
        if ($isSuperAdmin) {
            // SUPER ADMIN → SEMUA FAKULTAS
            $totalKelas     = Classmodel::count();
            $kelasAktif     = Classmodel::where('is_available', true)->count();
            $kelasNonaktif  = $totalKelas - $kelasAktif;
            $totalMahasiswa = User::count();
            $totalBooking   = BookingClass::count();
        } else {
            // USER BIASA → HANYA FAKULTASNYA
            $facultyId      = $user->faculty_id;

            $totalKelas     = Classmodel::where('faculty_id', $facultyId)->count();
            $kelasAktif     = Classmodel::where('faculty_id', $facultyId)
                                ->where('is_available', true)
                                ->count();
            $kelasNonaktif  = $totalKelas - $kelasAktif;

            $totalMahasiswa = User::where('faculty_id', $facultyId)->count();

            $totalBooking   = BookingClass::whereHas('classmodel', function ($q) use ($facultyId) {
                                    $q->where('faculty_id', $facultyId);
                                })
                                ->count();
        }

        // =========================
        // PEMINJAMAN TERBARU
        // =========================
        if ($isSuperAdmin) {
            $peminjamanTerbaru = BookingClass::with('classmodel')
                ->latest()
                ->take(5)
                ->get();
        } else {
            $facultyId = $user->faculty_id;

            $peminjamanTerbaru = BookingClass::with('classmodel')
                ->whereHas('classmodel', function ($q) use ($facultyId) {
                    $q->where('faculty_id', $facultyId);
                })
                ->latest()
                ->take(5)
                ->get();
        }

        // =========================
        // GRAFIK TREN PEMINJAMAN
        // =========================
        $bookingTrenQuery = BookingClass::select(
                DB::raw('MONTH(start_date) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('start_date', Carbon::now()->year)
            ->where('status', 'approved');

        if (!$isSuperAdmin) {
            $facultyId = $user->faculty_id;

            $bookingTrenQuery->whereHas('classmodel', function ($q) use ($facultyId) {
                $q->where('faculty_id', $facultyId);
            });
        }

        $bookingTrenRaw = $bookingTrenQuery
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Lengkapi jadi 12 bulan (Jan–Des) supaya grafik rapi
        $bookingTren = [];
        for ($i = 1; $i <= 12; $i++) {
            $bookingTren[$i] = $bookingTrenRaw[$i] ?? 0;
        }

        // =========================
        // TOP 5 KELAS PALING SERING DIPINJAM
        // =========================
        $topKelasQuery = BookingClass::select('classmodel_id', DB::raw('COUNT(*) as total'))
            ->where('status', 'approved');

        if (!$isSuperAdmin) {
            $facultyId = $user->faculty_id;

            $topKelasQuery->whereHas('classmodel', function ($q) use ($facultyId) {
                $q->where('faculty_id', $facultyId);
            });
        }

        $topKelas = $topKelasQuery
            ->groupBy('classmodel_id')
            ->with('classmodel')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('layouts.dashboard', compact(
            'totalKelas',
            'kelasAktif',
            'kelasNonaktif',
            'totalMahasiswa',
            'totalBooking',
            'peminjamanTerbaru',
            'bookingTren',
            'topKelas'
        ));
    }

    public function databooking()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->role === 'superadmin';

        // =========================
        // DATA UNTUK KALENDER
        // =========================
        $bookingsQuery = BookingClass::where('status', 'approved');

        if (!$isSuperAdmin) {
            $facultyId = $user->faculty_id;

            $bookingsQuery->whereHas('classmodel', function ($q) use ($facultyId) {
                $q->where('faculty_id', $facultyId);
            });
        }

        $bookings = $bookingsQuery->with('classmodel')->get();

        $events = [];
        foreach ($bookings as $booking) {
            $events[] = [
                'title' => 'Ruangan: ' . optional($booking->classmodel)->name,
                'start' => $booking->start_date,
                // +1 hari agar FullCalendar tidak eksklusif di end-date
                'end'   => Carbon::parse($booking->end_date)->addDay()->toDateString(),
                'color' => '#28a745', // hijau menandakan approved
            ];
        }

        return response()->json($events);
    }
}
