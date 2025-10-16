<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kode OTP Anda</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            padding: 40px;
            color: #333;
        }
        .container {
            max-width: 600px;
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            margin: 0 auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .text-center { text-align: center; }
        .mb-10 { margin-bottom: 40px; }
        .mb-8 { margin-bottom: 32px; }
        .mb-3 { margin-bottom: 12px; }
        .w-200px { width: 200px; }
        .text-primary { color: #0d6efd; }
        .otp {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #0d6efd;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }
        a { color: #0d6efd; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-10">
            <a href="{{ url('/') }}">
                <img src="{{ $logo }}" alt="{{ $schoolName }}" class="w-200px mb-8" />
            </a>
            <h1 class="mb-3">
                Masuk ke <span class="text-primary">{{ $schoolName }}</span>
            </h1>
        </div>

        <p>Halo <strong>{{ $userName }}</strong>,</p>
        <p>Kami menerima permintaan untuk login ke akun Anda. Berikut adalah kode OTP Anda:</p>

        <div class="text-center">
            <div class="otp">{{ $otp }}</div>
        </div>

        <p>Jangan bagikan kode ini kepada siapa pun. Kode ini berlaku selama 5 menit.</p>

        <div class="footer">
            <p>Jika Anda mengalami kendala, silakan hubungi kami di
                <a href="{{ $supportUrl }}">{{ $supportEmail }}</a>.
            </p>
            <p>&copy; {{ date('Y') }} {{ $schoolName }}. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>
