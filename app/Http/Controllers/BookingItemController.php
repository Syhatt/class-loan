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

    public function create($id)
    {
        $pageTitle = 'Form Peminjaman Barang';
        $items = Item::findOrFail($id);

        $today = Carbon::today();

        $approvedRooms = BookingClass::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->whereDate('end_date', '>=', $today)
            ->with('classmodel')
            ->orderBy('start_date')
            ->get();

        return view('bookingitem.create', compact('pageTitle', 'items', 'approvedRooms'));
    }

    public function store(Request $request)
    {
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

        // PETA HARI (ISO)
        $hariArray = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
        ];

        // CEK HARI KERJA
        $tanggal = Carbon::parse($request->tanggal_pengembalian);
        $dayIndex = $tanggal->dayOfWeekIso;

        if ($dayIndex > 5) {
            return back()
                ->withErrors(['tanggal_pengembalian' => 'Pengembalian hanya dapat dilakukan pada hari kerja (Senin sampai Jumat).'])
                ->withInput();
        }

        $hari_pengembalian = $hariArray[$dayIndex];

        // CEK JAM 07:00–15:00
        $jam = Carbon::createFromFormat('H:i', $request->jam_pengembalian);
        $start = Carbon::createFromTime(7, 0, 0);
        $end   = Carbon::createFromTime(15, 0, 0);

        if ($jam->lt($start) || $jam->gt($end)) {
            return back()
                ->withErrors(['jam_pengembalian' => 'Jam pengembalian hanya diperbolehkan antara pukul 07.00 sampai 15.00.'])
                ->withInput();
        }

        // datetime pengembalian
        $returnDT = Carbon::parse($request->tanggal_pengembalian . ' ' . $request->jam_pengembalian);

        $result = DB::transaction(function () use ($request, $user, $hari_pengembalian, $returnDT) {

            $today = Carbon::today();

            // pastikan ruangan milik user, approved, belum lewat
            $approvedRoom = BookingClass::where('id', $request->booking_class_id)
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereDate('end_date', '>=', $today)
                ->lockForUpdate()
                ->first();

            if (!$approvedRoom) {
                return [
                    'status'  => false,
                    'field'   => null,
                    'message' => 'Anda harus memiliki peminjaman ruangan yang telah disetujui dan belum melewati tanggal berakhir.',
                ];
            }

            // ✅ Validasi pengembalian: >= start ruangan, <= end+3 hari
            $roomStart = Carbon::parse($approvedRoom->start_date . ' ' . ($approvedRoom->start_time ?? '00:00'));
            $roomEnd   = Carbon::parse($approvedRoom->end_date   . ' ' . ($approvedRoom->end_time   ?? '23:59'));

            if ($returnDT->lt($roomStart)) {
                return [
                    'status'  => false,
                    'field'   => 'tanggal_pengembalian',
                    'message' => 'Tanggal/jam pengembalian tidak boleh sebelum waktu mulai peminjaman ruangan (' .
                                 $roomStart->format('d-m-Y H:i') . ').',
                ];
            }

            $maxReturn = $roomEnd->copy()->addDays(3);
            if ($returnDT->gt($maxReturn)) {
                return [
                    'status'  => false,
                    'field'   => 'tanggal_pengembalian',
                    'message' => 'Maksimal pengembalian adalah 3 hari setelah peminjaman ruangan selesai (' .
                                 $maxReturn->format('d-m-Y H:i') . ').',
                ];
            }

            // lock item
            $item = Item::where('id', $request->item_id)->lockForUpdate()->first();
            if (!$item) {
                return [
                    'status'  => false,
                    'field'   => 'item_id',
                    'message' => 'Barang tidak ditemukan.',
                ];
            }

            // PRIORITAS ROLE (GREEDY untuk PENDING)
            $rolePriority = [
                'superadmin'     => 1,
                'admin_fakultas' => 2,
                'dosen'          => 3,
                'user'           => 4,
            ];
            $currentPriority = $rolePriority[$user->role] ?? 99;

            // ✅ kalau stok kurang, coba "ambil" dari booking pending prioritas lebih rendah (greedy)
            if ($item->stock < $request->qty) {
                $pendingBookings = BookingItem::where('item_id', $item->id)
                    ->where('status', 'pending')
                    ->with('user')
                    ->lockForUpdate()
                    ->get()
                    ->sortBy(function ($b) use ($rolePriority) {
                        return $rolePriority[$b->user->role] ?? 99;
                    });

                foreach ($pendingBookings as $pending) {
                    $pendingPriority = $rolePriority[$pending->user->role] ?? 99;

                    // hanya override yang prioritasnya lebih rendah
                    if ($pendingPriority > $currentPriority) {
                        $pending->status = 'rejected';
                        $pending->save();

                        // kembalikan stok dari booking yang dibatalkan
                        $item->stock += $pending->qty;

                        if ($item->stock >= $request->qty) {
                            break; // greedy stop
                        }
                    }
                }
            }

            // ✅ setelah greedy, baru final check stok
            if ($item->stock < $request->qty) {
                return [
                    'status'  => false,
                    'field'   => 'qty',
                    'message' => 'Stok barang tidak mencukupi. Stok tersisa: ' . $item->stock . '.',
                ];
            }

            // kurangi stok
            $item->stock -= $request->qty;
            $item->save();

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
