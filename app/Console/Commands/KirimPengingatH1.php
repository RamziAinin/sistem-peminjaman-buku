<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PengingatH1Mail;
use Illuminate\Support\Facades\Log;

class KirimPengingatH1 extends Command
{
    // Ini nama perintah yang nanti diketik di terminal / cPanel
    protected $signature = 'perpus:pengingat-h1';
    protected $description = 'Kirim email pengingat H-1 sebelum batas waktu pengembalian buku.';

    public function handle()
    {
        // Cari tanggal besok
        $besok = Carbon::tomorrow()->format('Y-m-d');

        // Cari semua data yang disetujui DAN tanggal kembalinya = besok
        $targetPeminjam = Peminjaman::where('status', 'disetujui')
                                    ->whereDate('tanggal_kembali', $besok)
                                    ->get();

        $jumlahTerkirim = 0;

        foreach ($targetPeminjam as $peminjaman) {
            try {
                Mail::to($peminjaman->email)->send(new PengingatH1Mail($peminjaman));
                $jumlahTerkirim++;
            } catch (\Exception $e) {
                Log::error('Gagal kirim pengingat ke: ' . $peminjaman->email . ' - ' . $e->getMessage());
            }
        }

        $this->info("Berhasil mengirim {$jumlahTerkirim} email pengingat H-1.");
    }
}