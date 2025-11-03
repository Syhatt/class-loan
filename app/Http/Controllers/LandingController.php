<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Data untuk statistik
        $stats = [
            'ruang_kelas' => 100,
            'barang_tersedia' => 500,
            'pengguna_aktif' => 50000,
            'kepuasan' => 99.9
        ];

        // Data untuk fitur
        $features = [
            [
                'icon' => 'shield',
                'title' => 'Akses Berbasis Fakultas',
                'description' => 'Setiap fakultas memiliki akses terbatas ke ruang dan barang miliknya sendiri, memastikan tidak ada campur aduk antar fakultas.',
                'color' => 'blue'
            ],
            [
                'icon' => 'clock',
                'title' => 'Approval Real-time',
                'description' => 'Proses approval yang cepat dan transparan. Mahasiswa dapat melihat status peminjaman secara real-time.',
                'color' => 'emerald'
            ],
            [
                'icon' => 'bar-chart-3',
                'title' => 'Dashboard Analytics',
                'description' => 'Monitoring dan laporan lengkap untuk admin. Track penggunaan ruang dan barang dengan visualisasi data yang menarik.',
                'color' => 'purple'
            ],
            [
                'icon' => 'zap',
                'title' => 'Peminjaman Instan',
                'description' => 'Proses peminjaman yang mudah dan cepat. Booking ruang dan barang dalam beberapa klik saja.',
                'color' => 'orange'
            ],
            [
                'icon' => 'file-text',
                'title' => 'Generate Laporan Otomatis',
                'description' => 'Buat laporan peminjaman bulanan atau tahunan dengan mudah. Export ke PDF atau Excel untuk kebutuhan administrasi.',
                'color' => 'red'
            ],
            [
                'icon' => 'lock',
                'title' => 'Keamanan Terjamin',
                'description' => 'Sistem dengan keamanan berlapis dan enkripsi data untuk melindungi informasi peminjaman dan data pengguna.',
                'color' => 'indigo'
            ]
        ];

        // Data untuk role-based access
        $roles = [
            'mahasiswa' => [
                'title' => 'Mahasiswa',
                'description' => 'Akses penuh untuk kebutuhan peminjaman kelas dan barang',
                'icon' => 'users',
                'color' => 'blue',
                'features' => [
                    'Dashboard Informasi - Lihat statistik peminjaman dan info sistem',
                    'Peminjaman Kelas - Booking ruang kelas sesuai fakultas',
                    'History Peminjaman - Track semua peminjaman kelas',
                    'Peminjaman Barang - Pinjam barang untuk kegiatan',
                    'History Barang - Riwayat peminjaman barang'
                ]
            ],
            'dosen' => [
                'title' => 'Dosen',
                'description' => 'Akses khusus untuk kebutuhan akademik dan penelitian',
                'icon' => 'user-check',
                'color' => 'purple',
                'features' => [
                    'Dashboard Informasi - Lihat statistik peminjaman dan info sistem',
                    'Peminjaman Kelas - Booking ruang untuk perkuliahan dan seminar',
                    'History Peminjaman - Track semua peminjaman kelas',
                    'Peminjaman Barang - Pinjam barang untuk penelitian',
                    'History Barang - Riwayat peminjaman barang'
                ]
            ],
            'admin' => [
                'title' => 'Admin Fakultas',
                'description' => 'Kontrol penuh untuk manajemen fakultas',
                'icon' => 'settings',
                'color' => 'emerald',
                'features' => [
                    'Kelola Data Kelas - Tambah, edit, hapus ruang kelas fakultas',
                    'Kelola Data Barang - Manajemen inventaris fakultas',
                    'Approval Peminjaman Kelas - Setujui/tolak peminjaman ruang',
                    'Approval Peminjaman Barang - Setujui/tolak peminjaman barang',
                    'Generate Laporan - Buat laporan bulanan/tahunan'
                ]
            ],
            'superadmin' => [
                'title' => 'Super Admin',
                'description' => 'Akses tertinggi untuk manajemen sistem global',
                'icon' => 'award',
                'color' => 'red',
                'features' => [
                    'Kelola Fakultas - Tambah, edit, hapus data fakultas',
                    'Kelola User - Manajemen semua pengguna sistem',
                    'Monitoring Global - Lihat statistik seluruh sistem',
                    'System Configuration - Pengaturan sistem dan keamanan'
                ]
            ]
        ];

        return view('layouts/landing2', compact('stats', 'features', 'roles'));
    }
}
