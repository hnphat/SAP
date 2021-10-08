<!DOCTYPE html>
<html>
<head>
    <title>QR Code</title>
</head>
<body>
<h1 style="text-align: center;">
    {!! QrCode::size(400)->generate($content); !!}
</h1>
</body>
</html>
