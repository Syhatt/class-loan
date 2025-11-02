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
        $facultyId = Auth::user()->faculty_id;

        // Statistik singkat
        $totalKelas = Classmodel::where('faculty_id', $facultyId)->count();
        $kelasAktif = Classmodel::where('faculty_id', $facultyId)->where('is_available', true)->count();
        $kelasNonaktif = $totalKelas - $kelasAktif;
        $totalMahasiswa = User::where('faculty_id', $facultyId)->count();
        $totalBooking = BookingClass::whereHas('classmodel', fn($q) => $q->where('faculty_id', $facultyId))->count();

        // Peminjaman terbaru
        $peminjamanTerbaru = BookingClass::whereHas('classmodel', fn($q) => $q->where('faculty_id', $facultyId))
            ->latest()
            ->take(5)
            ->get();

        // Grafik tren peminjaman per bulan (12 bulan terakhir)
        $bookingTren = BookingClass::select(
            DB::raw('MONTH(start_date) as bulan'),
            DB::raw('COUNT(*) as total')
        )
            ->whereHas('classmodel', fn($q) => $q->where('faculty_id', $facultyId))
            ->whereYear('start_date', Carbon::now()->year)
            ->where('status', 'approved')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Top 5 kelas paling sering dipinjam
        $topKelas = BookingClass::select('classmodel_id', DB::raw('COUNT(*) as total'))
            ->whereHas('classmodel', fn($q) => $q->where('faculty_id', $facultyId))
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
            'topKelas'
        ));
    }

    public function databooking()
    {
        $facultyId = Auth::user()->faculty_id;

        $bookings = BookingClass::whereHas('classmodel', fn($q) => $q->where('faculty_id', $facultyId))
            ->where('status', 'approved') // hanya yang approved
            ->get();

        $events = [];
        foreach ($bookings as $booking) {
            $events[] = [
                'title' => 'Ruangan: ' . $booking->classmodel->name,
                'start' => $booking->start_date,
                'end'   => Carbon::parse($booking->end_date)->addDay()->toDateString(),
                'color' => '#28a745', // hijau menandakan approved
            ];
        }

        return response()->json($events);
    }
}
