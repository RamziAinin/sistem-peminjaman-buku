@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('header_title', 'Pengaturan Notifikasi')

@section('content')
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI: FORM TAMBAH -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 lg:col-span-1 h-max">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Tambah Pustakawan</h3>
            <p class="text-xs text-gray-500 mb-5">Tambahkan nama dan email staf yang akan menerima notifikasi otomatis.</p>

            <form action="{{ route('admin.pengaturan.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Pustakawan</label>
                    <input type="text" name="nama" required placeholder="Contoh: Budi Santoso"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-sm">
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Email</label>
                    <input type="email" name="email_pustakawan" required placeholder="budi@perpus.go.id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-sm">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg transition shadow-sm flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Simpan Data
                </button>
            </form>
        </div>

        <!-- KOLOM KANAN: TABEL DAFTAR EMAIL -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden lg:col-span-2">
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Daftar Penerima Notifikasi</h3>
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">{{ $daftarPustakawan->count() }} Terdaftar</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                            <th class="py-3 px-6 font-semibold">Nama Pustakawan</th>
                            <th class="py-3 px-6 font-semibold">Email</th>
                            <th class="py-3 px-6 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @forelse($daftarPustakawan as $item)
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                <td class="py-3 px-6 font-medium text-gray-800">{{ $item->nama ?? 'Tidak ada nama' }}</td>
                                <td class="py-3 px-6 text-gray-600">{{ $item->email_pustakawan }}</td>
                                <td class="py-3 px-6 text-center">
                                    <form action="{{ route('admin.pengaturan.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus email {{ $item->nama }} dari daftar notifikasi?')" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-lg transition" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-gray-500 text-sm">Belum ada email pustakawan yang didaftarkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection