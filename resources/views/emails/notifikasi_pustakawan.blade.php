<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notifikasi Pengajuan Baru</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 20px; margin: 0;">
    
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-top: 6px solid #2563eb;">
        
        <h2 style="color: #1f2937; margin-top: 0;">Halo, {{ $namaPustakawan ?? 'Pustakawan' }}! 📬</h2>
        <p style="color: #4b5563; line-height: 1.6; font-size: 16px;">
            Terdapat pengajuan perpanjangan masa pinjam buku baru yang memerlukan verifikasi Anda di sistem admin.
        </p>

        <div style="background-color: #f3f4f6; padding: 20px; border-radius: 8px; margin: 25px 0;">
            <p style="margin: 0 0 10px 0; color: #374151; font-size: 15px;">
                <strong>Nama Pemohon:</strong><br>
                {{ $dataPeminjaman['nama'] }}
            </p>
            
            <p style="margin: 0 0 10px 0; color: #374151; font-size: 15px;">
                <strong>Judul Buku:</strong><br>
                {{ $dataPeminjaman['judul_buku'] }} (ID: {{ $dataPeminjaman['id_buku'] }})
            </p>

            <p style="margin: 0; color: #374151; font-size: 15px;">
                <strong>Tanggal Batas Kembali (Jika Disetujui):</strong><br>
                <span style="color: #2563eb; font-weight: bold;">
                    {{ $dataPeminjaman['tanggal_kembali'] }}
                </span>
            </p>
        </div>

        <p style="color: #4b5563; font-size: 15px;">
            Silakan login ke <strong>Dashboard Admin</strong> untuk melihat detail foto buku dan memproses (Setuju/Tolak) pengajuan ini.
        </p>

        <p style="color: #9ca3af; font-size: 13px; margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 15px;">
            Pesan ini dikirimkan secara otomatis dari Sistem Validasi Perpustakaan.
        </p>
    </div>

</body>
</html>