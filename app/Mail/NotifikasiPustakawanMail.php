<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiPustakawanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dataPeminjaman;
    public $namaPustakawan;

    public function __construct($dataPeminjaman, $namaPustakawan)
    {
        $this->dataPeminjaman = $dataPeminjaman;
        $this->namaPustakawan = $namaPustakawan;
    }

    public function build()
    {
        return $this->subject('Pengajuan Perpanjangan Baru - ' . $this->dataPeminjaman['nama'])
                    ->view('emails.notifikasi_pustakawan');
    }
}