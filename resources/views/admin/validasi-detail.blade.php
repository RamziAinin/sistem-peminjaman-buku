@extends('layouts.admin')

@section('title', 'Detail Pengajuan')
@section('header_title', 'Detail Permohonan')

@section('content')

    <!-- Tombol Kembali -->
    <div class="mb-6">
        <a href="{{ route('admin.validasi') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-purple-600 transition">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Validasi
        </a>
    </div>

    <!-- Alert Sukses (kalau admin klik tombol setuju/tolak dari halaman ini) -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Info Peminjam & Buku -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card Info Peminjam -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Anggota</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Nama Lengkap</p>
                        <p class="text-base font-semibold text-gray-800">{{ $pengajuan->nama }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Nomor Anggota</p>
                        <p class="text-base font-medium text-gray-800">{{ $pengajuan->nomor_anggota }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Email</p>
                        <p class="text-base font-medium text-gray-800">{{ $pengajuan->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Card Info Buku -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Detail Peminjaman Buku</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Judul Buku</p>
                        <p class="text-lg font-bold text-purple-700">{{ $pengajuan->judul_buku }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">ID Buku</p>
                        <p class="font-mono bg-gray-100 text-gray-600 px-2 py-1 rounded text-sm w-max">{{ $pengajuan->id_buku }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Status Saat Ini</p>
                        @if(strtolower($pengajuan->status) == 'disetujui')
                            <span class="inline-block mt-1 bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Disetujui</span>
                        @elseif(strtolower($pengajuan->status) == 'ditolak')
                            <span class="inline-block mt-1 bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Ditolak</span>
                        @else
                            <span class="inline-block mt-1 bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Menunggu Verifikasi</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Tanggal Pinjam Awal</p>
                        <p class="text-base font-medium text-gray-800">{{ \Carbon\Carbon::parse($pengajuan->tanggal_pinjam)->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Batas Waktu Pengembalian</p>
                        <p class="text-base font-bold text-red-600">{{ \Carbon\Carbon::parse($pengajuan->tanggal_kembali)->format('d F Y') }}</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Foto & Aksi -->
        <div class="space-y-6">
            
            <!-- Card Foto Buku -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-100 px-4 py-3">
                    <h3 class="text-sm font-bold text-gray-700">Foto Bukti / Sampul</h3>
                </div>
                
                <!-- PERBAIKAN LOGIKA FOTO SUPABASE DI SINI -->
                <div class="p-4 flex justify-center bg-gray-50 relative group">
                    @if($pengajuan->foto)
                        @php
                            // Cek apakah url foto diawali dengan http (dari Supabase) atau dari storage lokal lama
                            $fotoUrl = str_starts_with($pengajuan->foto, 'http') 
                                    ? $pengajuan->foto 
                                    : asset('storage/' . $pengajuan->foto);
                        @endphp
                        
                        <img src="{{ $fotoUrl }}" alt="Foto Buku" class="w-full h-auto rounded-lg border border-gray-200 object-cover shadow-sm max-h-80"
                             onerror="this.onerror=null;this.src='https://via.placeholder.com/400x500?text=Gambar+Rusak+Atau+Hilang';">
                        
                        <!-- Overlay klik untuk buka di tab baru -->
                        <a href="{{ $fotoUrl }}" target="_blank" class="absolute inset-0 m-4 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 rounded-lg flex items-center justify-center">
                            <span class="opacity-0 group-hover:opacity-100 text-white font-medium flex items-center gap-2 bg-black bg-opacity-50 px-3 py-1.5 rounded-lg text-sm">
                                Buka Penuh
                            </span>
                        </a>
                    @else
                        <!-- Jika dari awal memang tidak upload foto -->
                        <div class="text-center p-8 border-2 border-dashed border-gray-200 rounded-lg w-full">
                            <svg class="mx-auto h-10 w-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-xs text-gray-500 font-medium">Tidak ada foto<br>yang dilampirkan.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card Aksi (Hanya muncul jika masih menunggu verifikasi) -->
            @if(strtolower($pengajuan->status) == 'menunggu_verifikasi')
            <div class="bg-white border border-purple-200 rounded-xl shadow-sm p-6 ring-1 ring-purple-100">
                <h3 class="text-sm font-bold text-gray-800 mb-4 text-center">Tindakan Validasi</h3>
                <div class="flex flex-col gap-3">
                    <form action="{{ route('admin.validasi.update', $pengajuan->id) }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="status" value="disetujui">
                        <button type="submit" onclick="return confirm('Setujui perpanjangan untuk buku ini?')" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Setujui Perpanjangan
                        </button>
                    </form>

                    <form action="{{ route('admin.validasi.update', $pengajuan->id) }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="status" value="ditolak">
                        <button type="submit" onclick="return confirm('Tolak perpanjangan buku ini?')" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Tolak Pengajuan
                        </button>
                    </form>
                </div>
            </div>
            @else
            <!-- Kalau sudah divalidasi, tampilkan info ini -->
            <div class="bg-gray-100 border border-gray-200 rounded-xl shadow-sm p-6 text-center">
                <p class="text-sm text-gray-600 font-medium">Pengajuan ini sudah diproses dan bersatus <br><span class="font-bold text-gray-800">{{ strtoupper($pengajuan->status) }}</span></p>
            </div>
            @endif

        </div>
    </div>

@endsection