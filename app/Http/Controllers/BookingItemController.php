<?php

namespace App\Http\Controllers;

use App\Models\BookingClass;
use App\Models\BookingItem;
use App\Models\Faculty;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\alert;

class BookingItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Peminjaman Barang';

        if (auth()->user()->role === 'superadmin') {
            $item = Item::where('stock', '>', 0)->with('faculty')->get();
            $faculties = Faculty::all();
            return view('bookingitem.index', compact('pageTitle', 'item', 'faculties'));
        }

        $item = Item::where('faculty_id', auth()->user()->faculty_id)->where('stock', '>', 0)->get();

        return view('bookingitem.index', compact('pageTitle', 'item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $pageTitle = 'Form Peminjaman Barang';
        $items = Item::findOrFail($id);

        // ambil semua peminjaman ruangan yg sudah approved milik mahasiswa ini
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
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'qty' => 'required|integer|min:1',
            'booking_class_id' => 'required|exists:booking_classes,id',
            'hari_pengembalian' => 'required|string|max:100',
            'tanggal_pengembalian' => 'required|date',
            'jam_pengembalian' => 'required|date_format:H:i',
        ]);

        $user = auth()->user();

        // role priority
        $rolePriority = [
            'superadmin' => 1,
            'admin_fakultas' => 2,
            'dosen' => 3,
            'user' => 4,
        ];
        $currentPriority = $rolePriority[$user->role] ?? 99;

        $result = DB::transaction(function () use ($request, $user, $rolePriority, $currentPriority) {

            // Pastikan ruangan yang dipilih benar-benar milik user & approved
            $approvedRoom = BookingClass::where('id', $request->booking_class_id)
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->lockForUpdate()
                ->first();

            if (!$approvedRoom) {
                return [
                    'status' => false,
                    'message' => 'Anda harus memiliki peminjaman ruangan yang telah disetujui.'
                ];
            }

            // Lock item agar stok tidak race
            $item = Item::where('id', $request->item_id)->lockForUpdate()->first();
            if (!$item) {
                return ['status' => false, 'message' => 'Barang tidak ditemukan.'];
            }

            if ($item->stock < $request->qty) {
                return [
                    'status' => false,
                    'message' => 'Stok barang tidak mencukupi.'
                ];
            }

            // Ambil semua peminjaman barang yg conflict (booking item pada booking_class yang sama)
            $existing = BookingItem::where('item_id', $request->item_id)
                ->where('booking_classes_id', $request->booking_class_id)
                ->whereIn('status', ['pending', 'approved'])
                ->lockForUpdate()
                ->get();

            // cek priority
            if ($existing->isNotEmpty()) {
                foreach ($existing as $ex) {
                    $exPriority = $rolePriority[$ex->user->role] ?? 99;

                    // Kalau existing memiliki prioritas lebih tinggi atau sama → tolak
                    if ($exPriority <= $currentPriority) {
                        return [
                            'status' => false,
                            'message' => 'Gagal — barang ini sedang dipinjam oleh user dengan prioritas sama/lebih tinggi.'
                        ];
                    }
                }

                // Override existing pending yg prioritasnya lebih rendah
                $lowPending = $existing->where('status', 'pending');
                foreach ($lowPending as $p) {
                    $p->status = 'rejected';
                    $p->save();
                }
            }

            // Kurangi stok setelah semua aman
            $item->stock -= $request->qty;
            $item->save();

            // Simpan booking item baru
            BookingItem::create([
                'faculty_id' => $request->faculty_id,
                'user_id' => $user->id,
                'booking_classes_id' => $request->booking_class_id,
                'item_id' => $request->item_id,
                'qty' => $request->qty,
                'hari_pengembalian' => $request->hari_pengembalian,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'jam_pengembalian' => $request->jam_pengembalian,
                'status' => 'pending',
            ]);

            return ['status' => true, 'message' => 'Peminjaman barang berhasil disimpan!'];
        }, 5);

        if (!$result['status']) {
            return redirect()->back()->withInput()->with(['error' => $result['message']]);
        }

        return redirect()->route('bookingitem.index')->with(['success' => $result['message']]);
    }
}
