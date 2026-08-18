<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PeminjamanImport;

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
    // 4. EKSEKUSI TOMBOL SETUJU / TOLAK
    // ==========================================
    public function updateValidasi(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Update status sesuai form input hidden (disetujui / ditolak)
        $peminjaman->status = $request->status; 
        $peminjaman->save();

        return back()->with('success', 'Status pengajuan berhasil diubah menjadi: ' . strtoupper($request->status));
    }

    // ==========================================
    // 5. HALAMAN RIWAYAT & FILTER TAB
    // ==========================================
    public function riwayat(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter');
        
        // TAMBAHKAN 'tidak_terproses' KE DALAM ARRAY STATUS RIWAYAT
        $statusRiwayat = ['disetujui', 'ditolak', 'tidak_terproses'];

        $countAll = Peminjaman::whereIn('status', $statusRiwayat)->count();
        $countDisetujui = Peminjaman::where('status', 'disetujui')->count();
        $countDitolak = Peminjaman::where('status', 'ditolak')->count();

        $query = Peminjaman::whereIn('status', $statusRiwayat);

        // Jika salah satu Tab (Disetujui / Ditolak) diklik
        if ($filter && in_array($filter, ['disetujui', 'ditolak'])) {
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

        return view('admin.riwayat', compact('riwayat', 'countAll', 'countDisetujui', 'countDitolak', 'filter'));
    }
    public function tandaiDikembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // KUNCI SAKTINYA DI SINI:
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
        // Validasi HANYA file (kategori sudah dihapus)
        $request->validate([
            'file_database' => 'required|file|mimes:csv,txt,xls,xlsx|max:10240', 
        ], [
            'file_database.required' => 'Anda belum memilih file untuk diunggah.',
            'file_database.mimes'    => 'Format file harus berupa CSV atau Excel.',
            'file_database.max'      => 'Ukuran file maksimal adalah 10 MB.'
        ]);

        $file = $request->file('file_database');

        try {
            // Eksekusi package Laravel-Excel
            Excel::import(new \App\Imports\PeminjamanImport, $file);
            
            return back()->with('success', 'Berhasil! Data dari file ' . $file->getClientOriginalName() . ' telah masuk ke antrean validasi.');
            
        } catch (\Exception $e) {
            return back()->withErrors(['file_database' => 'Gagal membaca isi file. Pastikan format kolom sesuai dengan template SIPOKU. (Detail: ' . $e->getMessage() . ')']);
        }
    }
}