<?php

namespace App\Http\Controllers\Admin;

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
        $year = $request->year ?? Carbon::now()->year;

        // bulan opsional (null = semua bulan)
        $month = $request->month;

        $facultyId = auth()->user()->faculty_id;

        $query = BookingClass::with(['user', 'classmodel', 'bookingItems.item'])
            ->where('faculty_id', $facultyId)
            ->whereYear('start_date', $year);

        // filter bulan jika dipilih
        if (!empty($month)) {
            $query->whereMonth('start_date', $month);
        }

        $data = $query->get();

        return view('admin.reports.index', compact(
            'pageTitle',
            'data',
            'month',
            'year'
        ));
    }

    public function exportPdf(Request $request)
    {
        $year  = $request->year ?? Carbon::now()->year;
        $month = $request->month;

        $facultyId = auth()->user()->faculty_id;

        $query = BookingClass::with(['user', 'classmodel', 'bookingItems.item'])
            ->where('faculty_id', $facultyId)
            ->whereYear('start_date', $year);

        if (!empty($month)) {
            $query->whereMonth('start_date', $month);
        }

        $data = $query->get();

        // penamaan file
        $title = $month
            ? "Laporan Bulan " . Carbon::create()->month($month)->translatedFormat('F')
            : "Laporan Tahunan";

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'data'  => $data,
            'year'  => $year,
            'month' => $month,
            'title' => $title,
        ])->setPaper('A4', 'landscape');

        return $pdf->download(
            str_replace(' ', '_', $title) . "_{$year}.pdf"
        );
    }
}
