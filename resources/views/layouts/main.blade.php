<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Perpustakaan Pekalongan</title>
    <!-- Memanggil Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        TUA: '#162E93',
                        biru: '#012269',
                        ijo: '#218028',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-white text-gray-800 font-sans antialiased flex h-screen overflow-hidden relative">

    <!-- ================= OVERLAY (GELAP) UNTUK HP ================= -->
    <!-- Default disembunyikan (hidden), akan muncul saat sidebar dibuka -->
    <div id="sidebar-overlay" onclick="toggleSidebar()" 
         class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden transition-opacity duration-300 opacity-0">
    </div>

    <!-- ================= SIDEBAR (KIRI) ================= -->
    <!-- Di HP: Posisinya absolut/fixed, sembunyi di luar layar (-translate-x-full). Muncul saat diklik -->
    <!-- Di PC: Posisinya statis, jadi bagian dari flex (md:translate-x-0 md:relative md:flex) -->
    <aside id="sidebar-menu" 
           class="fixed inset-y-0 left-0 z-30 w-64 transform -translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex-shrink-0 border-r border-gray-200 flex flex-col h-full bg-white shadow-xl md:shadow-none">
        
        <!-- Tombol Tutup Silang (Hanya Muncul di HP) -->
        <button onclick="toggleSidebar()" class="absolute top-4 right-4 text-gray-500 hover:text-red-500 md:hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Logo Area -->
        <div class="h-16 flex items-center px-6 border-b border-gray-100">
            <svg class="w-6 h-6 text-TUA mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                </path>
            </svg>
            <span class="text-lg font-bold text-TUA tracking-wide">PERPUSTAKAAN</span>
        </div>

        <!-- Menu Navigasi -->
        <nav class="flex-1 px-4 space-y-1 mt-6 overflow-y-auto">
            <!-- Menu Beranda -->
            <a href="/"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->is('/') ? 'bg-TUA text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                Beranda Utama
            </a>

            <!-- Menu Form Perpanjangan -->
            <a href="{{ route('user.form') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->is('pengajuan') ? 'bg-TUA text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                Perpanjang Buku
            </a>
        </nav>
    </aside>

    <!-- ================= KONTEN UTAMA (KANAN) ================= -->
    <main class="flex-1 flex flex-col bg-gray-50 h-full overflow-y-auto w-full md:w-auto">
        
        <!-- Header Atas -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center px-4 md:px-8 shadow-sm flex-shrink-0">
            <!-- Tombol Hamburger (Hanya Muncul di HP) -->
            <button onclick="toggleSidebar()" class="mr-4 text-gray-600 hover:text-purple-600 focus:outline-none md:hidden">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <h1 class="text-xl md:text-2xl font-bold text-gray-700 truncate">Layanan Digital Publik</h1>
        </header>

        <!-- Area Injeksi Konten Blade -->
        @yield('content')
        
        <!-- ================= FOOTER ================= -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-6xl mx-auto px-4 md:px-8 py-6 w-full flex flex-col lg:flex-row justify-between items-center gap-4 text-center lg:text-left">
                <div class="text-sm text-gray-500 font-medium">
                    &copy; 2026 Layanan Perpustakaan Daerah.
                </div>
                <div class="flex flex-col sm:flex-row flex-wrap justify-center items-center gap-4 sm:gap-6 text-sm text-gray-600">
                    <!-- Kontak WA -->
                    <span class="flex items-center gap-2 hover:text-green-600 transition cursor-pointer">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        0812-3456-7890
                    </span>
                    <!-- Email -->
                    <span class="flex items-center gap-2 hover:text-purple-600 transition cursor-pointer">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        info@perpuspekalongan.go.id
                    </span>
                    <!-- Instagram -->
                    <span class="flex items-center gap-2 hover:text-pink-600 transition cursor-pointer">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @perpus.pekalongan
                    </span>
                </div>
            </div>
        </footer>
        
    </main>

    <!-- SCRIPT UNTUK BUKA/TUTUP SIDEBAR DI HP -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar-menu');
            const overlay = document.getElementById('sidebar-overlay');
            
            // Cek apakah sidebar sedang tersembunyi (-translate-x-full)
            if (sidebar.classList.contains('-translate-x-full')) {
                // Tampilkan Sidebar
                sidebar.classList.remove('-translate-x-full');
                
                // Tampilkan Overlay Gelap
                overlay.classList.remove('hidden');
                // Kasih delay dikit biar animasi fade-in opasitasnya jalan
                setTimeout(() => {
                    overlay.classList.remove('opacity-0');
                    overlay.classList.add('opacity-100');
                }, 10);
            } else {
                // Sembunyikan Sidebar
                sidebar.classList.add('-translate-x-full');
                
                // Sembunyikan Overlay Gelap
                overlay.classList.remove('opacity-100');
                overlay.classList.add('opacity-0');
                
                // Tunggu animasi transisi selesai (300ms) baru di-hidden beneran
                setTimeout(() => {
                    overlay.classList.add('hidden');
                }, 300);
            }
        }
    </script>

</body>
</html>