@extends('layouts.admin')

@section('title', 'Riwayat Validasi')
@section('header_title', 'Riwayat Validasi Perpanjangan')

@section('content')

    <!-- Title & Actions Bar -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">ARSIP PENGAJUAN SELESAI</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar seluruh riwayat buku yang sudah disetujui maupun ditolak.</p>
        </div>

        <!-- Search Box (Support Filter Terpilih) -->
        <form action="{{ route('admin.riwayat') }}" method="GET" class="relative w-full md:w-64">
            <!-- Simpan state filter saat melakukan pencarian -->
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif
            
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau ID Buku..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-sm">
            <button type="submit" class="absolute left-3 top-2.5 text-gray-400 hover:text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </form>
    </div>

    <!-- Filter Tabs Dinamis -->
    <div class="flex space-x-1 border-b border-gray-200 mb-6 overflow-x-auto">
        <a href="{{ route('admin.riwayat', ['search' => request('search')]) }}"
            class="px-4 py-2 text-sm font-medium whitespace-nowrap {{ !request('filter') ? 'text-grey-900 border-b-2 border-purple-600 bg-purple-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} rounded-t-lg transition">
            Semua Riwayat ({{ $countAll }})
        </a>
        <a href="{{ route('admin.riwayat', ['filter' => 'disetujui', 'search' => request('search')]) }}"
            class="px-4 py-2 text-sm font-medium whitespace-nowrap {{ request('filter') == 'disetujui' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} rounded-t-lg transition">
            Disetujui ({{ $countDisetujui }})
        </a>
        <a href="{{ route('admin.riwayat', ['filter' => 'ditolak', 'search' => request('search')]) }}"
            class="px-4 py-2 text-sm font-medium whitespace-nowrap {{ request('filter') == 'ditolak' ? 'text-red-600 border-b-2 border-red-600 bg-red-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} rounded-t-lg transition">
            Ditolak ({{ $countDitolak }})
        </a>
    </div>

    <!-- Tabel Data Riwayat -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200 uppercase tracking-wider">
                        <th class="py-4 px-6 font-semibold">Tgl Keputusan</th>
                        <th class="py-4 px-6 font-semibold">Data Peminjam</th>
                        <th class="py-4 px-6 font-semibold">Judul Buku</th>
                        <th class="py-4 px-6 font-semibold">ID Buku</th>
                        <th class="py-4 px-6 font-semibold text-center">Status Keputusan</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">

                    @forelse($riwayat as $item)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($item->updated_at)->format('d M Y') }}</span>
                                <span class="block text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->updated_at)->format('H:i') }} WIB</span>
                            </td>
                            <td class="py-4 px-6">
                                <!-- NAMA PEMINJAM (Bisa diklik menuju detail) -->
                                <a href="{{ route('admin.validasi.detail', $item->id) }}" class="font-bold text-gray-800 hover:text-purple-600 hover:underline transition">
                                    {{ $item->nama }}
                                </a>
                                <span class="block text-xs text-gray-500">No Kartu Anggota: {{ $item->nomor_anggota }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-medium text-purple-700">{{ $item->judul_buku }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-mono bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs border border-gray-200">{{ $item->id_buku }}</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <!-- BADGE STATUS (Bisa diklik menuju detail juga) -->
                                @if(strtolower($item->status) == 'disetujui')
                                    <a href="{{ route('admin.validasi.detail', $item->id) }}" class="inline-block bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1 rounded-full text-xs font-semibold transition cursor-pointer">
                                        Disetujui
                                    </a>
                                @else
                                    <a href="{{ route('admin.validasi.detail', $item->id) }}" class="inline-block bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded-full text-xs font-semibold transition cursor-pointer">
                                        Ditolak
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-gray-500">
                                @if(request('search'))
                                    Riwayat "<strong>{{ request('search') }}</strong>" tidak ditemukan pada kategori ini.
                                @else
                                    Belum ada arsip riwayat pada kategori ini.
                                @endif
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Pagination Bawaan Laravel yang sudah responsif dengan Tailwind -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $riwayat->links() }}
        </div>
    </div>

@endsection