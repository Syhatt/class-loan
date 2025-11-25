<?php

namespace App\Http\Controllers;

use App\Models\BookingClass;
use App\Models\BookingItem;
use App\Models\Faculty;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Peminjaman Barang';

        if (auth()->user()->role === 'superadmin') {
            $item = Item::where('stock', '>', 0)
                ->with('faculty')
                ->get();

            $faculties = Faculty::all();

            return view('bookingitem.index', compact('pageTitle', 'item', 'faculties'));
        }

        $item = Item::where('faculty_id', auth()->user()->faculty_id)
            ->where('stock', '>', 0)
            ->get();

        return view('bookingitem.index', compact('pageTitle', 'item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $pageTitle = 'Form Peminjaman Barang';
        $items = Item::findOrFail($id);

        // ambil semua peminjaman ruangan yg sudah approved milik user ini
        $approvedRooms = BookingClass::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->get();

        return view('bookingitem.create', compact('pageTitle', 'items', 'approvedRooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // VALIDASI AWAL (tanpa hari_pengembalian, hari dihitung otomatis dari tanggal)
        $request->validate(
            [
                'item_id'              => 'required|exists:items,id',
                'qty'                  => 'required|integer|min:1',
                'booking_class_id'     => 'required|exists:booking_classes,id',
                'tanggal_pengembalian' => 'required|date',
                'jam_pengembalian'     => 'required|date_format:H:i',
            ],
            [
                'item_id.required'          => 'Barang wajib dipilih.',
                'item_id.exists'            => 'Data barang tidak valid.',
                'qty.required'              => 'Jumlah barang wajib diisi.',
                'qty.integer'               => 'Jumlah harus berupa angka.',
                'qty.min'                   => 'Jumlah minimal 1.',
                'booking_class_id.required' => 'Ruangan / acara wajib dipilih.',
                'booking_class_id.exists'   => 'Data ruangan / acara tidak valid.',
                'tanggal_pengembalian.required' => 'Tanggal pengembalian wajib diisi.',
                'tanggal_pengembalian.date'     => 'Format tanggal pengembalian tidak valid.',
                'jam_pengembalian.required' => 'Jam pengembalian wajib diisi.',
                'jam_pengembalian.date_format' => 'Format jam pengembalian tidak valid (HH:ii).',
            ]
        );

        $user = auth()->user();

        // CEK ITEM + STOK (awal, sebelum transaksi, utk error yg rapi di qty)
        $item = Item::find($request->item_id);
        if (!$item) {
            return back()
                ->withErrors(['item_id' => 'Barang tidak ditemukan.'])
                ->withInput();
        }

        if ($request->qty > $item->stock) {
            return back()
                ->withErrors([
                    'qty' => 'Jumlah yang diminta melebihi stok yang tersedia (stok: ' . $item->stock . ').',
                ])
                ->withInput();
        }

        // PETA HARI DALAM BAHASA INDONESIA (ISO: 1=Senin ... 7=Minggu)
        $hariArray = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
        ];

        // CEK HANYA HARI KERJA (SENIN–JUMAT)
        $tanggal = Carbon::parse($request->tanggal_pengembalian);
        $dayIndex = $tanggal->dayOfWeekIso; // 1=Mon ... 7=Sun

        if ($dayIndex > 5) {
            return back()
                ->withErrors([
                    'tanggal_pengembalian' => 'Pengembalian hanya dapat dilakukan pada hari kerja (Senin sampai Jumat).',
                ])
                ->withInput();
        }

        // HITUNG HARI PENGEMBALIAN OTOMATIS
        $hari_pengembalian = $hariArray[$dayIndex];

        // CEK JAM 07:00–15:00
        $jam = Carbon::createFromFormat('H:i', $request->jam_pengembalian);
        $start = Carbon::createFromTime(7, 0, 0);   // 07:00
        $end   = Carbon::createFromTime(15, 0, 0);  // 15:00

        if ($jam->lt($start) || $jam->gt($end)) {
            return back()
                ->withErrors([
                    'jam_pengembalian' => 'Jam pengembalian hanya diperbolehkan antara pukul 07.00 sampai 15.00.',
                ])
                ->withInput();
        }

        // SIMPAN DENGAN TRANSAKSI (untuk jaga-jaga race condition stok)
        $result = DB::transaction(function () use ($request, $user, $hari_pengembalian) {

            // pastikan ruangan yang dipilih milik user dan sudah approved
            $approvedRoom = BookingClass::where('id', $request->booking_class_id)
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->lockForUpdate()
                ->first();

            if (!$approvedRoom) {
                return [
                    'status'  => false,
                    'field'   => null,
                    'message' => 'Anda harus memiliki peminjaman ruangan yang telah disetujui.',
                ];
            }

            // lock item supaya stok aman
            $item = Item::where('id', $request->item_id)->lockForUpdate()->first();
            if (!$item) {
                return [
                    'status'  => false,
                    'field'   => 'item_id',
                    'message' => 'Barang tidak ditemukan.',
                ];
            }

            // CEK STOK LAGI DI DALAM TRANSAKSI
            if ($item->stock < $request->qty) {
                return [
                    'status'  => false,
                    'field'   => 'qty',
                    'message' => 'Stok barang tidak mencukupi. Stok tersisa: ' . $item->stock . '.',
                ];
            }

            // ❌ TIDAK ADA LAGI LOGIKA CEK BOOKINGITEM EXISTING / PRIORITAS
            // Selama stok cukup, boleh dipinjam oleh peminjam lain.

            // kurangi stok
            $item->stock -= $request->qty;
            $item->save();

            // simpan booking item
            BookingItem::create([
                'faculty_id'           => $request->faculty_id ?? $item->faculty_id,
                'user_id'              => $user->id,
                'booking_classes_id'   => $request->booking_class_id,
                'item_id'              => $request->item_id,
                'qty'                  => $request->qty,
                'hari_pengembalian'    => $hari_pengembalian,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'jam_pengembalian'     => $request->jam_pengembalian,
                'status'               => 'pending',
            ]);

            return [
                'status'  => true,
                'field'   => null,
                'message' => 'Peminjaman barang berhasil disimpan!',
            ];
        }, 5);

        // HANDLE GAGAL DARI TRANSAKSI
        if (!$result['status']) {
            if (!empty($result['field'])) {
                return redirect()
                    ->back()
                    ->withErrors([$result['field'] => $result['message']])
                    ->withInput();
            }

            return redirect()
                ->back()
                ->withInput()
                ->with(['error' => $result['message']]);
        }

        return redirect()
            ->route('bookingitem.index')
            ->with(['success' => $result['message']]);
    }
}
