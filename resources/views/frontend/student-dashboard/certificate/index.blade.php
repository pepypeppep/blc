<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Certificate') }}</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@100..900&family=Noto+Sans+Bengali:wght@100..900&family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap');

        body {
            font-family: 'Noto Sans Bengali', 'Noto Sans', 'Noto Sans Arabic', sans-serif;
        }

        @foreach ($certificateItems as $item)
            #{{ $item->element_id }} {
                left: {{ $item->x_position }}px;
                top: {{ $item->y_position }}px;
            }
        @endforeach

        @page {
            size: 1123px 794px;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;

            @if ($cover1Base64)
                background-image: url("data:image/png;base64,{{ $cover1Base64 }}");
                background-size: 100% 100%;
                background-position: center;
                background-repeat: no-repeat;
            @endif
        }



        .draggable-element {
            position: absolute;

        }

        #title {
            font-size: 22px;
            font-weight: bold;
            color: black;
            left: 50%;
            transform: translate(-50%);
            width: 730px;
            text-align: center;
        }

        #sub_title {
            font-size: 18px;
            color: black;
            text-align: inherit;
            font-weight: inherit;
            left: 50%;
            transform: translate(-50%);
            width: 730px;
            text-align: center;
        }

        #description {
            font-size: 16px;
            color: black;
            text-align: center;
            font-weight: inherit;
            width: 730px;
            left: 50%;
            transform: translate(-50%);
        }

        .signature {
            font-size: 12px;
            color: black;
            text-align: center;
            font-weight: inherit;
            width: 730px;
            left: 50%;
            transform: translate(-50%);
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="certificate-outer">
        <div class="certificate-body">

            @if ($certificate->title)
                <div id="title" class="draggable-element">{{ $certificate->title }}</div>
            @endif

            @if ($certificate->sub_title)
                <div id="sub_title" class="draggable-element">{{ $certificate->sub_title }}
                </div>
            @endif

            @if ($certificate->description)
                <div id="description" class="draggable-element">
                    {!! clean(nl2br($certificate->description)) !!}
                </div>
            @endif


            @if ($certificate->description)
                <div id="signature" class="draggable-element">
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
            @endif

        </div>
    </div>
</body>

</html>
