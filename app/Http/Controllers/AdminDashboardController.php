<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PeminjamanImport;

// WAJIB DITAMBAHKAN UNTUK FITUR KIRIM EMAIL
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifikasiPeminjamanMail;

class AdminDashboardController extends Controller
{
    // ==========================================
    // 1. DASHBOARD UTAMA
    // ==========================================
    public function index()
    {
        // Hitung pengajuan yang menunggu konfirmasi
        $requestPerpanjangan = Peminjaman::where('status', 'menunggu_verifikasi')->count();

        // Hitung buku yang tanggal kembalinya sudah lewat dari hari ini
        $bukuTerlambat = Peminjaman::whereDate('tanggal_kembali', '<', Carbon::today())->count();

        // Ambil 5 data riwayat terbaru untuk ditampilkan di tabel mini dashboard
        $riwayatValidasi = Peminjaman::whereIn('status', ['disetujui', 'ditolak'])
                                     ->orderBy('updated_at', 'desc')
                                     ->take(5)
                                     ->get();

        return view('admin.dashboard', compact('requestPerpanjangan', 'bukuTerlambat', 'riwayatValidasi'));
    }

    // ==========================================
    // 2. HALAMAN VALIDASI & PENCARIAN
    // ==========================================
    public function validasi(Request $request)
    {
        $search = $request->input('search');
        
        $query = Peminjaman::where('status', 'menunggu_verifikasi');

        // Fitur Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('id_buku', 'like', "%{$search}%")
                  ->orWhere('judul_buku', 'like', "%{$search}%")
                  ->orWhere('nomor_anggota', 'like', "%{$search}%");
            });
        }

        // Tampilkan 10 data per halaman (Pagination)
        $pengajuan = $query->orderBy('created_at', 'asc')->paginate(10);
        
        // Simpan keyword pencarian saat pindah halaman
        if ($search) {
            $pengajuan->appends(['search' => $search]);
        }
                                       
        $jumlahPending = Peminjaman::where('status', 'menunggu_verifikasi')->count();

        return view('admin.validasi', compact('pengajuan', 'jumlahPending'));
    }

    // ==========================================
    // 3. HALAMAN DETAIL PERMOHONAN
    // ==========================================
    public function detail($id)
    {
        // Ambil data spesifik berdasarkan ID yang diklik
        $pengajuan = Peminjaman::findOrFail($id);
        
        // Tetap butuh jumlah pending untuk angka notifikasi merah di sidebar
        $jumlahPending = Peminjaman::where('status', 'menunggu_verifikasi')->count();

        return view('admin.validasi-detail', compact('pengajuan', 'jumlahPending'));
    }

    // ==========================================
    // 4. EKSEKUSI TOMBOL SETUJU / TOLAK & KIRIM EMAIL
    // ==========================================
    public function updateValidasi(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Update status sesuai form input hidden (disetujui / ditolak)
        $peminjaman->status = $request->status; 
        $peminjaman->save();

        // Siapkan data untuk dikirim ke Email
        $dataMail = [
            'nama' => $peminjaman->nama,
            'judul_buku' => $peminjaman->judul_buku,
            'id_buku' => $peminjaman->id_buku,
            'status' => $peminjaman->status,
            'tanggal_kembali' => $peminjaman->tanggal_kembali
        ];

        // Eksekusi pengiriman email secara otomatis!
        try {
            Mail::to($peminjaman->email)->send(new NotifikasiPeminjamanMail($dataMail));
            $pesanEmail = " dan Email notifikasi telah terkirim!";
        } catch (\Exception $e) {
            $pesanEmail = " namun Email gagal terkirim karena masalah server/koneksi.";
        }

        return back()->with('success', 'Status pengajuan berhasil diubah menjadi: ' . strtoupper($request->status) . $pesanEmail);
    }

    // ==========================================
    // 5. HALAMAN RIWAYAT & FILTER TAB
    // ==========================================
    public function riwayat(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter');
        
        // TAMBAHKAN 'dikembalikan' KE DALAM ARRAY STATUS RIWAYAT
        $statusRiwayat = ['disetujui', 'ditolak', 'tidak_terproses', 'dikembalikan'];

        $countAll = Peminjaman::whereIn('status', $statusRiwayat)->count();
        $countDisetujui = Peminjaman::where('status', 'disetujui')->count();
        $countDitolak = Peminjaman::where('status', 'ditolak')->count();
        // Hitung juga jumlah buku yang sudah dikembalikan untuk ditampilkan di Tab Baru
        $countDikembalikan = Peminjaman::where('status', 'dikembalikan')->count();

        $query = Peminjaman::whereIn('status', $statusRiwayat);

        // Jika salah satu Tab diklik (termasuk tab dikembalikan)
        if ($filter && in_array($filter, ['disetujui', 'ditolak', 'dikembalikan'])) {
            $query->where('status', $filter);
        }

        // Fitur pencarian riwayat
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('id_buku', 'like', "%{$search}%")
                  ->orWhere('judul_buku', 'like', "%{$search}%")
                  ->orWhere('nomor_anggota', 'like', "%{$search}%");
            });
        }

        $riwayat = $query->orderBy('updated_at', 'desc')->paginate(10);
        $riwayat->appends(['search' => $search, 'filter' => $filter]);

        // Kirim variabel $countDikembalikan ke View agar angka di tab muncul
        return view('admin.riwayat', compact('riwayat', 'countAll', 'countDisetujui', 'countDitolak', 'countDikembalikan', 'filter'));
    }

    public function tandaiDikembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Kita ubah semua riwayat buku ini milik user ini yang statusnya 'disetujui' menjadi 'dikembalikan'
        Peminjaman::where('nomor_anggota', $peminjaman->nomor_anggota)
                  ->where('id_buku', $peminjaman->id_buku)
                  ->where('status', 'disetujui')
                  ->update(['status' => 'dikembalikan']);

        return redirect()->back()->with('success', 'Buku telah ditandai dikembalikan. Kuota perpanjangan berhasil di-reset!');
    }

    // ==========================================
    // 6. HALAMAN UPLOAD DATABASE
    // ==========================================
    public function uploadDatabase()
    {
        return view('admin.upload-database');
    }

    // ==========================================
    // 7. PROSES IMPORT FILE EXCEL SIPOKU
    // ==========================================
    public function processUpload(Request $request)
    {
        $request->validate([
            'file_database' => 'required|file|mimes:csv,txt,xls,xlsx|max:10240', 
        ], [
            'file_database.required' => 'Anda belum memilih file untuk diunggah.',
            'file_database.mimes'    => 'Format file harus berupa CSV atau Excel.',
            'file_database.max'      => 'Ukuran file maksimal adalah 10 MB.'
        ]);

        $file = $request->file('file_database');

        try {
            Excel::import(new \App\Imports\PeminjamanImport, $file);
            return back()->with('success', 'Berhasil! Data dari file ' . $file->getClientOriginalName() . ' telah masuk ke antrean validasi.');
        } catch (\Exception $e) {
            return back()->withErrors(['file_database' => 'Gagal membaca isi file. Pastikan format kolom sesuai dengan template SIPOKU. (Detail: ' . $e->getMessage() . ')']);
        }
    }
}