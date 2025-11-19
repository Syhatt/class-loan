<?php

namespace App\Http\Controllers\admin;

use App\Helpers\NodinBarangGenerator;
use App\Http\Controllers\Controller;
use App\Models\BookingItem;
use Illuminate\Http\Request;

class BookItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Data Peminjaman Kelas';
        $booking = BookingItem::where('faculty_id', auth()->user()->faculty_id)->get();

        return view('admin.bookitem.index', compact('pageTitle', 'booking'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Detail Peminjaman Barang';

        $booking = BookingItem::with(['item', 'user', 'bookingClass'])->findOrFail($id);

        return view('admin.bookitem.show', compact('pageTitle', 'booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $booking = BookingItem::findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected,returned',
            'nama_kabag' => 'nullable|string|max:255',
            'nip_kabag' => 'nullable|string|max:100',
            'ttd_kabag' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($request->status === 'approved') {
            $request->validate([
                'nama_kabag' => 'required|string|max:255',
                'nip_kabag' => 'required|string|max:100',
                'ttd_kabag' => 'required|file|mimes:png,jpg,jpeg|max:2048',
            ]);

            // upload ttd
            $signaturePath = null;
            if ($request->hasFile('ttd_kabag')) {
                $signaturePath = $request->file('ttd_kabag')->store('signatures', 'public');
            }

            try {
                $pdfRelativePath = NodinBarangGenerator::generate(
                    $booking,
                    $request->nama_kabag,
                    $request->nip_kabag,
                    $signaturePath
                );

                $booking->update([
                    'status' => 'approved',
                    'nodin_barang' => $pdfRelativePath
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with(['error' => 'Gagal generate surat: ' . $e->getMessage()]);
            }
        } elseif ($request->status === 'rejected') {
            // kembalikan stok jika sebelumnya sudah dikurangi (bila diperlukan)
            if ($booking->status === 'pending' || $booking->status === 'approved') {
                $item = $booking->item;
                $item->stock += $booking->qty;
                $item->save();
            }
            $booking->update(['status' => 'rejected']);
        } elseif ($request->status === 'returned') {
            // ketika barang dikembalikan oleh user -> naikkan stok dan update status
            if ($booking->status !== 'returned') {
                $item = $booking->item;
                $item->stock += $booking->qty;
                $item->save();
            }
            $booking->update(['status' => 'returned']);
        }

        return redirect()->route('bookitem.index')->with(['success' => 'Data berhasil diperbarui!']);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $booking = BookingItem::findOrFail($id);

    //     $request->validate([
    //         'status' => 'required|in:approved,rejected',
    //         'nama_kabag' => 'nullable|string|max:255',
    //         'nip_kabag' => 'nullable|string|max:100',
    //         'ttd_kabag' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
    //     ]);

    //     if ($request->status === 'approved') {
    //         // Validasi jika approve harus ada data kabag
    //         $request->validate([
    //             'nama_kabag' => 'required|string|max:255',
    //             'nip_kabag' => 'required|string|max:100',
    //             'ttd_kabag' => 'required|file|mimes:png,jpg,jpeg|max:2048',
    //         ]);

    //         // Upload ttd kabag
    //         $signaturePath = null;
    //         if ($request->hasFile('ttd_kabag')) {
    //             $signaturePath = $request->file('ttd_kabag')->store('signatures', 'public');
    //         }

    //         // Generate surat pernyataan kesanggupan otomatis
    //         try {
    //             $pdfRelativePath = NodinBarangGenerator::generate(
    //                 $booking,
    //                 $request->nama_kabag,
    //                 $request->nip_kabag,
    //                 $signaturePath
    //             );

    //             // Update status dan simpan path nodin ke database
    //             $booking->update([
    //                 'status' => 'approved',
    //                 'nodin_barang' => $pdfRelativePath
    //             ]);
    //         } catch (\Exception $e) {
    //             return redirect()->back()->with(['error' => 'Gagal generate surat: ' . $e->getMessage()]);
    //         }
    //     } else {
    //         // Jika reject, hanya update status
    //         $booking->update(['status' => 'rejected']);
    //     }
    //     // dd($request->all());

    //     return redirect()->route('bookitem.index')->with(['success' => 'Data berhasil diperbarui!']);
    // }

    /**
     * Remove the specified resource from storage (optional, jika diperlukan).
     */
    public function destroy(string $id)
    {
        $booking = BookingItem::findOrFail($id);

        // Kembalikan stok barang jika peminjaman dihapus
        $item = $booking->item;
        $item->stock += $booking->qty;
        $item->save();

        // Hapus file nodin jika ada
        if ($booking->nodin_barang && file_exists(public_path($booking->nodin_barang))) {
            unlink(public_path($booking->nodin_barang));
        }

        $booking->delete();

        return redirect()->route('bookitem.index')->with(['success' => 'Data berhasil dihapus!']);
    }
}
