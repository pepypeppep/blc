<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Mixed Orientation PDF</title>
    <style>
        @page {
            size: A4 portrait;
            margin-top: 1cm;
            margin-bottom: 1cm;
            margin-left: 1cm;
            margin-right: 1cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;

            @if ($cover2Base64)
                background-image: url("data:image/png;base64,{{ $cover2Base64 }}");
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            @endif
        }

        .page-break {
            page-break-after: always;
        }

        .container {
            text-align: center;
            padding: 20px;
            font-size: 12px;
        }

        .align-center {
            text-align: center;
        }

        .with-border {
            border: 1px solid black;
        }

        .bg-blue {
            background-color: #0000ff;
        }

        .margin-center {
            margin: 0 auto;
        }

        .w-full {
            width: 100%;
        }

        .table-column {
            padding: 10px;
        }

        .signature-section {
            display: block;
            margin-top: 20px;
            /* background-color: #0000ff; */
        }

        .signature {
            background-color: #ff0000;
            padding: 20px;
            width: 100px;

        }

        .signature-name {
            margin-top: 10px;
            font-size: 12px;
        }

        div.border {
            border: 3px solid #73AD21;
        }

        .right {
            position: absolute;
            right: 20px;
            width: 300px;
            padding: 10px;
        }

        .certificate-number {
            margin-top: 20px;
        }

        .title {
            margin-top: 10px;
        }


        .p-4 {
            padding: 20px;
        }

        .table-header {
            background-color: #C0C0C0;
            color: #000;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="title">Sesi Mentoring</h1>

        <div class="grade-section">
            <table class="margin-center" style="width: 600px; border-collapse: collapse; border: 1px solid black;">
                <thead>
                    <tr>
                        <th class="with-border table-column table-header" scope="col" style=" ">No</th>
                        <th class="with-border table-column table-header" scope="col" style=" ">Mata Pelatihan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mentoring->mentoringSessions as $session)
                        <tr>
                            <td class="with-border table-column" style="text-align:center;  ">{{ $loop->iteration }}
                            </td>
                            <td class="with-border table-column" style="text-align:left;  ">{{ $session->activity }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <div class="signature-section">
            <div class="right">
                <div class="text-center">
                    <div>[tanggal_sertifikat]</div>
                    <div>[nama_jabatan]</div>
                    <div>Kabupaten Bantul</div>
                    <img style="width: 100px; height: 100px;" src="{{ $qrcodeData }}" alt="">
                    <div>[nama_kepala_opd]</div>
                    <div>[nama_golongan]</div>
                    <div>[nip]</div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
