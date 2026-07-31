@extends('layouts.admin')

@section('title', 'Dashboard Utama')
@section('header_title', 'Dashboard Utama')

@section('content')

    <!-- Card Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <!-- Card 1: Request Perpanjangan -->
        <div class="bg-white p-6 rounded-xl border border-purple-200 shadow-sm flex items-center gap-4 ring-1 ring-purple-100">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Request Perpanjangan</p>
                <p class="text-2xl font-bold text-purple-700">
                    {{ $requestPerpanjangan ?? 0 }} 
                    <span class="text-sm text-gray-400 font-normal">Menunggu</span>
                </p>
            </div>
        </div>

        <!-- Card 2: Buku Terlambat -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 text-red-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Buku Terlambat</p>
                <p class="text-2xl font-bold text-red-600">
                    {{ $bukuTerlambat ?? 0 }}
                </p>
            </div>
        </div>

    </div>

    <!-- Tabel Riwayat Validasi -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Riwayat Validasi Perpanjangan</h3>
            <!-- Link ke halaman riwayat full (nanti bisa dibuat rutenya) -->
            <a href="{{ route('admin.riwayat') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">Lihat Semua &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-gray-500 text-sm border-b border-gray-200">
                        <th class="py-3 px-6 font-medium">Waktu Pengajuan</th>
                        <th class="py-3 px-6 font-medium">Nama Peminjam</th>
                        <th class="py-3 px-6 font-medium">Judul Buku</th>
                        <th class="py-3 px-6 font-medium">Barcode Buku</th>
                        <th class="py-3 px-6 font-medium text-center">Status Validasi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">

    <!-- Looping data riwayat dari Controller -->
    @forelse($riwayatValidasi ?? [] as $riwayat)
        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
            <td class="py-4 px-6 whitespace-nowrap">
                <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($riwayat->updated_at)->format('d M Y') }}</span>
                <span class="block text-xs text-gray-500">{{ \Carbon\Carbon::parse($riwayat->updated_at)->format('H:i') }} WIB</span>
            </td>
            
            <td class="py-4 px-6">
                <!-- Langsung panggil kolom nama & nomor_anggota dari database -->
                <span class="font-bold text-gray-800">{{ $riwayat->nama }}</span>
                <span class="block text-xs text-gray-500">Nomor Kartu Anggota: {{ $riwayat->nomor_anggota }}</span>
            </td>
            
            <td class="py-4 px-6">
                <!-- Langsung panggil kolom judul_buku -->
                <span class="font-medium text-purple-700">{{ $riwayat->judul_buku }}</span>
            </td>
            
            <td class="py-4 px-6">
                <!-- Langsung panggil kolom id_buku -->
                <span class="font-mono bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs border border-gray-200">
                    {{ $riwayat->id_buku }}
                </span>
            </td>
            
            <td class="py-4 px-6 text-center">
                <!-- Cek statusnya langsung dari kolom status -->
                @if(strtolower($riwayat->status) == 'disetujui')
                    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                        Disetujui
                    </span>
                @else
                    <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                        Ditolak
                    </span>
                @endif
            </td>
        </tr>
    @empty
        <!-- Jika belum ada riwayat (karena di database kamu saat ini statusnya masih menunggu_verifikasi semua) -->
        <tr>
            <td colspan="5" class="py-8 text-center text-gray-400 text-sm">
                Belum ada riwayat validasi perpanjangan yang diproses.
            </td>
        </tr>
    @endforelse

</tbody>
            </table>
        </div>
    </div>

@endsection