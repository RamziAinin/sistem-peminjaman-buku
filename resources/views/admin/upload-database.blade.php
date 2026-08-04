@extends('layouts.admin')

@section('title', 'Upload Database')
@section('header_title', 'Sinkronisasi Database Perpustakaan')

@section('content')

    <!-- Area Konten (Lebar disesuaikan desainmu) -->
    <div class="max-w-4xl mx-auto w-full">

        <!-- Title & Info Box -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Upload Data SIPOKU via Excel</h2>
            <p class="text-sm text-gray-500 mt-1">Impor data riwayat peminjaman secara massal langsung ke dalam database sistem perpustakaan.</p>
        </div>

        <!-- Alert Sukses -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm mb-6 flex items-start gap-3">
                <svg class="w-6 h-6 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h4 class="font-bold">Berhasil!</h4>
                    <p class="text-sm mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Alert Error -->
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm mb-6 flex items-start gap-3">
                <svg class="w-6 h-6 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h4 class="font-bold">Gagal Mengunggah</h4>
                    <ul class="list-disc ml-5 mt-1 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Card Upload Utama -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">
            
            <form action="{{ route('admin.upload.process') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Drag and Drop Box -->
                <div class="border-2 border-dashed border-gray-300 hover:border-blue-500 rounded-2xl p-12 text-center flex flex-col items-center justify-center bg-gray-50 hover:bg-gray-100 transition cursor-pointer relative group">
                    <!-- Input File (Wajib disembunyikan tapi bisa diklik) -->
                    <input type="file" name="file_database" accept=".xlsx, .xls, .csv" required
                        class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" id="file-upload" onchange="showFileName()">
                    
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition transform duration-200">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                    </div>
                    
                    <p class="text-base font-bold text-gray-700" id="file-name-text">Seret & Letakkan file Excel di sini</p>
                    <p class="text-sm text-gray-400 mt-1" id="file-instruction">atau <span class="text-blue-600 font-semibold underline">pilih file</span> dari perangkat komputer</p>
                    <p class="text-xs text-gray-400 mt-3">Format yang didukung: .XLSX, .XLS, .CSV (Maks. 10MB)</p>
                </div>

                <!-- Tombol Proses Upload -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="reset" onclick="resetFile()"
                        class="px-6 py-2.5 border border-gray-300 rounded-xl text-gray-600 text-sm font-medium hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <!-- Tipe tombol harus 'submit' biar datanya terkirim -->
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Proses & Masukkan ke Database
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Script simpel biar nama file yang dipilih muncul di UI -->
    <script>
        function showFileName() {
            const input = document.getElementById('file-upload');
            const nameText = document.getElementById('file-name-text');
            const instruction = document.getElementById('file-instruction');
            
            if (input.files && input.files.length > 0) {
                nameText.textContent = "File terpilih: " + input.files[0].name;
                nameText.classList.add("text-purple-700");
                instruction.classList.add("hidden");
            }
        }

        function resetFile() {
            document.getElementById('file-name-text').textContent = "Seret & Letakkan file Excel di sini";
            document.getElementById('file-name-text').classList.remove("text-purple-700");
            document.getElementById('file-instruction').classList.remove("hidden");
        }
    </script>

@endsection