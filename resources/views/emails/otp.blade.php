<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kode OTP Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            width: 100px;
            margin-bottom: 20px;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            background: #e9ecef;
            padding: 10px;
            display: inline-block;
            border-radius: 5px;
        }
        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            background: #007bff;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 10px;
        }
        .button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <img
            :src="setting?.logo"
            :alt="setting?.app"
            class="w-200px mb-8"
        />
        <h2>Kode OTP Anda</h2>
        <p>Gunakan kode berikut untuk verifikasi akun Anda:</p>
        <div class="otp-code">{{ $otp }}</div>
        <p>Kode ini berlaku selama <strong>5 menit</strong>. Jangan berikan kepada siapa pun.</p>
        <p class="footer">Jika Anda tidak meminta kode ini, abaikan email ini.</p>
    </div>
</body>
</html>
