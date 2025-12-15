<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifikasi</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5;">
    <div style="max-width: 600px; margin: 0 auto; padding: 16px;">
        <h2 style="margin: 0 0 12px 0;">{{ $notif->judul }}</h2>

        @if(!empty($notif->pesan))
            <p style="margin: 0 0 12px 0;">{{ $notif->pesan }}</p>
        @else
            <p style="margin: 0 0 12px 0; color:#666;">(Tidak ada pesan)</p>
        @endif

        <hr style="border:0;border-top:1px solid #eee;margin:16px 0;">

        <p style="margin:0; font-size: 12px; color:#888;">
            Email ini dikirim otomatis oleh sistem.
        </p>
    </div>
</body>
</html>
