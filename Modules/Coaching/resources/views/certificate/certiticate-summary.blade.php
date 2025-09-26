<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ringkasan Sertifikat Coaching</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0.3in;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        .certificate-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border: 3px solid #2c3e50;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .certificate-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #3498db, #2ecc71, #f39c12, #e74c3c);
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 15px;
        }

        .certificate-title {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .certificate-subtitle {
            font-size: 16px;
            color: #7f8c8d;
            margin: 8px 0 0 0;
            font-style: italic;
        }

        .certificate-number {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 12px;
            color: #7f8c8d;
            font-weight: bold;
        }

        .content-section {
            margin: 20px 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 12px;
            text-align: center;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: #3498db;
        }

        .coaching-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .coaching-table thead {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .coaching-table th {
            padding: 10px 8px;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .coaching-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 11px;
        }

        .coaching-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .coaching-table tbody tr:nth-child(odd) {
            background-color: white;
        }

        .coaching-table tbody tr:hover {
            background-color: #e8f4fd;
            transition: background-color 0.3s ease;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .signature-section {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 2px solid #bdc3c7;
        }

        .signature-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 15px;
        }

        .signature-block {
            text-align: center;
            flex: 1;
            max-width: 200px;
        }

        .signature-line {
            width: 120px;
            height: 1px;
            background-color: #2c3e50;
            margin: 30px auto 8px;
            position: relative;
        }

        .signature-line::after {
            content: '✕';
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            background-color: white;
            padding: 0 5px;
            color: #2c3e50;
            font-size: 12px;
        }

        .signature-name {
            font-size: 12px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 5px;
        }

        .signature-title {
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 2px;
        }

        .signature-location {
            font-size: 9px;
            color: #95a5a6;
            margin-top: 10px;
        }

        .qr-code-section {
            text-align: center;
            margin: 15px 0;
        }

        .qr-code {
            width: 60px;
            height: 60px;
            border: 2px solid #3498db;
            border-radius: 5px;
            padding: 3px;
        }

        .footer-text {
            text-align: center;
            font-size: 9px;
            color: #95a5a6;
            margin-top: 15px;
            font-style: italic;
        }

        .highlight-box {
            background: linear-gradient(135deg, #e8f4fd, #f0f9ff);
            border-left: 4px solid #3498db;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }

        .total-hours {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: #e74c3c;
            margin-top: 8px;
        }

        @media print {
            body {
                background-color: white;
            }

            .certificate-container {
                box-shadow: none;
                border: 2px solid #2c3e50;
            }
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <div class="certificate-number">
            Certificate No: [nomor_sertifikat]
        </div>

        <div class="certificate-header">
            <h1 class="certificate-title">Sertifikat Penyelesaian</h1>
            <p class="certificate-subtitle">Ringkasan Program Coaching</p>
        </div>

        <div class="content-section">
            <h2 class="section-title">Sesi Coaching yang Telah Diselesaikan</h2>

            <table class="coaching-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">No</th>
                        <th style="width: 70%;">Judul Sesi</th>
                        <th style="width: 20%;">Durasi (Jam)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sessions as $chapter)
                        <tr>
                            <td class="text-center font-bold">{{ $loop->iteration }}</td>
                            <td class="text-left">{{ $chapter->title }}</td>
                            <td class="text-right font-bold">{{ $chapter->jp }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="highlight-box">
                <div class="total-hours">
                    Total Jam Coaching: <span
                        id="total-hours">{{ array_sum(collect($sessions)->pluck('jp')->toArray()) }}</span> Jam
                </div>
            </div>
        </div>

        <div class="signature-section">
            <div class="qr-code-section">
                <img src="{{ $qrcodeData2 }}" alt="QR Code" class="qr-code">
            </div>

            <div class="signature-content">
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signature-name">Peserta</div>
                    <div class="signature-title">[nama_peserta]</div>
                    <div class="signature-location">[tanggal_sertifikat]</div>
                </div>

                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signature-name">[nama_kepala_opd]</div>
                    <div class="signature-title">[nama_jabatan]</div>
                    <div class="signature-location">Kabupaten Bantul</div>
                </div>
            </div>
        </div>

        <div class="footer-text">
            Sertifikat ini dikeluarkan untuk penyelesaian program coaching dan berlaku untuk keperluan pengembangan
            profesional.
        </div>
    </div>
</body>

</html>
