<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BookingClass;
// use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Laporan Peminjaman Ruangan & Barang';

        $month = $request->month ?? Carbon::now()->month;
        $year  = $request->year ?? Carbon::now()->year;

        $facultyId = auth()->user()->faculty_id;

        $data = BookingClass::with(['user', 'classmodel', 'bookingItems.item'])
            ->where('faculty_id', $facultyId)
            ->whereMonth('start_date', $month)
            ->whereYear('start_date', $year)
            ->get();

        return view('admin.reports.index', compact('pageTitle', 'data', 'month', 'year'));
    }

    public function exportPdf(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year ?? now()->year;
        $facultyId = auth()->user()->faculty_id;

        $data = BookingClass::with(['user', 'classmodel', 'bookingItems.item'])
            ->where('faculty_id', $facultyId)
            ->whereMonth('start_date', $month)
            ->whereYear('start_date', $year)
            ->get();

        $pdf = PDF::loadView('admin.reports.pdf', [
            'data' => $data,
            'month' => $month,
            'year' => $year
        ])->setPaper('A4', 'landscape');

        return $pdf->stream("Laporan_Peminjaman_{$month}_{$year}.pdf");
    }
}
