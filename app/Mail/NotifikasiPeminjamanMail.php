<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiPeminjamanMail extends Mailable
{
    use Queueable, SerializesModels;

    // 1. Bikin wadah untuk nyimpen data yang dikirim dari Controller
    public $dataMail;

    /**
     * Create a new message instance.
     * @return void
     */
    public function __construct($dataMail)
    {
        // 2. Masukkan data ke dalam wadah
        $this->dataMail = $dataMail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // 3. Ubah Judul (Subject) Email yang akan muncul di HP/Inbox Peminjam
            subject: 'Informasi Status Perpanjangan Buku',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // 4. Arahkan ke file template HTML email yang tadi kita bikin
            view: 'emails.notifikasi',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}