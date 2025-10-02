<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ringkasan Sertifikat Coaching</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1cm;
            background-color: #ff00ff;
        }



        body {
            border: 2px solid #2c3e50;
            /* width: 21mm;
            height: 297mm; */
            /* margin: 0 auto; */
            /* centers content if previewed in browser */
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            /* margin: 0; */
            padding: 0;
            /* background-color: #ff0000; */
            color: #333333;
        }

        .certificate-container {
            /* width: 100%;
            height: 100%; */
            margin: 0 0;
            padding: 20px;
            background-color: #ffffff;
        }

        .certificate-number {
            text-align: right;
            font-size: 11px;
            color: #555555;
            font-weight: bold;
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 18px;
            border-bottom: 1px solid #3498db;
            padding-bottom: 12px;
        }

        .certificate-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
        }

        .certificate-subtitle {
            font-size: 14px;
            color: #555555;
            margin: 5px 0 0 0;
            font-style: italic;
        }

        .content-section {
            margin: 18px 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            text-align: center;
            margin: 0 0 12px 0;
            padding-bottom: 6px;
            border-bottom: 1px solid #3498db;
        }

        .coaching-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
        }

        .coaching-table th,
        .coaching-table td {
            border: 1px solid #d0d5db;
            padding: 6px;
            font-size: 11px;
        }

        .coaching-table thead th {
            background-color: #3498db;
            color: #ffffff;
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

        .highlight-box {
            border: 1px solid #3498db;
            background-color: #eef5fb;
            padding: 8px;
            margin-top: 12px;
        }

        .total-hours {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            color: #333333;
        }

        .signature-section {
            margin-top: 22px;
            padding-top: 15px;
            border-top: 1px solid #bdc3c7;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            text-align: center;
            padding-top: 25px;
        }

        .signature-line {
            border-top: 1px solid #2c3e50;
            width: 140px;
            margin: 0 auto 6px;
        }

        .signature-mark {
            font-size: 10px;
            color: #2c3e50;
            margin-bottom: 4px;
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
            margin-top: 8px;
        }

        .qr-code-section {
            text-align: center;
            margin: 12px 0;
        }

        .qr-code {
            width: 60px;
            height: 60px;
            border: 1px solid #3498db;
            padding: 2px;
        }

        .footer-text {
            text-align: center;
            font-size: 9px;
            color: #7f8c8d;
            margin-top: 15px;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <div class="certificate-number">Certificate No: [nomor_sertifikat]</div>

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
                    Total Jam Coaching: <span id="total-hours">{{ $totalJP }}</span> Jam
                </div>
            </div>
        </div>

        <div class="signature-section">
            <div class="qr-code-section">
                <img src="{{ $qrcodeData2 }}" alt="QR Code" class="qr-code">
            </div>

            <table class="signature-table">
                <tr>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-mark">✕</div>
                        <div class="signature-name">[nama_kepala_opd]</div>
                        <div class="signature-title">[nama_jabatan]</div>
                        <div class="signature-location">Kabupaten Bantul</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer-text">
            Sertifikat ini dikeluarkan untuk penyelesaian program coaching dan berlaku untuk keperluan pengembangan
            profesional.
        </div>
    </div>
</body>

</html>
