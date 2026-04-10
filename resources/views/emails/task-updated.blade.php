<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Task Assigned</title>
</head>

<body style="font-family: Arial; background:#f4f4f4; padding:20px;">

    <div style="max-width:600px;margin:auto;background:#fff;padding:20px;border-radius:8px;">

        <h2 style="text-align:center;color:#333;">
            Task updated ✅
        </h2>

        <hr>

        <p><strong>Title:</strong> {{ $task->title }}</p>
        <p><strong>Description:</strong> {{ $task->description }}</p>
        <p><strong>Priority:</strong> {{ $task->priority }}</p>

        @if ($task->due_date)
            <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
        @endif

        <hr>

        <p style="font-size:12px;color:#888;text-align:center;">
            © {{ date('Y') }} {{ config('app.name') }}
        </p>

    </div>

</body>

</html>
