<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Ujian {{ $user->name }}</title>
    <style>
        @page { size: landscape; margin: 10mm; }
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .card {
            width: 900px;
            border: 1px solid black;
            padding: 12px;
            margin: auto;
            display: flex;
            flex-direction: column;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid black;
            padding-bottom: 4px;
            margin-bottom: 4px;
        }
        .header img { width: 50px; }
        .header div { text-align: center; flex: 1; }
        .content {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .info { flex: 1; }
        .info table { line-height: 1.6; }
        .photo {
            width: 100px; height: 100px;
            border: 1px solid black; background: #eee;
            display: flex; align-items: center; justify-content: center;
            margin-top: 10px;
        }
        .ttd { text-align: right; margin-top: 20px; }
        .qr { text-align: right; }
    </style>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</head>
<body>
    <div class="card">
        <div class="header">
            <img src="/media/logo.png" alt="Logo">
            <div>
                <strong>KARTU PESERTA UJIAN SEKOLAH</strong><br>
                DEMO E-UJIAN<br>
                TAHUN PELAJARAN 2024/2025
            </div>
            <div>Panitia</div>
        </div>

        <div class="content">
            <div class="info">
                <table>
                    <tr><td>No Peserta</td><td>:</td><td>{{ $user->id }}</td></tr>
                    <tr><td>Nama</td><td>:</td><td>{{ $user->name }}</td></tr>
                    <tr><td>Username</td><td>:</td><td>{{ $user->username }}</td></tr>
                    <tr><td>Password</td><td>:</td><td>{{ $user->password }}</td></tr>
                </table>
                <div class="photo">
                    <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('media/avatars/blank.png') }}" alt="Foto" width="100" height="100">
                </div>
            </div>

            <div class="qr">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ $user->id }}" alt="QR">
            </div>
        </div>

        <div class="ttd">
            <p>Bantul, 13 Maret 2024<br>Kepala Sekolah,</p>
            <br><br>
            <p><strong>Kepala Sekolah</strong><br>NIP N123144345</p>
        </div>
    </div>
</body>
</html>
