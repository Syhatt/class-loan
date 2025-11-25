<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BookingClass;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Laporan Peminjaman Ruangan & Barang';

        // tahun default = tahun sekarang
        $year  = $request->year ?? Carbon::now()->year;

        // bulan boleh kosong (null / '' = semua bulan)
        $month = $request->month; // jangan kasih default bulan sekarang

        $facultyId = auth()->user()->faculty_id;

        // query dasar: semua data di tahun tsb
        $query = BookingClass::with(['user', 'classmodel', 'bookingItems.item'])
            ->where('faculty_id', $facultyId)
            ->whereYear('start_date', $year);

        // kalau bulan diisi (tidak kosong) → filter per bulan
        if (!empty($month)) {
            $query->whereMonth('start_date', $month);
        }

        $data = $query->get();

        return view('admin.reports.index', compact('pageTitle', 'data', 'month', 'year'));
    }

    public function exportPdf(Request $request)
    {
        // sama seperti index()
        $year  = $request->year ?? Carbon::now()->year;
        $month = $request->month; // bisa null / ''

        $facultyId = auth()->user()->faculty_id;

        $query = BookingClass::with(['user', 'classmodel', 'bookingItems.item'])
            ->where('faculty_id', $facultyId)
            ->whereYear('start_date', $year);

        if (!empty($month)) {
            $query->whereMonth('start_date', $month);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'data'  => $data,
            'month' => $month,
            'year'  => $year,
        ])->setPaper('A4', 'landscape');

        $monthName = $month ? str_pad($month, 2, '0', STR_PAD_LEFT) : 'all';

        return $pdf->stream("Laporan_Peminjaman_{$monthName}_{$year}.pdf");
    }
}
