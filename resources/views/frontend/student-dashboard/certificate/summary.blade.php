<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Mixed Orientation PDF</title>
    <style>
        @page {
            size: A4 portrait;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .page-break {
            page-break-after: always;
        }

        .landscape-content {
            width: 100%;
            text-align: center;
            padding: 20px;
        }

        .portrait-content {
            width: 100%;
            text-align: center;
            padding: 20px;
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
    </style>
</head>

<body>
    <div class="portrait-content">
        <h1>{{ $course->title }}</h1>

        <div class="w-full">
            <table class="margin-center" style="width: 500px; border-collapse: collapse; border: 1px solid black;">
                <thead>
                    <tr>
                        <th class="with-border table-column" scope="col" style=" ">{{ __('Chapter Title') }}</th>
                        <th class="with-border table-column" scope="col" style=" ">{{ __('Lesson Hours') }}</th>
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
    </div>
</body>

</html>
