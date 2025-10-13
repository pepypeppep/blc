<!DOCTYPE html>
<html>

<head>
    <style>
        /* Your existing styles remain the same */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
        }

        .bold {
            font-weight: bold;
        }

        .student-image {
            max-width: 200px;
            height: auto;
        }

        .m-0 {
            margin: 0;
        }

        .mx-0 {
            margin-left: 0;
            margin-right: 0;
        }

        .my-1 {
            margin-top: 4px;
            margin-bottom: 4px;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .table-border-0 th,
        .table-border-0 td {
            border: none;
        }

        td p {
            margin: 0;
        }

        .session-image {
            max-width: 300px;
            max-height: 200px;
        }
    </style>
</head>

<body>
    <center>
        <h1 style="margin-bottom:0;">Laporan Mentoring</h1>
        <h2 class="m-0">Total JP: {{ $data->jp }}</h2>
    </center>

    <div>
        <table>
            <tr>
                <td class="text-left">
                    <h2 class="m-0">Mentee</h2>
                </td>
                <td class="text-left">
                    <h2 class="m-0">Mentor</h2>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="my-1"><span class="bold">Nama:</span> {{ $data->mentee->name }}</p>
                    <p class="my-1"><span class="bold">Jabatan:</span> {{ $data->mentee->jabatan }}</p>
                </td>
                <td>
                    <p class="my-1"><span class="bold">Nama:</span> {{ $data->mentor->name }}</p>
                    <p class="my-1"><span class="bold">Jabatan:</span> {{ $data->mentor->jabatan }}</p>
                </td>
            </tr>
        </table>
    </div>

    @foreach ($data->mentoringSessions as $key => $session)
        <table>
            <tr>
                <td>
                    <strong>Sesi #{{ $key + 1 }}</strong>
                </td>
                <td>
                    <strong>{{ \Carbon\Carbon::parse($session->mentoring_date)->format('d F Y') }}</strong>
                </td>
            </tr>
            <tr>
                <td width="100">Kegiatan</td>
                <td>{!! $session->activity !!}</td>
            </tr>
            <tr>
                <td>Hambatan</td>
                <td>{!! $session->description !!}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>
                    @if ($session->image)
                        @php
                            $imagePath = storage_path('app/private/' . $session->image);
                            if (file_exists($imagePath)) {
                                $imageData = base64_encode(file_get_contents($imagePath));
                                $imageSrc =
                                    'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;
                            } else {
                                $imageSrc = null;
                            }
                        @endphp
                        @if ($imageSrc)
                            <img src="{{ $imageSrc }}" alt="img" class="session-image">
                        @else
                            <p>Gambar tidak ditemukan</p>
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td>Catatan</td>
                <td>{!! $session->mentoring_note !!}</td>
            </tr>
            <tr>
                <td>Instruksi</td>
                <td>{!! $session->mentoring_instructions !!}</td>
            </tr>
        </table>
    @endforeach
</body>

</html>
