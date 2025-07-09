<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Export</title>
</head>
<body>
    <h1>Log Details</h1>
    <p><strong>Action:</strong> {{ $log->action }}</p>
    <p><strong>Performed By:</strong> {{ $log->user->name }}</p>
    <p><strong>Details:</strong> {{ $log->details }}</p>
    <p><strong>Date:</strong> {{ $log->created_at }}</p>
</body>
</html>
