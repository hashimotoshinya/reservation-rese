<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        h1 { color: #2c3e50; }
        p { font-size: 14px; }
    </style>
</head>
<body>
    <h1>{{ $subjectLine }}</h1>
    <p>{!! nl2br(e($messageBody)) !!}</p>
</body>
</html>