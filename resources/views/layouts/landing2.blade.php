@extends('layouts.landing')

@section('title', 'ClassLoan UINSA - Sistem Peminjaman Kelas & Barang Terintegrasi')

@section('content')
    <!-- Hero Section -->
    <section class="container mx-auto px-4 py-20" x-data="{ activeTab: 'mahasiswa' }">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center space-x-2 bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full mb-4">
                <i data-lucide="star" class="w-4 h-4"></i>
                <span class="text-sm font-medium">Sistem Peminjaman Terintegrasi UINSA</span>
            </div>

            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                Peminjaman Kelas & Barang
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-emerald-600">
                    Lebih Mudah, Lebih Cepat, Lebih Terorganisir
                </span>
            </h1>

            <p class="text-xl text-gray-600 mb-8 leading-relaxed max-w-3xl mx-auto">
                Sistem peminjaman modern untuk Universitas Islam Negeri Sunan Ampel Surabaya.
                Kelola peminjaman kelas dan barang dengan mudah, approval otomatis, dan akses
                berdasarkan fakultas untuk memastikan efisiensi maksimal.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}"
                    class="bg-gradient-to-r from-blue-600 to-emerald-600 hover:from-blue-700 hover:to-emerald-700 text-white text-lg px-8 py-3 rounded-lg inline-flex items-center justify-center transition-all transform hover:scale-105">
                    Mulai Sekarang
                    <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </a>
                <a href="#features"
                    class="border-2 border-gray-300 text-gray-700 hover:border-gray-400 text-lg px-8 py-3 rounded-lg inline-flex items-center justify-center transition-all">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-20">
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow p-6 text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="calendar" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900">100+</div>
                <div class="text-gray-600">Ruang Kelas</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow p-6 text-center">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="package" class="w-6 h-6 text-emerald-600"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900">500+</div>
                <div class="text-gray-600">Barang Tersedia</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow p-6 text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="users" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900">50,000+</div>
                <div class="text-gray-600">Pengguna Aktif</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow p-6 text-center">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="trending-up" class="w-6 h-6 text-orange-600"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900">99.9%</div>
                <div class="text-gray-600">Kepuasan Pengguna</div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Fitur Unggulan Sistem Kami
                </h2>
                <p class="text-xl text-gray-600">
                    Solusi lengkap untuk kebutuhan peminjaman kelas dan barang di UINSA
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 p-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i data-lucide="shield" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Akses Berbasis Fakultas</h3>
                    <p class="text-gray-600">
                        Setiap fakultas memiliki akses terbatas ke ruang dan barang miliknya sendiri,
                        memastikan tidak ada campur aduk antar fakultas.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 p-6">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                        <i data-lucide="clock" class="w-6 h-6 text-emerald-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Approval Real-time</h3>
                    <p class="text-gray-600">
                        Proses approval yang cepat dan transparan. Mahasiswa dapat melihat status
                        peminjaman secara real-time.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 p-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <i data-lucide="bar-chart-3" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Dashboard Analytics</h3>
                    <p class="text-gray-600">
                        Monitoring dan laporan lengkap untuk admin. Track penggunaan ruang dan barang
                        dengan visualisasi data yang menarik.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 p-6">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                        <i data-lucide="zap" class="w-6 h-6 text-orange-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Peminjaman Instan</h3>
                    <p class="text-gray-600">
                        Proses peminjaman yang mudah dan cepat. Booking ruang dan barang dalam
                        beberapa klik saja.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 p-6">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <i data-lucide="file-text" class="w-6 h-6 text-red-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Generate Laporan Otomatis</h3>
                    <p class="text-gray-600">
                        Buat laporan peminjaman bulanan atau tahunan dengan mudah. Export ke PDF
                        atau Excel untuk kebutuhan administrasi.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 p-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <i data-lucide="lock" class="w-6 h-6 text-indigo-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Keamanan Terjamin</h3>
                    <p class="text-gray-600">
                        Sistem dengan keamanan berlapis dan enkripsi data untuk melindungi
                        informasi peminjaman dan data pengguna.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Role-based Access Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Akses Sesuai Peran Anda
                </h2>
                <p class="text-xl text-gray-600">
                    Setiap role memiliki fitur yang disesuaikan dengan kebutuhannya
                </p>
            </div>

            <!-- Tabs -->
            <div class="max-w-5xl mx-auto">
                <div class="flex flex-wrap justify-center mb-8 border-b border-gray-200">
                    <button id="mahasiswa-button" onclick="showTab('mahasiswa')"
                        class="tab-button active px-6 py-3 border-b-2 font-medium text-sm transition-colors border-blue-500 text-blue-600">
                        Mahasiswa
                    </button>
                    <button id="dosen-button" onclick="showTab('dosen')"
                        class="tab-button px-6 py-3 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700">
                        Dosen
                    </button>
                    <button id="admin-button" onclick="showTab('admin')"
                        class="tab-button px-6 py-3 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700">
                        Admin Fakultas
                    </button>
                    <button id="superadmin-button" onclick="showTab('superadmin')"
                        class="tab-button px-6 py-3 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700">
                        Super Admin
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <!-- Mahasiswa Tab -->
                    <div id="mahasiswa-tab" class="tab-content active">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold">Mahasiswa</h3>
                        </div>
                        <p class="text-lg text-gray-600 mb-6">Akses penuh untuk kebutuhan peminjaman kelas dan barang</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Dashboard Informasi</div>
                                    <div class="text-sm text-gray-600">Lihat statistik peminjaman dan info sistem</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Peminjaman Kelas</div>
                                    <div class="text-sm text-gray-600">Booking ruang kelas sesuai fakultas</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">History Peminjaman</div>
                                    <div class="text-sm text-gray-600">Track semua peminjaman kelas</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Peminjaman Barang</div>
                                    <div class="text-sm text-gray-600">Pinjam barang untuk kegiatan</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">History Barang</div>
                                    <div class="text-sm text-gray-600">Riwayat peminjaman barang</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dosen Tab -->
                    <div id="dosen-tab" class="tab-content">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="user-check" class="w-5 h-5 text-purple-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold">Dosen</h3>
                        </div>
                        <p class="text-lg text-gray-600 mb-6">Akses khusus untuk kebutuhan akademik dan penelitian</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Dashboard Informasi</div>
                                    <div class="text-sm text-gray-600">Lihat statistik peminjaman dan info sistem</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Peminjaman Kelas</div>
                                    <div class="text-sm text-gray-600">Booking ruang untuk perkuliahan dan seminar</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">History Peminjaman</div>
                                    <div class="text-sm text-gray-600">Track semua peminjaman kelas</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Peminjaman Barang</div>
                                    <div class="text-sm text-gray-600">Pinjam barang untuk penelitian</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">History Barang</div>
                                    <div class="text-sm text-gray-600">Riwayat peminjaman barang</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Fakultas Tab -->
                    <div id="admin-tab" class="tab-content">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="settings" class="w-5 h-5 text-emerald-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold">Admin Fakultas</h3>
                        </div>
                        <p class="text-lg text-gray-600 mb-6">Kontrol penuh untuk manajemen fakultas</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Kelola Data Kelas</div>
                                    <div class="text-sm text-gray-600">Tambah, edit, hapus ruang kelas fakultas</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Kelola Data Barang</div>
                                    <div class="text-sm text-gray-600">Manajemen inventaris fakultas</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Approval Peminjaman Kelas</div>
                                    <div class="text-sm text-gray-600">Setujui/tolak peminjaman ruang</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Approval Peminjaman Barang</div>
                                    <div class="text-sm text-gray-600">Setujui/tolak peminjaman barang</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Generate Laporan</div>
                                    <div class="text-sm text-gray-600">Buat laporan bulanan/tahunan</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Super Admin Tab -->
                    <div id="superadmin-tab" class="tab-content">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="award" class="w-5 h-5 text-red-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold">Super Admin</h3>
                        </div>
                        <p class="text-lg text-gray-600 mb-6">Akses tertinggi untuk manajemen sistem global</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Kelola Fakultas</div>
                                    <div class="text-sm text-gray-600">Tambah, edit, hapus data fakultas</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Kelola User</div>
                                    <div class="text-sm text-gray-600">Manajemen semua pengguna sistem</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Monitoring Global</div>
                                    <div class="text-sm text-gray-600">Lihat statistik seluruh sistem</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="icon-wrapper w-5 h-5 text-emerald-500 mt-1 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium">System Configuration</div>
                                    <div class="text-sm text-gray-600">Pengaturan sistem dan keamanan</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Cara Kerja Sistem
                </h2>
                <p class="text-xl text-gray-600">
                    Proses peminjaman yang sederhana dan efisien
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 max-w-5xl mx-auto">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600">1</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Login Sistem</h3>
                    <p class="text-gray-600">Masuk menggunakan akun UINSA Anda</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-emerald-600">2</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Pilih Ruang/Barang</h3>
                    <p class="text-gray-600">Browse kelas dan barang tersedia</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-purple-600">3</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Ajukan Peminjaman</h3>
                    <p class="text-gray-600">Isi form dan kirim permohonan</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-orange-600">4</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Tunggu Approval</h3>
                    <p class="text-gray-600">Notifikasi otomatis saat disetujui</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-emerald-600">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-white mb-4">
                Siap Meningkatkan Efisiensi Peminjaman?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan mahasiswa dan dosen yang sudah menikmati
                kemudahan sistem peminjaman terintegrasi UINSA
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}"
                    class="bg-white text-blue-600 hover:bg-gray-100 text-lg px-8 py-3 rounded-lg inline-flex items-center justify-center transition-all transform hover:scale-105">
                    Mulai Sekarang
                    <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </a>
                <a href="#"
                    class="border-2 border-white text-white hover:bg-white hover:text-blue-600 text-lg px-8 py-3 rounded-lg inline-flex items-center justify-center transition-all">
                    Hubungi Admin
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Initialize tabs setelah page load
        document.addEventListener('DOMContentLoaded', function() {
            // Show mahasiswa tab by default
            showTab('mahasiswa');

            // Re-create icons setelah beberapa ms untuk memastikan semua icons muncul
            setTimeout(() => {
                lucide.createIcons();
            }, 200);
        });
    </script>
@endpush
