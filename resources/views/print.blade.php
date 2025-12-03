<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>ID Card Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f5f5f5;
        }
        .id-card {
            width: 300px;
            height: 190px;
            border: 2px solid #333;
            border-radius: 10px;
            background: white;
            padding: 15px;
            text-align: center;
            box-shadow: 0 0 8px rgba(0,0,0,0.2);
        }
        .id-card img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 8px;
        }
        h3 {
            margin: 5px 0 3px;
        }
        p {
            margin: 2px 0;
            font-size: 14px;
        }
        .school {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
        }
        @media print {
            body { background: none; }
            button { display: none; }
        }
    </style>
</head>
<body>
    <div class="id-card">
        <div class="school">SMK AL-AZHAR MENGANTI GRESIK</div>
        <img src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('images/default.png') }}" alt="Foto Siswa">
        <h3>{{ $user->nama }}</h3>
        <p><strong>Username:</strong> {{ $user->username }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        @if($user->siswa)
            <p><strong>Nama Siswa:</strong> {{ $user->siswa->nama_siswa ?? '-' }}</p>
            <p><strong>Kelas:</strong> {{ $user->siswa->kelas->nama ?? '-' }}</p>
        @endif
        <button onclick="window.print()">🖨 Cetak</button>
    </div>
</body>
</html>
