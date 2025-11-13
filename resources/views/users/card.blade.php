<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Pengguna {{ $user->name }}</title>
    <style>
        @page { size: landscape; margin: 10mm; }
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .card {
            width: 900px;
            border: 1px solid #000;
            padding: 20px;
            margin: auto;
            border-radius: 8px;
        }
        h2 { text-align: center; margin-bottom: 10px; }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
        }
        .photo {
            text-align: center;
            margin-top: 10px;
        }
        .photo img {
            width: 120px;
            height: 120px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Kartu Pengguna</h2>
        <table class="info-table">
            <tr>
                <td><strong>Nama</strong></td>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <td><strong>Username</strong></td>
                <td>{{ $user->username }}</td>
            </tr>
            <tr>
                <td><strong>Email</strong></td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td><strong>Siswa</strong></td>
                <td>{{ $user->student->nama ?? '-' }}</td>
            </tr>
        </table>

        <div class="photo">
            <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('media/avatars/blank.png') }}" alt="Foto User">
        </div>
    </div>
</body>
</html>
