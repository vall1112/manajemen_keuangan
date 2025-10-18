<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kode OTP Anda</title>
    <style>
        body {
            background-color: #f5f6fa;
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-wrapper {
            width: 100%;
            padding: 40px 0;
        }
        .email-container {
            max-width: 480px;
            background: #ffffff;
            margin: 0 auto;
            padding: 30px 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            text-align: center;
        }
        .logo {
            width: 80px;
            margin-bottom: 20px;
        }
        h2 {
            color: #111;
            margin-bottom: 10px;
        }
        p {
            font-size: 15px;
            color: #555;
            margin: 0 0 16px;
        }
        .otp-box {
            display: inline-block;
            background: #f0f4ff;
            color: #0056d2;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 6px;
            padding: 14px 24px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .expire-info {
            font-size: 14px;
            color: #777;
        }
        .button {
            display: inline-block;
            background-color: #0056d2;
            color: #fff !important;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 16px;
        }
        .button:hover {
            background-color: #003e9c;
        }
        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 24px;
            border-top: 1px solid #eee;
            padding-top: 14px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <img
                :src="setting?.logo"
                :alt="setting?.app"
                class="logo"
            />
            <h2>Verifikasi Akun Anda</h2>
            <p>Gunakan kode OTP berikut untuk menyelesaikan proses verifikasi akun Anda:</p>
            <div class="otp-box">{{ $otp }}</div>
            <p class="expire-info">Kode ini hanya berlaku selama <strong>5 menit</strong>.</p>
            <p>Jangan berikan kode ini kepada siapa pun demi keamanan akun Anda.</p>
            <a href="#" class="button">Verifikasi Sekarang</a>
            <p class="footer">
                Jika Anda tidak meminta kode ini, abaikan email ini.
            </p>
        </div>
    </div>
</body>
</html>
