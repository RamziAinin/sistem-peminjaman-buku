<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notifikasi Perpustakaan</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 20px; margin: 0;">
    
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-top: 6px solid #9333EA;">
        
        <!-- Header -->
        <h2 style="color: #1f2937; margin-top: 0;">Halo, {{ $dataMail['nama'] }}! 👋</h2>
        <p style="color: #4b5563; line-height: 1.6; font-size: 16px;">
            Berikut adalah informasi mengenai pengajuan perpanjangan masa pinjam buku Anda di Layanan Perpustakaan Daerah.
        </p>

        <!-- Kotak Detail -->
        <div style="background-color: #f3f4f6; padding: 20px; border-radius: 8px; margin: 25px 0;">
            <p style="margin: 0 0 10px 0; color: #374151; font-size: 15px;">
                <strong>Judul Buku:</strong><br>
                {{ $dataMail['judul_buku'] }} (ID: {{ $dataMail['id_buku'] }})
            </p>
            
            <p style="margin: 0 0 10px 0; color: #374151; font-size: 15px;">
                <strong>Status Pengajuan:</strong><br>
                @if(strtolower($dataMail['status']) == 'disetujui')
                    <span style="color: #15803d; font-weight: bold; font-size: 18px;">✅ DISETUJUI</span>
                @else
                    <span style="color: #dc2626; font-weight: bold; font-size: 18px;">❌ DITOLAK</span>
                @endif
            </p>

            @if(strtolower($dataMail['status']) == 'disetujui')
                <p style="margin: 0; color: #374151; font-size: 15px;">
                    <strong>Batas Pengembalian Baru:</strong><br>
                    <span style="color: #9333EA; font-weight: bold; font-size: 16px;">
                        {{ \Carbon\Carbon::parse($dataMail['tanggal_kembali'])->format('d M Y') }}
                    </span>
                </p>
            @else
                <p style="margin: 0; color: #dc2626; font-size: 14px; background: #fee2e2; padding: 10px; border-radius: 6px;">
                    <strong>Penting:</strong> Mohon segera kembalikan fisik buku ke perpustakaan maksimal pada batas waktu awal Anda untuk menghindari sanksi/denda.
                </p>
            @endif
        </div>

        <!-- Footer -->
        <p style="color: #6b7280; font-size: 14px; line-height: 1.5; border-top: 1px solid #e5e7eb; padding-top: 20px;">
            Email ini dihasilkan otomatis oleh sistem. Harap tidak membalas email ini.<br>
            <strong>&copy; 2026 Layanan Digital Perpustakaan Pekalongan</strong>
        </p>
    </div>

</body>
</html>