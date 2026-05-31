<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222;">
    <div style="max-width: 600px; margin: 0 auto; padding: 32px; border: 1px solid #e2e8f0; border-radius: 16px; background: #ffffff;">
        <h1 style="font-size: 24px; color: #3b82f6; margin-bottom: 16px;">{{ $title }}</h1>
        <p style="font-size: 16px; line-height: 1.6; color: #334155;">{{ $message }}</p>
        <p style="margin-top: 24px; font-size: 14px; color: #64748b;">If you received this email successfully, your SMTP settings are working.</p>
        <div style="margin-top: 24px; padding: 16px; background: #f8fafc; border-radius: 12px; color: #475569;">
            <strong>Application:</strong> TARIQ<br>
            <strong>Received at:</strong> {{ now()->toDayDateTimeString() }}
        </div>
    </div>
</body>
</html>
