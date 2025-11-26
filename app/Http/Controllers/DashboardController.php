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
        $isSuperAdmin = $user->role === 'superadmin';

        // Jika superadmin -> semua fakultas, jika bukan -> filter fakultas user
        $facultyFilter = $isSuperAdmin ? null : $user->faculty_id;

        // Total kelas
        $totalKelas = Classmodel::when(!$isSuperAdmin, function ($q) use ($facultyFilter) {
            $q->where('faculty_id', $facultyFilter);
        })->count();

        $kelasAktif = Classmodel::when(!$isSuperAdmin, function ($q) use ($facultyFilter) {
            $q->where('faculty_id', $facultyFilter);
        })->where('is_available', true)->count();

        $kelasNonaktif = $totalKelas - $kelasAktif;

        // Total mahasiswa
        $totalMahasiswa = User::when(!$isSuperAdmin, function ($q) use ($facultyFilter) {
            $q->where('faculty_id', $facultyFilter);
        })->count();

        // Total booking
        $totalBooking = BookingClass::when(!$isSuperAdmin, function ($q) use ($facultyFilter) {
            $q->whereHas('classmodel', fn($q2) => $q2->where('faculty_id', $facultyFilter));
        })->count();

        // Peminjaman terbaru
        $peminjamanTerbaru = BookingClass::when(!$isSuperAdmin, function ($q) use ($facultyFilter) {
            $q->whereHas('classmodel', fn($q2) => $q2->where('faculty_id', $facultyFilter));
        })
            ->latest()
            ->take(5)
            ->get();

        // Grafik tren
        $bookingTren = BookingClass::select(
            DB::raw('MONTH(start_date) as bulan'),
            DB::raw('COUNT(*) as total')
        )
            ->when(!$isSuperAdmin, function ($q) use ($facultyFilter) {
                $q->whereHas('classmodel', fn($q2) => $q2->where('faculty_id', $facultyFilter));
            })
            ->whereYear('start_date', now()->year)
            ->where('status', 'approved')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Top kelas
        $topKelas = BookingClass::select('classmodel_id', DB::raw('COUNT(*) as total'))
            ->when(!$isSuperAdmin, function ($q) use ($facultyFilter) {
                $q->whereHas('classmodel', fn($q2) => $q2->where('faculty_id', $facultyFilter));
            })
            ->where('status', 'approved')
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
            'topKelas',
            'isSuperAdmin'
        ));
    }

    public function databooking()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->role === 'superadmin';

        $bookings = BookingClass::when(!$isSuperAdmin, function ($q) use ($user) {
            $q->whereHas('classmodel', fn($q2) => $q2->where('faculty_id', $user->faculty_id));
        })
            ->where('status', 'approved')
            ->get();

        $events = $bookings->map(function ($booking) {
            return [
                'title' => 'Ruangan: ' . $booking->classmodel->name,
                'start' => $booking->start_date,
                'end'   => Carbon::parse($booking->end_date)->addDay()->toDateString(),
                'color' => '#28a745',
            ];
        });

        return response()->json($events);
    }
}
