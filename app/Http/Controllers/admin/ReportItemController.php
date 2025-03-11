<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BookingItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportItemController extends Controller
{
    public function index()
    {
        $pageTitle = 'Laporan Peminjaman Barang';

        return view('admin.report.item', compact('pageTitle'));
    }

    public function generate(Request $request)
    {
        $facultyId = auth()->user()->faculty_id;
        $month = $request->month;

        $itemBookings = BookingItem::whereHas('bookingClass', function ($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId);
            })
            ->whereMonth('created_at', $month)
            ->get();

        return view('admin.report.item_result', compact('itemBookings', 'month'));
    }

    public function download(Request $request)
    {
        $facultyId = auth()->user()->faculty_id;
        $month = $request->month;

        $itemBookings = BookingItem::whereHas('bookingClass', function ($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId);
            })
            ->whereMonth('created_at', $month)
            ->get();

        $pdf = Pdf::loadView('admin.report.item_pdf', compact('itemBookings', 'month'));
        return $pdf->download("Laporan_Peminjaman_Barang_Bulan_{$month}.pdf");
    }
}
