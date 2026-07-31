<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    // Pastikan nama tabelnya sesuai
    protected $table = 'peminjamans';

    // Daftarkan semua kolom yang ada di database agar bisa diisi data (Mass Assignment)
    protected $fillable = [
        'nama',
        'nomor_anggota',
        'email',
        'id_buku',
        'judul_buku',
        'foto',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];
}