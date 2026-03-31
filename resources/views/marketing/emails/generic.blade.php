<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        {!! $renderedContent !!}
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="font-size: 12px; color: #777;">
            You are receiving this email because you are a contact in our system. 
            If you wish to stop receiving these emails, please 
            <a href="{{ url('/unsubscribe?email=' . urlencode($notifiable_email ?? '')) }}">unsubscribe here</a>.
        </p>
    </div>
</body>
</html>
