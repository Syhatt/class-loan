<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BookingClass;
use App\Models\Faculty;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Laporan Peminjaman Ruangan & Barang';

        $year  = $request->year ?? Carbon::now()->year;
        $month = $request->month;
        $facultyFilter = $request->faculty_id;

        $query = BookingClass::with(['user', 'classmodel', 'bookingItems.item']);

        // SUPERADMIN BISA FILTER FAKULTAS
        if (auth()->user()->role === 'superadmin') {
            if (!empty($facultyFilter)) {
                $query->where('faculty_id', $facultyFilter);
            }
        } else {
            // ADMIN BIASA > LOCK FAKULTAS DI USER
            $query->where('faculty_id', auth()->user()->faculty_id);
        }

        $query->whereYear('start_date', $year);

        if (!empty($month)) {
            $query->whereMonth('start_date', $month);
        }

        $data = $query->get();

        $faculties = Faculty::all(); // diperlukan untuk dropdown

        return view('admin.reports.index', compact('pageTitle', 'data', 'month', 'year', 'faculties', 'facultyFilter'));
    }

    public function exportPdf(Request $request)
    {
        $year  = $request->year ?? Carbon::now()->year;
        $month = $request->month;
        $facultyFilter = $request->faculty_id;

        $query = BookingClass::with(['user', 'classmodel', 'bookingItems.item']);

        if (auth()->user()->role === 'superadmin') {
            if (!empty($facultyFilter)) {
                $query->where('faculty_id', $facultyFilter);
            }
        } else {
            $query->where('faculty_id', auth()->user()->faculty_id);
        }

        $query->whereYear('start_date', $year);

        if (!empty($month)) {
            $query->whereMonth('start_date', $month);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'data'  => $data,
            'month' => $month,
            'year'  => $year,
            'faculty' => $facultyFilter ? Faculty::find($facultyFilter)->name : 'Semua Fakultas'
        ])->setPaper('A4', 'landscape');

        $fileName = "Laporan_{$year}_";
        $fileName .= $month ? str_pad($month, 2, '0', STR_PAD_LEFT) : "ALL";
        $fileName .= $facultyFilter ? "_FACULTY_{$facultyFilter}" : "_ALL";

        return $pdf->stream("$fileName.pdf");
    }
}
