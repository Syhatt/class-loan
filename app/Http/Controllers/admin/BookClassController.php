<?php

namespace App\Http\Controllers\admin;

use App\Helpers\NodinGenerator;
use App\Http\Controllers\Controller;
use App\Models\BookingClass;
use App\Models\Nodin;
use Illuminate\Http\Request;

class BookClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Data Peminjaman Kelas';
        $booking = BookingClass::where('faculty_id', auth()->user()->faculty_id)->get();

        return view('admin.bookclass.index', compact('pageTitle', 'booking'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Detail Data Peminjaman Kelas';
        $booking = BookingClass::findOrFail($id);

        return view('admin.bookclass.show', compact('pageTitle', 'booking'));
    }

    public function update(Request $request, string $id)
    {
        $booking = BookingClass::findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'no_surat' => 'nullable|string|max:255',
            'nama_wadek' => 'nullable|string|max:255',
            'ttd_admin' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($request->status === 'approved') {
            // Validasi wajib untuk approval
            $request->validate([
                'no_surat' => 'required|string|max:255',
                'nama_wadek' => 'required|string|max:255',
                'ttd_admin' => 'required|file|mimes:png,jpg,jpeg|max:2048',
            ]);

            // Upload TTD admin
            $signaturePath = null;
            if ($request->hasFile('ttd_admin')) {
                $signaturePath = $request->file('ttd_admin')->store('signatures', 'public');
            }

            try {
                // Generate Nodin otomatis ke PDF
                $pdfPath = NodinGenerator::generate(
                    $booking,
                    $request->no_surat,
                    $request->nama_wadek,
                    $signaturePath
                );

                // Simpan ke DB
                Nodin::updateOrCreate(
                    ['booking_class_id' => $booking->id],
                    ['file_path' => $pdfPath]
                );

                // Update status
                $booking->update(['status' => 'approved']);

                return redirect()->route('bookclass.index')
                    ->with(['success' => 'Peminjaman berhasil disetujui dan Nota Dinas telah digenerate!']);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->with(['error' => 'Gagal generate Nota Dinas: ' . $e->getMessage()]);
            }
        } else {
            // Reject
            $booking->update(['status' => 'rejected']);

            return redirect()->route('bookclass.index')
                ->with(['success' => 'Peminjaman berhasil ditolak.']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $booking = BookingClass::findOrFail($id);

    //     $request->validate([
    //         'status' => 'required|in:approved,rejected',
    //         'no_surat' => 'nullable|string',
    //         'nama_wadek' => 'nullable|string',
    //         'ttd_admin' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
    //     ]);

    //     if ($request->status === 'approved') {
    //         // Upload TTD admin
    //         $signaturePath = null;
    //         if ($request->hasFile('ttd_admin')) {
    //             $signaturePath = $request->file('ttd_admin')->store('signatures', 'public');
    //         }

    //         // Generate Nodin otomatis ke PDF
    //         $pdfPath = NodinGenerator::generate($booking, $request->no_surat, $request->nama_wadek, $signaturePath);

    //         // Simpan ke DB
    //         Nodin::updateOrCreate(
    //             ['booking_class_id' => $booking->id],
    //             ['file_path' => $pdfPath]
    //         );

    //         // Update status
    //         $booking->update(['status' => 'approved']);
    //     } else {
    //         $booking->update(['status' => 'rejected']);
    //     }

    //     return redirect()->route('bookclass.index')->with(['success' => 'Data berhasil diperbarui!']);
    // }
}
