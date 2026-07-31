<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function formPengajuan()
    {
        return view('user.pengajuan'); // atau view('user.form'), sesuaikan dengan nama view form kamu
    }

    public function simpanPengajuan(Request $request)
    {
        // ==========================================
        // 1. VALIDASI INPUT DARI FORM
        // ==========================================
        $request->validate([
            'nama'            => 'required|string|max:255',
            'nomor_anggota'   => 'required|string|max:50',
            'email'           => 'required|email|max:255',
            'id_buku'         => 'required|string|max:50',
            'judul_buku'      => 'required|string|max:255',
            'tanggal_pinjam'  => 'required|date',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg|max:10240', 
        ], [
            'foto.image' => 'File yang diunggah harus berupa gambar!',
            'foto.mimes' => 'Format gambar hanya boleh JPG, JPEG, atau PNG!',
            'foto.max'   => 'Ukuran foto maksimal adalah 10 MB!',
        ]);

        // ==========================================
        // 2. CEK KETERLAMBATAN (BLOKIR JIKA TELAT)
        // ==========================================
        $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
        $batasKembali = $tanggalPinjam->copy()->addDays(7); 
        $hariIni = Carbon::now()->startOfDay();

        // Jika hari ini LEBIH DARI batas kembali (sudah telat)
        if ($hariIni->greaterThan($batasKembali)) {
            return redirect()->back()->with('error', 'Maaf pengajuan perpanjangan gagal dikarenakan sudah melebihi masa waktu pinjam, mohon segera mengembalikan buku.');
        }

        // ==========================================
        // 3. PROSES UPLOAD FOTO (JIKA AMAN)
        // ==========================================
        $pathFoto = null;
        if ($request->hasFile('foto')) {
            $pathFoto = $request->file('foto')->store('foto_buku', 'public');
        }

        // ==========================================
        // 4. SIMPAN DATA KE DATABASE
        // ==========================================
        Peminjaman::create([
            'nama'            => $request->nama,
            'nomor_anggota'   => $request->nomor_anggota,
            'email'           => $request->email,
            'id_buku'         => $request->id_buku,
            'judul_buku'      => $request->judul_buku,
            'tanggal_pinjam'  => $request->tanggal_pinjam,
            'tanggal_kembali' => $batasKembali->format('Y-m-d'), // Langsung pakai hasil hitungan dari langkah 2
            'foto'            => $pathFoto, 
            'status'          => 'menunggu_verifikasi', 
        ]);

        // ==========================================
        // 5. KEMBALIKAN NOTIFIKASI SUKSES
        // ==========================================
        $pesanSukses = "Permohonan perpanjangan waktu sudah diajukan dan akan diverifikasi selama 1x24 jam. Konfirmasi akan dikirimkan ke email: {$request->email}";

        return redirect()->back()->with('success', $pesanSukses);
    }
}