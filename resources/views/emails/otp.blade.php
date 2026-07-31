<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your verification code</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f5f5f5; }
        .wrap { max-width: 480px; margin: 24px auto; padding: 24px; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        h1 { font-size: 1.25rem; margin: 0 0 16px; color: #111; }
        .code { font-size: 1.75rem; font-weight: 700; letter-spacing: 6px; color: {{ $portfolio->brand_color_1 ?? '#de2e79' }}; margin: 16px 0; }
        p { margin: 0 0 12px; }
        .muted { color: #666; font-size: 0.875rem; }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>Your {{ $portfolio->company_name ?? env('APP_NAME', 'Pinkush') }} verification code</h1>
        <p>Use this code to complete your registration:</p>
        <div class="code">{{ $otp }}</div>
        <p class="muted">This code expires in 10 minutes. If you didn't request it, you can ignore this email.</p>
        <p class="muted">— {{ $portfolio->company_name ?? env('APP_NAME', 'Pinkush') }}</p>
    </div>
</body>
</html>
