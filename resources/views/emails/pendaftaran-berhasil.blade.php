<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #1a1a2e;
            background-color: #f0f7fc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #0066b2;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .summary-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .summary-table td:first-child {
            font-weight: bold;
            color: #6b7280;
            width: 40%;
        }
        .summary-table tr:last-child td {
            border-bottom: none;
        }
        .qr-section {
            text-align: center;
            margin: 30px 0;
        }
        .kode-registrasi {
            font-size: 20px;
            font-weight: bold;
            color: #0066b2;
            margin-bottom: 10px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bukti Pendaftaran Khitanan Massal</h1>
            <p style="margin: 5px 0 0;">Dareliman Peduli &times; Surau TV</p>
        </div>
        
        <div class="content">
            <p>Assalamu'alaikum Warahmatullahi Wabarakatuh,</p>
            <p>Alhamdulillah, pendaftaran Khitanan Massal untuk Ananda <strong>{{ $pendaftaran->nama_lengkap }}</strong> telah berhasil kami terima.</p>
            
            <div class="summary-box">
                <table class="summary-table">
                    <tr>
                        <td>Nama Peserta</td>
                        <td>{{ $pendaftaran->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td>Jenjang</td>
                        <td>{{ $pendaftaran->jenjang }} {{ $pendaftaran->kelas ? '- Kelas ' . $pendaftaran->kelas : '' }}</td>
                    </tr>
                    <tr>
                        <td>Kode Registrasi</td>
                        <td><strong>{{ $pendaftaran->kode_registrasi }}</strong></td>
                    </tr>
                </table>
            </div>

            <div class="qr-section">
                <div class="kode-registrasi">{{ $pendaftaran->kode_registrasi }}</div>
                @if($qrPath)
                    <img src="{{ $message->embed($qrPath) }}" alt="QR Code Registrasi" width="200" height="200">
                @else
                    <p style="color: #d97706;">QR Code tidak dapat dilampirkan, harap gunakan Kode Registrasi saat check-in.</p>
                @endif
                <p style="margin-top: 15px; font-size: 14px; color: #6b7280;">Tunjukkan QR Code ini kepada panitia saat registrasi ulang / check-in di lokasi acara.</p>
            </div>

            <h3>Informasi Acara (Contoh/Placeholder)</h3>
            <ul>
                <li><strong>Hari/Tanggal:</strong> Akan diinformasikan menyusul</li>
                <li><strong>Waktu:</strong> 07.00 WIB - Selesai</li>
                <li><strong>Tempat:</strong> Komplek Sekolah Dareliman</li>
            </ul>

            <p>Jika ada pertanyaan lebih lanjut, silakan hubungi narahubung Panitia Khitanan Massal Dareliman Peduli.</p>
            
            <p>Jazakumullahu Khairan,<br>
            <strong>Panitia Khitanan Massal</strong></p>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} Dareliman Peduli &times; Surau TV. Semua hak cipta dilindungi.
        </div>
    </div>
</body>
</html>
