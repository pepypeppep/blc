<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Mixed Orientation PDF</title>
    <style>
        @page {
            size: portrait;
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
    </style>
</head>

<body>
    <div class="portrait-content">
        <h1>Portrait Page</h1>
        <p>This is page 2 in portrait orientation.</p>
    </div>
</body>

</html>
