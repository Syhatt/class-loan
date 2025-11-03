<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ClassLoan UINSA - Sistem Peminjaman Terintegrasi')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js untuk interaktivitas -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>

    @stack('styles')
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-emerald-50">
    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <div
                        class="w-10 h-10 bg-gradient-to-r from-blue-600 to-emerald-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="book-open" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-900">ClassLoan UINSA</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#"
                        class="hidden md:flex text-gray-600 hover:text-gray-900 transition-colors">Tentang</a>
                    <a href="#" class="hidden md:flex text-gray-600 hover:text-gray-900 transition-colors">Cara
                        Kerja</a>
                    <a href="#" class="hidden md:flex text-gray-600 hover:text-gray-900 transition-colors">FAQ</a>
                    <a href="{{ route('login') }}"
                        class="bg-gradient-to-r from-blue-600 to-emerald-600 hover:from-blue-700 hover:to-emerald-700 text-white px-4 py-2 rounded-lg transition-all">
                        Masuk ke Sistem
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-blue-600 to-emerald-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="book-open" class="w-5 h-5 text-white"></i>
                        </div>
                        <span class="text-lg font-bold">ClassLoan UINSA</span>
                    </div>
                    <p class="text-gray-400">
                        Sistem peminjaman kelas dan barang terintegrasi untuk Universitas Islam Negeri Sunan Ampel
                        Surabaya
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Fitur</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Peminjaman Kelas</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Peminjaman Barang</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Dashboard Analytics</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Generate Laporan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Cara Kerja</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kontak Support</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Panduan Pengguna</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>support@classloan.uinsa.ac.id</li>
                        <li>(031) 1234-5678</li>
                        <li>UINSA Surabaya</li>
                        <li>Jl. Ahmad Yani No. 117</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>© {{ date('Y') }} ClassLoan UINSA. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Initialize Lucide icons setelah DOM loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Create icons untuk semua element dengan data-lucide attribute
            lucide.createIcons();

            // Re-create icons setelah Alpine.js render
            document.addEventListener('alpine:initialized', function() {
                setTimeout(() => {
                    lucide.createIcons();
                }, 100);
            });
        });

        // Fungsi untuk tabs
        function showTab(tabName) {
            // Hide semua tab content
            const allTabs = document.querySelectorAll('.tab-content');
            allTabs.forEach(tab => {
                tab.classList.remove('active');
                tab.style.display = 'none';
            });

            // Remove active class dari semua buttons
            const allButtons = document.querySelectorAll('.tab-button');
            allButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            // Show tab yang dipilih
            const selectedTab = document.getElementById(tabName + '-tab');
            if (selectedTab) {
                selectedTab.classList.add('active');
                selectedTab.style.display = 'block';
            }

            // Add active class ke button yang dipilih
            const selectedButton = document.getElementById(tabName + '-button');
            if (selectedButton) {
                selectedButton.classList.add('active', 'border-blue-500', 'text-blue-600');
                selectedButton.classList.remove('border-transparent', 'text-gray-500');
            }

            // Re-create icons setelah tab change
            setTimeout(() => {
                lucide.createIcons();
            }, 50);
        }
    </script>

    @stack('scripts')
</body>

</html>
