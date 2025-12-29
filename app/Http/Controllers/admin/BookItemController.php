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
        $pageTitle = 'Data Peminjaman Barang';
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $booking = BookingItem::findOrFail($id);

    $request->validate([
        'status' => 'required|in:approved,rejected,returned',
        'nama_kabag' => 'nullable|string|max:255',
        'nip_kabag' => 'nullable|string|max:100',
        'ttd_kabag' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
    ]);

    // ===============================
    // APPROVED
    // ===============================
    if ($request->status === 'approved') {

        $request->validate([
            'nama_kabag' => 'required|string|max:255',
            'nip_kabag' => 'required|string|max:100',
            'ttd_kabag' => 'required|file|mimes:png,jpg,jpeg|max:2048',
        ]);

        $signaturePath = null;
        if ($request->hasFile('ttd_kabag')) {
            $signaturePath = $request->file('ttd_kabag')->store('ttd_kabag', 'public');
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
                'nodin_barang' => $pdfRelativePath,
            ]);

            return redirect()
                ->route('bookitem.show', $booking->id)
                ->with('success', 'Peminjaman barang berhasil disetujui dan surat telah dibuat.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal generate surat: ' . $e->getMessage());
        }
    }

    // ===============================
    // REJECTED
    // ===============================
    if ($request->status === 'rejected') {

        if (in_array($booking->status, ['pending', 'approved'])) {
            $item = $booking->item;
            $item->stock += $booking->qty;
            $item->save();
        }

        $booking->update(['status' => 'rejected']);

        return redirect()
            ->route('bookitem.show', $booking->id)
            ->with('success', 'Peminjaman barang berhasil ditolak.');
    }

    // ===============================
    // RETURNED
    // ===============================
    if ($request->status === 'returned') {

        if ($booking->status !== 'returned') {
            $item = $booking->item;
            $item->stock += $booking->qty;
            $item->save();
        }

        $booking->update(['status' => 'returned']);

        return redirect()
            ->route('bookitem.index')
            ->with('success', 'Barang berhasil dikembalikan dan stok diperbarui.');
    }

        // fallback
        return redirect()->route('bookitem.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = BookingItem::findOrFail($id);

        // Kembalikan stok barang
        $item = $booking->item;
        $item->stock += $booking->qty;
        $item->save();

        // Hapus file surat jika ada
        if ($booking->nodin_barang && file_exists(public_path($booking->nodin_barang))) {
            unlink(public_path($booking->nodin_barang));
        }

        $booking->delete();

        return redirect()
            ->route('bookitem.index')
            ->with('success', 'Data peminjaman barang berhasil dihapus.');
    }
}
