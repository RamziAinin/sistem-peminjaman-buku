@extends('layouts.admin')

@section('title', 'Validasi Perpanjangan')
@section('header_title', 'Manajemen Validasi')

@section('content')

    <!-- Alert Sukses -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Title & Actions Bar -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Pengajuan Perpanjangan</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola permohonan perpanjangan waktu pinjam buku dari anggota.</p>
        </div>

        <!-- Search Box (Form Valid) -->
        <form action="{{ route('admin.validasi') }}" method="GET" class="relative w-full md:w-64">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau ID Buku..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent text-sm">
            <button type="submit" class="absolute left-3 top-2.5 text-gray-400 hover:text-purple-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </form>
    </div>

    <!-- Filter Tabs -->
    <div class="flex space-x-1 border-b border-gray-200 mb-6">
        <button class="px-4 py-2 text-sm font-medium text-purple-600 border-b-2 border-purple-600 bg-purple-50 rounded-t-lg">
            Menunggu Validasi ({{ $jumlahPending }})
        </button>
    </div>

    <!-- Tabel Data Utama -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200 uppercase tracking-wider">
                        <th class="py-4 px-6 font-semibold">Tgl Pengajuan</th>
                        <th class="py-4 px-6 font-semibold">Data Peminjam</th>
                        <th class="py-4 px-6 font-semibold">Judul & Batas Waktu</th>
                        <th class="py-4 px-6 font-semibold">ID Buku</th>
                        <th class="py-4 px-6 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">

                    @forelse($pengajuan as $item)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</span>
                                <span class="block text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</span>
                            </td>
                            <td class="py-4 px-6">
                                <!-- Link ke halaman detail sudah diaktifkan -->
                                <a href="{{ route('admin.validasi.detail', $item->id) }}" class="font-bold text-gray-800 hover:text-purple-600 hover:underline transition">
                                    {{ $item->nama }}
                                </a>
                                <span class="block text-xs text-gray-500">Nomor Kartu Anggota: {{ $item->nomor_anggota }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-medium text-purple-700">{{ $item->judul_buku }}</span>
                                <span class="block mt-1 bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded font-medium w-max">
                                    Batas: {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-mono bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs border border-gray-200">{{ $item->id_buku }}</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex justify-center gap-2 items-center">
                                    
                                    <!-- Tombol SETUJU -->
                                    <form action="{{ route('admin.validasi.update', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="disetujui">
                                        <button type="submit" onclick="return confirm('Yakin ingin menyetujui pengajuan dari {{ $item->nama }}?')" class="bg-green-500 text-white hover:bg-green-600 px-4 py-2 rounded-lg font-medium transition shadow-sm flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Setuju
                                        </button>
                                    </form>

                                    <!-- Tombol TOLAK -->
                                    <form action="{{ route('admin.validasi.update', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="ditolak">
                                        <button type="submit" onclick="return confirm('Yakin ingin menolak pengajuan dari {{ $item->nama }}?')" class="bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded-lg font-medium transition shadow-sm flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Tolak
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-gray-500">
                                @if(request('search'))
                                    Data untuk pencarian "<strong>{{ request('search') }}</strong>" tidak ditemukan.
                                @else
                                    Semua pengajuan sudah divalidasi. Tidak ada data yang menunggu.
                                @endif
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Pagination Laravel -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $pengajuan->links() }}
        </div>
    </div>

@endsection