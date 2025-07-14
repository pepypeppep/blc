<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Mixed Orientation PDF</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="certificate-number">No. 2621/STMP/SPBE/11/2024</div>
        <h1 class="title">{{ $course->title }}</h1>

        <div class="grade-section">
            <table class="margin-center" style="width: 600px; border-collapse: collapse; border: 1px solid black;">
                <thead>
                    <tr>
                        <th class="with-border table-column" scope="col" style=" ">Mata Pelatihan </th>
                        <th class="with-border table-column" scope="col" style=" ">JPL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courseChapers as $chapter)
                        <tr>

                            <td class="with-border table-column" style="text-align:left;  ">{{ $chapter->title }}
                            </td>
                            <td class="with-border table-column" style="text-align:right;  ">{{ $chapter->jp }}
                                {{ __('hours') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <div class="signature-section">
            <div class="right">
                <img style="width: 100px; height: 100px;" src="{{ $qrcodeData2 }}" alt="">
                <div class="signature-name">
                    EMANUEL TEGAR WIBISONO, S.Kom.
                </div>
            </div>

        </div>
    </div>
</body>

</html>
