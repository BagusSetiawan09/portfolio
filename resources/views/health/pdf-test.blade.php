<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>PDF Test</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        .box { border: 1px solid #000; padding: 12px; }
    </style>
</head>
<body>
    <div class="box">
        <h3>PDF Render Test</h3>
        <p>Ini hanya test render PDF.</p>
        <p><b>Time:</b> {{ $time ?? now()->toDateTimeString() }}</p>
    </div>
</body>
</html>
