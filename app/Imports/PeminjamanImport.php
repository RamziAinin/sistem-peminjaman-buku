<?php

namespace App\Imports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class PeminjamanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Lewati jika kolom nama kosong
        if (!isset($row['nama'])) {
            return null;
        }

        // ==========================================
        // LOGIKA PENGECEKAN STATUS EXCEL
        // ==========================================
        $cekVerifikasi = strtolower($row['verifikasi_tindak_lanjut'] ?? '');
        
        if (str_contains($cekVerifikasi, 'sudah')) {
            // Jika ada kata 'sudah', status disetujui
            $statusPeminjaman = 'disetujui';
        } elseif (str_contains($cekVerifikasi, 'belum')) {
            // Jika ada kata 'belum', anggap saja ditolak
            $statusPeminjaman = 'ditolak';
        } elseif (str_contains($cekVerifikasi, 'tidak')) {
            // Jika ada kata 'tidak diproses', status jadi tidak_terproses
            $statusPeminjaman = 'tidak_terproses';
        } else {
            // Jika kosong, baru masuk ke antrean validasi
            $statusPeminjaman = 'menunggu_verifikasi';
        }

        return new Peminjaman([
            'nama'            => $row['nama'],
            'nomor_anggota'   => $row['no_kartu_anggota'] ?? '000000',
            'email'           => $row['email_aktif'] ?? '-',
            'judul_buku'      => $row['judul_buku'] ?? 'Tidak Diketahui',
            'id_buku'         => $row['barcode_buku'] ?? '00000',
            'foto'            => 'foto_buku/default.png',
            'tanggal_pinjam'  => Carbon::now(),
            'tanggal_kembali' => Carbon::now()->addDays(7),
            'status'          => $statusPeminjaman,
        ]);
    }
}