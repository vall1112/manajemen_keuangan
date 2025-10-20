<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi - Sistem Keuangan {{ $schoolName }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            color: #333;
            min-height: 100vh;
        }
        .wrapper { max-width: 600px; margin: 0 auto; }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            border-radius: 12px 12px 0 0;
            color: white;
        }
        .header img {
            max-width: 100px;
            height: auto;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .security-badge {
            background: #e7f3ff;
            border-left: 4px solid #0d6efd;
            padding: 12px 16px;
            margin-bottom: 25px;
            border-radius: 4px;
            font-size: 13px;
            color: #0d6efd;
        }
        .security-badge strong { display: block; margin-bottom: 5px; }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .greeting strong { color: #667eea; }
        .otp-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            margin: 30px 0;
            border: 2px solid #667eea;
        }
        .otp-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .otp-code {
            font-size: 42px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #667eea;
            font-family: 'Courier New', monospace;
            line-height: 1.2;
        }
        .otp-timer {
            font-size: 13px;
            color: #e74c3c;
            margin-top: 15px;
            font-weight: 600;
        }
        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #856404;
        }
        .transaction-info {
            background: #f0f8ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 13px;
            line-height: 1.8;
        }
        .transaction-info dt {
            font-weight: 600;
            color: #667eea;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .transaction-info dd {
            color: #555;
            margin-left: 0;
        }
        .transaction-info dd:first-child { margin-top: 0; }
        .instructions {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #2e7d32;
        }
        .instructions ol {
            margin-left: 20px;
            margin-top: 10px;
        }
        .instructions li { margin: 8px 0; }
        .divider {
            height: 1px;
            background: #e0e0e0;
            margin: 25px 0;
        }
        .footer {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 25px;
            font-size: 12px;
            color: #666;
            line-height: 1.8;
        }
        .footer strong { color: #333; }
        .support-link { color: #0d6efd; text-decoration: none; }
        .support-link:hover { text-decoration: underline; }
        .warning {
            background: #ffebee;
            border-left: 4px solid #f44336;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #c62828;
        }
        .footer-links {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .footer-links a {
            color: #0d6efd;
            text-decoration: none;
            margin: 0 15px;
            font-size: 12px;
        }
        .footer-links a:hover { text-decoration: underline; }
        @media (max-width: 600px) {
            .container { padding: 20px; }
            .otp-code { font-size: 32px; letter-spacing: 6px; }
            .header h1 { font-size: 20px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <img src="{{ $logo }}" alt="{{ $schoolName }}" />
            <h1>{{ $schoolName }}</h1>
            <p>Sistem Manajemen Keuangan & Administrasi</p>
        </div>

        <!-- Main Content -->
        <div class="container">
            <!-- Security Badge -->
            <div class="security-badge">
                <strong>🔒 Verifikasi Keamanan Dua Langkah</strong>
                Email ini merupakan permintaan otentikasi resmi dari sistem keuangan sekolah.
            </div>

            <!-- Greeting -->
            <div class="greeting">
                Halo <strong>{{ $userName }}</strong>,<br>
                Kami menerima permintaan akses ke Sistem Keuangan {{ $schoolName }}. Untuk melanjutkan, silakan gunakan kode OTP di bawah ini.
            </div>

            <!-- OTP Section -->
            <div class="otp-section">
                <div class="otp-label">Kode Verifikasi Anda</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-timer">⏱️ Berlaku selama 5 menit</div>
            </div>

            <!-- Transaction Info (if applicable) -->
            @if(!empty($transactionInfo))
            <div class="transaction-info">
                <dt>📋 Detail Transaksi:</dt>
                <dd>Jenis Akses: {{ $transactionInfo['type'] ?? 'Login' }}</dd>
                <dd>Waktu Permintaan: {{ $transactionInfo['timestamp'] ?? date('d/m/Y H:i') }}</dd>
                <dd>Perangkat: {{ $transactionInfo['device'] ?? 'Browser' }}</dd>
            </div>
            @endif

            <!-- Instructions -->
            <div class="instructions">
                <strong>📝 Cara Menggunakan Kode OTP:</strong>
                <ol>
                    <li>Salin kode OTP di atas</li>
                    <li>Kembali ke halaman login Sistem Keuangan</li>
                    <li>Masukkan kode OTP pada kolom yang tersedia</li>
                    <li>Klik tombol "Selanjutnya"</li>
                </ol>
            </div>

            <!-- Warning -->
            <div class="warning">
                <strong>⚠️ Penting:</strong> Jangan pernah memberikan kode OTP ini kepada siapa pun, termasuk pihak dari sekolah atau administrator sistem. Kami tidak akan pernah meminta kode ini melalui telepon atau media sosial.
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <strong>ℹ️ Keamanan Akun:</strong> Gunakan password yang kuat dan jangan bagikan kredensial akun Anda. Selalu logout setelah menggunakan sistem keuangan.
            </div>

            <div class="divider"></div>

            <!-- Support Footer -->
            <div class="footer">
                <strong>Butuh Bantuan?</strong><br>
                Jika Anda tidak melakukan permintaan login ini atau mengalami kendala, hubungi tim support kami segera.

                <div class="footer-links">
                    <a href="mailto:{{ $supportEmail }}" class="support-link">{{ $supportEmail }}</a>
                    <a href="{{ $supportUrl }}" class="support-link">Hubungi Support</a>
                </div>

                <p style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                    &copy; {{ date('Y') }} <strong>{{ $schoolName }}</strong>. Semua hak dilindungi.<br>
                    <small style="color: #999;">Email ini dikirim secara otomatis. Jangan membalas email ini.</small>
                </p>
            </div>
        </div>
    </div>
</body>
</html>