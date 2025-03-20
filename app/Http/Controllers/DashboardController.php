<?php

namespace App\Http\Controllers;

use App\Models\BookingClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('layouts.dashboard');
    }

    public function databooking()
    {
        // Ambil fakultas user login
        $facultyId = Auth::user()->faculty_id;

        // Ambil semua peminjaman ruangan berdasarkan fakultas user
        $bookings = BookingClass::whereHas('classmodel', function ($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId);
            })->get();

        // Format data untuk FullCalendar
        $events = [];
        foreach ($bookings as $booking) {
            $events[] = [
                'title' => 'Ruangan: ' . $booking->classmodel->name,
                'start' => $booking->start_date,
                'end'   => $booking->end_date,
                'color' => '#ff0000' // Warna merah untuk menandai ruangan yang sudah dipinjam
            ];
        }

        return response()->json($events);
    }
}
