<!doctype html>
<html>
<head>
  <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color:#111; line-height:1.6;">
  <h2 style="margin:0 0 8px;">{{ $title }}</h2>
  <p style="margin:0 0 12px;">{!! nl2br(e($body)) !!}</p>

  @if(!empty($url))
    <p style="margin:16px 0 0;">
      <a href="{{ $url }}" style="display:inline-block;padding:10px 14px;background:#111;color:#fff;text-decoration:none;border-radius:8px;">
        Buka di Dashboard
      </a>
    </p>
  @endif

  <hr style="margin:18px 0;border:none;border-top:1px solid #ddd;">
  <small style="color:#666;">Notifikasi otomatis dari sistem.</small>
</body>
</html>
