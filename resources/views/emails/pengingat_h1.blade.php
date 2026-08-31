<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 12px; border-top: 6px solid #eab308;">
        <h2 style="color: #1f2937; margin-top: 0;">Halo, {{ $peminjaman->nama }}! ⚠️</h2>
        <p style="color: #4b5563; font-size: 16px;">
            Email ini adalah pengingat otomatis dari Perpustakaan bahwa batas waktu peminjaman buku Anda akan berakhir <strong>BESOK</strong>.
        </p>

        <div style="background-color: #fef9c3; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0 0 10px 0; color: #854d0e; font-size: 15px;"><strong>Judul Buku:</strong><br>{{ $peminjaman->judul_buku }}</p>
            <p style="margin: 0 0 10px 0; color: #854d0e; font-size: 15px;"><strong>ID Buku:</strong><br>{{ $peminjaman->id_buku }}</p>
            <p style="margin: 0; color: #854d0e; font-size: 15px;">
                <strong>Batas Akhir Pengembalian:</strong><br>
                <span style="font-weight: bold; font-size: 18px;">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d F Y') }}</span>
            </p>
        </div>

        <p style="color: #dc2626; font-size: 14px; font-weight: bold;">
            Mohon segera kembalikan buku fisik ke perpustakaan untuk menghindari sanksi keterlambatan.
        </p>
    </div>
</body>
</html>