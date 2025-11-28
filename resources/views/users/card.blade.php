<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Login {{ $user->name }}</title>
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
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .header img { width: 60px; height: auto; }
        .header div { text-align: center; flex: 1; line-height: 1.4; }
        .info {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .info table { line-height: 1.8; }
        .photo {
            width: 100px;
            height: 120px;
            border: 1px solid black;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
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
            <img src="{{ $setting->logo_sekolah ? asset($setting->logo_sekolah) : asset('media/avatars/blank.png') }}" alt="Logo Sekolah">
            <div>
                <strong>KARTU LOGIN {{$setting->app }}</strong><br>
                {{$setting->school }}
            </div>
            <img src="{{ $setting->logo ? asset($setting->logo) : asset('media/avatars/blank.png') }}" alt="Logo">
        </div>

        <div class="info">
            <div>
                <table>
                    <tr><td>Nama</td><td>:</td><td>{{ $user->name }}</td></tr>
                    <tr><td>Email</td><td>:</td><td>{{ $user->email }}</td></tr>
                    <tr><td>Username</td><td>:</td><td>{{ $user->username }}</td></tr>
                    <tr><td>Password</td><td>:</td><td>{{ $user->password }}</td></tr>
                </table>
            </div>

            <div class="photo">
                <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('media/avatars/blank.png') }}" alt="Foto">
            </div>
        </div>
    </div>
</body>
</html>