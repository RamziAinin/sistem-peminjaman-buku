<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PengingatH1Mail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;

    public function __construct($peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function build()
    {
        return $this->subject('PENGINGAT: Tenggat Pengembalian Buku H-1 (' . $this->peminjaman->judul_buku . ')')
                    ->view('emails.pengingat_h1');
    }
}