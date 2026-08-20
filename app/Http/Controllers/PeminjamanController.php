<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; // INI WAJIB DITAMBAHIN BIAR SUPABASE JALAN

class PeminjamanController extends Controller
{
    public function formPengajuan()
    {
        return view('user.pengajuan'); // sesuaikan dengan nama view form kamu
    }

    public function simpan(Request $request)
    {
        // ==========================================
        // 1. VALIDASI INPUT DARI FORM (EMAIL DIPERKETAT)
        // ==========================================
        $request->validate([
            'nama'            => 'required|string|max:255',
            'nomor_anggota'   => 'required|string|max:50',
            // Pakai rfc,dns untuk memastikan domain email benar-benar ada
            'email'           => 'required|email:rfc,dns|max:255', 
            'id_buku'         => 'required|string|max:50',
            'judul_buku'      => 'required|string|max:255',
            'tanggal_pinjam'  => 'required|date',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg|max:10240', 
        ], [
            'email.email' => 'Format penulisan email tidak valid!',
            'email.rfc'   => 'Penulisan email tidak memenuhi standar penulisan yang benar!',
            'email.dns'   => 'Domain email tidak ditemukan! Pastikan email aktif dan penulisannya benar.',
            'foto.image'  => 'File yang diunggah harus berupa gambar!',
            'foto.mimes'  => 'Format gambar hanya boleh JPG, JPEG, atau PNG!',
            'foto.max'    => 'Ukuran foto maksimal adalah 10 MB!',
        ]);

        // ==========================================
        // 2. CEK BATAS MAKSIMAL & ANTI SPAM
        // ==========================================
        $riwayatBukuIni = Peminjaman::where('nomor_anggota', $request->nomor_anggota)
                                    ->where('id_buku', $request->id_buku)
                                    ->get();

        // A. Cek apakah masih ada pengajuan yang antre
        $sedangAntre = $riwayatBukuIni->where('status', 'menunggu_verifikasi')->count();
        if ($sedangAntre > 0) {
            return redirect()->back()->with('error', 'Pengajuan ditolak: Buku ini sedang dalam antrean verifikasi admin. Mohon tunggu 1x24 jam.');
        }

        // B. Cek apakah sudah pernah diperpanjang 2x
        $jumlahDisetujui = $riwayatBukuIni->where('status', 'disetujui')->count();
        if ($jumlahDisetujui >= 2) {
            return redirect()->back()->with('error', 'Pengajuan ditolak: Anda sudah mencapai batas maksimal perpanjangan (2 kali) untuk buku ini. Harap segera kembalikan ke perpustakaan.');
        }

        // ==========================================
        // 3. CEK KETERLAMBATAN WAKTU PINJAM
        // ==========================================
        $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
        $batasKembali = $tanggalPinjam->copy()->addDays(7); 
        $hariIni = Carbon::now()->startOfDay();

        if ($hariIni->greaterThan($batasKembali)) {
            return redirect()->back()->with('error', 'Maaf pengajuan perpanjangan gagal dikarenakan sudah melebihi masa waktu pinjam. Mohon segera kembalikan buku.');
        }

        // ==========================================
        // 4. PROSES UPLOAD FOTO KE SUPABASE (JALUR BYPASS)
        // ==========================================
        $pathFoto = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Upload ke Supabase (disk S3)
            Storage::disk('s3')->put($filename, file_get_contents($file));
            
            // Hardcode URL Public Supabase (Pastikan ID Project lu udah benar)
            $pathFoto = 'https://qniahxitvogjnxzleiug.supabase.co/storage/v1/object/public/foto_buku/' . $filename;
        }

        // ==========================================
        // 5. SIMPAN DATA KE DATABASE
        // ==========================================
        Peminjaman::create([
            'nama'            => $request->nama,
            'nomor_anggota'   => $request->nomor_anggota,
            'email'           => $request->email,
            'id_buku'         => $request->id_buku,
            'judul_buku'      => $request->judul_buku,
            'tanggal_pinjam'  => $request->tanggal_pinjam,
            'tanggal_kembali' => $batasKembali->format('Y-m-d'),
            'foto'            => $pathFoto, 
            'status'          => 'menunggu_verifikasi', 
        ]);

        // ==========================================
        // 6. KEMBALIKAN NOTIFIKASI SUKSES
        // ==========================================
        $pesanSukses = "Permohonan perpanjangan waktu sudah diajukan dan akan diverifikasi maksimal 1x24 jam. Konfirmasi akan dikirimkan ke email: {$request->email}";

        return redirect()->back()->with('success', $pesanSukses);
    }
}