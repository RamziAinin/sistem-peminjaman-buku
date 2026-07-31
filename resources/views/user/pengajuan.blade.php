@extends('layouts.main')

@section('content')

<!-- ========================================================== -->
<!-- MODAL POP-UP NOTIFIKASI (MUNCUL JIKA ADA SUCCESS ATAU ERROR) -->
<!-- ========================================================== -->
@if(session('success') || session('error'))
<div id="popup-notif" class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black bg-opacity-60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative transform transition-all scale-100">
        
        <!-- Tombol Silang (Tutup) -->
        <button onclick="tutupPopUp()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="text-center">
            @if(session('success'))
                <!-- Ikon Sukses -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4 shadow-sm">
                    <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Pengajuan Berhasil! 🎉</h3>
                <p class="text-sm md:text-base text-gray-600 mb-6 leading-relaxed">{{ session('success') }}</p>
            @endif

            @if(session('error'))
                <!-- Ikon Error -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4 shadow-sm">
                    <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Pengajuan Gagal</h3>
                <p class="text-sm md:text-base text-gray-600 mb-6 leading-relaxed">{{ session('error') }}</p>
            @endif

            <!-- Tombol Mengerti -->
            <button onclick="tutupPopUp()" class="w-full flex justify-center items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition transform hover:-translate-y-0.5 focus:outline-none">
                Saya Mengerti
            </button>
        </div>
    </div>
</div>
@endif
<!-- ========================================================== -->


<!-- Area Formulir -->
<div class="p-4 md:p-8 max-w-4xl mx-auto w-full">

    <!-- Header Halaman -->
    <div class="mb-6 md:mb-8">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Formulir Perpanjangan Buku</h2>
        <p class="text-sm md:text-base text-gray-500 mt-1">Lengkapi data di bawah ini dengan benar untuk memproses perpanjangan masa pinjam Anda.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 md:p-8">
            
            <form action="{{ route('user.simpan') }}" method="POST" enctype="multipart/form-data" class="space-y-6 md:space-y-8">
                @csrf

                <!-- Section: Data Diri -->
                <div>
                    <h3 class="text-base md:text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informasi Peminjam</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 md:mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-TUA focus:border-TUA outline-none transition bg-gray-50 focus:bg-white text-sm md:text-base">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 md:mb-2">Nomor Kartu Anggota</label>
                            <input type="text" name="nomor_anggota" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-TUA focus:border-TUA outline-none transition bg-gray-50 focus:bg-white text-sm md:text-base">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 md:mb-2">Email Aktif</label>
                            <input type="email" name="email" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-TUA focus:border-TUA outline-none transition bg-gray-50 focus:bg-white text-sm md:text-base">
                        </div>
                    </div>
                </div>

                <!-- Section: Data Buku & Peminjaman -->
                <div class="pt-2 md:pt-4">
                    <h3 class="text-base md:text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Detail Buku & Peminjaman</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 md:mb-2">Barcode Buku</label>
                            <input type="text" name="id_buku" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-TUA focus:border-TUA outline-none transition bg-gray-50 focus:bg-white text-sm md:text-base">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 md:mb-2">Judul Buku</label>
                            <input type="text" name="judul_buku" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-TUA focus:border-TUA outline-none transition bg-gray-50 focus:bg-white text-sm md:text-base">
                        </div>
                        
                        <!-- Tanggal Pinjam -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 md:mb-2">Tanggal Pinjam</label>
                            <input type="date" name="tanggal_pinjam" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-TUA focus:border-TUA outline-none transition bg-gray-50 focus:bg-white text-sm md:text-base">
                        </div>
                        
                        <!-- Upload Foto Buku -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 md:mb-2">Upload Foto Buku (Opsional)</label>
                            <div class="mt-1 flex justify-center px-4 md:px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition relative">
                                
                                <!-- Tampilan Awal (Icon & Teks) -->
                                <div class="space-y-1 text-center" id="upload-ui">
                                    <svg class="mx-auto h-10 w-10 md:h-12 md:w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex flex-col md:flex-row text-sm text-gray-600 justify-center items-center gap-1">
                                        <label for="foto" class="relative cursor-pointer bg-transparent rounded-md font-medium text-blue-600 hover:text-purple-500 focus-within:outline-none px-1">
                                            <span>Pilih file</span>
                                            <input id="foto" name="foto" type="file" class="sr-only" accept="image/*" onchange="tampilkanPreview(event)">
                                        </label>
                                        <p class="md:pl-1">atau seret ke sini</p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 md:mt-0">PNG, JPG up to 10MB</p>
                                </div>

                                <!-- Tampilan Preview Foto -->
                                <div id="preview-container" class="hidden flex-col items-center justify-center w-full">
                                    <img id="previewGambar" src="#" alt="Preview Foto" class="max-h-40 md:max-h-48 w-auto rounded-lg shadow-sm object-cover mb-3">
                                    <button type="button" onclick="hapusPreview()" class="text-xs md:text-sm bg-white border border-gray-300 px-3 py-1.5 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-red-500 transition font-medium">
                                        Ganti Foto
                                    </button>
                                </div>

                            </div>
                            
                            @error('foto')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-4 md:pt-6">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 bg-TUA hover:bg-biru text-white font-bold py-3 md:py-3.5 px-4 rounded-xl shadow-md transition transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 text-sm md:text-base">
                        Kirim Pengajuan Perpanjangan
                    </button>
                </div>
                
            </form>
        </div>
    </div>
</div>

<!-- JavaScript untuk Handle Preview Gambar & Pop-up -->
<script>
    // FUNGSI UNTUK MENUTUP POP-UP
    function tutupPopUp() {
        const popup = document.getElementById('popup-notif');
        if(popup) {
            popup.style.display = 'none';
        }
    }

    // FUNGSI UNTUK PREVIEW GAMBAR
    function tampilkanPreview(event) {
        const input = event.target;
        const previewContainer = document.getElementById('preview-container');
        const preview = document.getElementById('previewGambar');
        const uploadUi = document.getElementById('upload-ui');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                previewContainer.classList.add('flex');
                uploadUi.classList.add('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function hapusPreview() {
        const input = document.getElementById('foto');
        const previewContainer = document.getElementById('preview-container');
        const preview = document.getElementById('previewGambar');
        const uploadUi = document.getElementById('upload-ui');

        input.value = ''; 
        preview.src = '#';
        
        previewContainer.classList.add('hidden');
        previewContainer.classList.remove('flex');
        uploadUi.classList.remove('hidden');
    }
</script>
@endsection