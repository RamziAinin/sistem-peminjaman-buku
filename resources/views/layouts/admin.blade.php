<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Perpustakaan Pekalongan</title>
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

<body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden relative">

    <!-- OVERLAY GELAP UNTUK MOBILE (MUNCUL SAAT SIDEBAR TERBUKA) -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 hidden md:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <!-- SIDEBAR ADMIN -->
    <!-- Tambahan class: fixed, transform, -translate-x-full (untuk sembunyi di HP), md:relative, md:translate-x-0 (selalu muncul di PC) -->
    <aside id="sidebar" class="w-64 flex-shrink-0 border-r border-gray-200 flex flex-col h-full bg-white shadow-sm z-30 fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out">
        
        <!-- Logo Area & Tombol Close (Mobile) -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100 bg-blue-50">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-TUA mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                    </path>
                </svg>
                <span class="text-lg font-bold text-TUA tracking-wide">ADMIN PERPUS</span>
            </div>
            <!-- Tombol Silang khusus HP -->
            <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-red-500 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-6 overflow-y-auto">
            <!-- Menu Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-TUA text-white shadow-sm' : 'text-gray-600 hover:bg-blue-50 hover:text-biru' }} rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                Dashboard
            </a>

            <!-- Menu Validasi -->
            <a href="{{ route('admin.validasi') }}"
                class="flex items-center justify-between px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.validasi') ? 'bg-TUA text-white shadow-sm' : 'text-gray-600 hover:bg-blue-50 hover:text-biru' }} rounded-lg transition">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    Validasi Perpanjangan
                </div>
                
                <!-- Badge Notifikasi Dinamis -->
                @php
                    $pendingCount = \App\Models\Peminjaman::where('status', 'menunggu_verifikasi')->count();
                @endphp
                
                @if($pendingCount > 0)
                    <span class="{{ request()->routeIs('admin.validasi') ? 'bg-white text-TUA' : 'bg-red-500 text-white' }} text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>
            
            <!-- Menu Riwayat (Kalau ada, pastikan ditambahkan di sini ya) -->
            
            <!-- Menu Upload Database -->
            <a href="{{ route('admin.upload') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.upload') ? 'bg-TUA text-white shadow-sm' : 'text-gray-600 hover:bg-blue-50 hover:text-biru' }} rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Upload Database
            </a>
        </nav>

        <!-- Tombol Logout -->
        <div class="p-4 border-t border-gray-100">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- KONTEN UTAMA ADMIN -->
    <!-- Tambahan w-full agar di HP kontennya nggak kepotong -->
    <main class="flex-1 flex flex-col h-full overflow-y-auto w-full">
        
        <!-- Header Atas -->
        <!-- Ubah px-8 jadi px-4 md:px-8 untuk HP -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-8 sticky top-0 z-10 shadow-sm flex-shrink-0">
            
            <div class="flex items-center gap-3">
                <!-- Tombol Hamburger (Khusus Layar HP) -->
                <button onclick="toggleSidebar()" class="md:hidden p-2 -ml-2 text-gray-600 hover:text-TUA hover:bg-gray-100 rounded-lg focus:outline-none transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-lg md:text-xl font-bold text-gray-800">@yield('header_title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-gray-700">{{ Auth::user()->name ?? 'Admin Utama' }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'admin@perpuspekalongan.go.id' }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center text-TUA font-bold border-2 border-TUA uppercase flex-shrink-0">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Area Konten Dinamis -->
        <div class="flex-1 p-4 md:p-8 max-w-7xl mx-auto w-full">
            @yield('content')
        </div>

        <!-- FOOTER ADMIN -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-6xl mx-auto px-4 md:px-8 py-6 w-full flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
                <div class="text-sm text-gray-500 font-medium">
                    &copy; 2026 Layanan Perpustakaan Daerah.
                </div>
                <div class="flex flex-wrap justify-center items-center gap-4 md:gap-6 text-sm text-gray-600">
                    <!-- Kontak WA -->
                    <span class="flex items-center gap-1.5 hover:text-green-600 transition cursor-pointer">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        0812-3456-7890
                    </span>
                    <!-- Email -->
                    <span class="flex items-center gap-1.5 hover:text-purple-600 transition cursor-pointer hidden sm:flex">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        info@perpuspekalongan.go.id
                    </span>
                </div>
            </div>
        </footer>
    </main>

    <!-- SCRIPT UNTUK TOGGLE SIDEBAR DI MOBILE -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            // Toggle class translate untuk menggeser sidebar
            sidebar.classList.toggle('-translate-x-full');
            
            // Toggle class hidden untuk memunculkan/menyembunyikan latar belakang gelap
            overlay.classList.toggle('hidden');
        }
    </script>
</body>

</html>