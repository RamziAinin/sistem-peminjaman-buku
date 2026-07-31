@extends('layouts.main')

@section('content')
<!-- Ubah p-8 jadi p-4 md:p-8 agar padding di HP tidak memakan banyak tempat -->
<div class="p-4 md:p-8 max-w-6xl mx-auto w-full">

    <!-- Banner Hero Section -->
    <div class="bg-gradient-to-r from-biru to-TUA rounded-2xl p-6 md:p-10 text-white shadow-lg mb-8 md:mb-10 relative overflow-hidden">
        <!-- Elemen Dekorasi Abstrak (Dikecilkan saat di HP) -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-40 h-40 md:w-64 md:h-64 rounded-full bg-white opacity-10"></div>
        <div class="absolute bottom-0 right-10 md:right-20 -mb-10 w-24 h-24 md:w-32 md:h-32 rounded-full bg-white opacity-10"></div>

        <div class="relative z-10">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-3 leading-tight">Selamat Datang di Layanan Online 📚</h2>
            <p class="text-purple-100 max-w-2xl text-sm md:text-lg mb-6 md:mb-8 leading-relaxed">
                Perpustakaan Daerah Kota Pekalongan kini memudahkan Anda untuk memperpanjang masa peminjaman
                buku tanpa harus datang langsung ke lokasi. Proses cepat, mudah, dan transparan.
            </p>
            <!-- Tombol dibikin full width di HP, normal di Desktop -->
            <a href="{{ route('user.form') }}"
                class="flex md:inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-blue-600 font-bold rounded-lg shadow-md hover:bg-gray-50 transition transform hover:-translate-y-0.5 w-full md:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
                Isi Formulir Perpanjangan
            </a>
        </div>
    </div>

    <!-- Section Cara Kerja -->
    <div class="mb-8 md:mb-10">
        <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-4 md:mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-TUA flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Cara Perpanjangan Buku Online
        </h3>

        <!-- Grid sudah aman: 1 kolom di HP, 3 kolom di Desktop -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
            <!-- Step 1 -->
            <div class="bg-white border border-gray-200 p-5 md:p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 text-TUA rounded-full flex items-center justify-center font-bold text-lg md:text-xl mb-4">
                    1
                </div>
                <h4 class="font-bold text-gray-800 mb-2">Siapkan Data Buku</h4>
                <p class="text-xs md:text-sm text-gray-600 leading-relaxed">Pastikan Anda mengetahui Barcode Buku dan menyiapkan foto fisik buku yang sedang dipinjam.</p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white border border-gray-200 p-5 md:p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 text-TUA rounded-full flex items-center justify-center font-bold text-lg md:text-xl mb-4">
                    2
                </div>
                <h4 class="font-bold text-gray-800 mb-2">Isi Formulir</h4>
                <p class="text-xs md:text-sm text-gray-600 leading-relaxed">Masuk ke menu "Perpanjang Buku" di sebelah kiri (atau via ikon ☰ di HP), lalu isi data diri dan buku dengan lengkap.</p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white border border-gray-200 p-5 md:p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 text-TUA rounded-full flex items-center justify-center font-bold text-lg md:text-xl mb-4">
                    3
                </div>
                <h4 class="font-bold text-gray-800 mb-2">Tunggu Proses</h4>
                <p class="text-xs md:text-sm text-gray-600 leading-relaxed">Sistem akan memvalidasi data Anda maksimal 1x24 jam. Jika berhasil, masa pinjam otomatis diperpanjang 7 hari.</p>
            </div>
        </div>
    </div>

    <!-- Section Aturan Penting -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 md:p-5 rounded-r-xl shadow-sm">
        <h4 class="font-bold text-yellow-800 mb-2 flex items-center gap-2">
            <span class="text-lg">⚠️</span> Syarat dan Ketentuan Penting
        </h4>
        <ul class="list-disc ml-5 text-xs md:text-sm text-yellow-700 space-y-1.5 leading-relaxed">
            <li>Perpanjangan buku hanya dapat dilakukan maksimal <strong>2 (dua) kali</strong> untuk setiap buku.</li>
            <li>Pengajuan perpanjangan harus dilakukan <strong>sebelum</strong> melewati batas tanggal pengembalian.</li>
            <li>Buku yang sudah berstatus <strong>terlambat/skorsing</strong> tidak bisa diperpanjang secara online. Anda wajib datang langsung ke perpustakaan.</li>
        </ul>
    </div>

</div>
@endsection