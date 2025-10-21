<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kode OTP - {{ $schoolName }}</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f9fafb;
      color: #333;
      margin: 0;
      padding: 0;
    }
    .card {
      max-width: 480px;
      margin: 40px auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
      overflow: hidden;
    }
    .header {
      text-align: center;
      padding: 25px 20px;
      border-bottom: 1px solid #eee;
    }
    .header img { max-width: 70px; margin-bottom: 10px; }
    .header h2 {
      font-size: 20px;
      font-weight: 600;
      margin: 5px 0;
    }
    .content {
      padding: 25px;
      line-height: 1.6;
      font-size: 15px;
    }
    .otp-box {
      text-align: center;
      margin: 25px 0;
      padding: 18px;
      border: 1px solid #ccc;
      border-radius: 6px;
      background: #f5f5f5;
    }
    .otp-code {
      font-size: 32px;
      font-weight: bold;
      color: #222;
      letter-spacing: 6px;
      font-family: 'Courier New', monospace;
    }
    .footer {
      text-align: center;
      font-size: 12px;
      color: #777;
      padding: 20px;
      border-top: 1px solid #eee;
      background: #fafafa;
    }
    a { color: #444; text-decoration: none; }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="card">
    <div class="header">
      <img src="{{ $logo }}" alt="{{ $schoolName }}">
      <h2>{{ $schoolName }}</h2>
      <p>Sistem Keuangan Sekolah</p>
    </div>

    <div class="content">
      <p>Halo <strong>{{ $userName }}</strong>,</p>
      <p>Kode verifikasi (OTP) Anda adalah:</p>

      <div class="otp-box">
        <div class="otp-code">{{ $otp }}</div>
        <p>Berlaku selama 5 menit</p>
      </div>

      <p>Jangan berikan kode ini kepada siapa pun. Kami tidak akan pernah memintanya melalui telepon atau media sosial.</p>

      @if(!empty($transactionInfo))
      <p style="margin-top:15px;">
        <strong>Info Akses:</strong><br>
        Jenis: {{ $transactionInfo['type'] ?? 'Login' }}<br>
        Waktu: {{ $transactionInfo['timestamp'] ?? date('d/m/Y H:i') }}<br>
        Perangkat: {{ $transactionInfo['device'] ?? 'Browser' }}
      </p>
      @endif
    </div>

    <div class="footer">
      <p>Butuh bantuan? Hubungi <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a> atau <a href="{{ $supportUrl }}">Support</a>.</p>
      <p>&copy; {{ date('Y') }} {{ $schoolName }}. Semua hak dilindungi.</p>
    </div>
  </div>
</body>
</html>
