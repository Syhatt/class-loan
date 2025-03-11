<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BookingClass;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportClassController extends Controller
{
    public function index()
    {
        $pageTitle = 'Laporan Peminjaman Kelas';

        return view('admin.report.class', compact('pageTitle'));
    }

    public function generate(Request $request)
    {
        $facultyId = auth()->user()->faculty_id;
        $month = $request->month;

        $roomBookings = BookingClass::where('faculty_id', $facultyId)
            ->whereMonth('created_at', $month)
            ->get();

        // dd($roomBookings);

        return view('admin.report.class_result', compact('roomBookings', 'month'));
    }

    public function download(Request $request)
    {
        $facultyId = auth()->user()->faculty_id;
        $month = $request->month;

        $roomBookings = BookingClass::where('faculty_id', $facultyId)
            ->whereMonth('created_at', $month)
            ->get();

        $pdf = Pdf::loadView('admin.report.class_pdf', compact('roomBookings', 'month'));
        return $pdf->download("Laporan_Peminjaman_Ruangan_Bulan_{$month}.pdf");
    }
}
