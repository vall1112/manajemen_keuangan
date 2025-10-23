<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran SPP</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            padding: 20px;
        }

        .receipt {
            width: 105mm;
            height: 148mm; /* A6 */
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin: 0 auto;
            padding: 15px;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #666;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .header img { height: 45px; margin-bottom: 5px; }
        .header h1 { font-size: 13px; font-weight: bold; }
        .header p { font-size: 11px; color: #555; }

        .section { font-size: 12px; margin-bottom: 8px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 2px; }
        .label { font-weight: bold; }
        .divider { border-top: 1px dashed #666; margin: 6px 0; }

        .amount {
            text-align: center;
            background: #f0f0f0;
            padding: 6px;
            font-weight: bold;
            font-size: 15px;
            border-radius: 4px;
            margin-top: 5px;
        }

        .status {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            padding: 5px;
            border-radius: 4px;
            margin: 8px 0;
        }
        .status.lunas { background: #d4edda; color: #155724; }
        .status.pending { background: #fff3cd; color: #856404; }
        .status.belum { background: #f8d7da; color: #721c24; }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #555;
            margin-top: 10px;
        }

        .print-btn {
            display: block;
            width: 100%;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px;
            cursor: pointer;
            font-size: 13px;
            margin-top: 10px;
        }
        .print-btn:hover { background: #0056b3; }

        @media print {
            body { background: white; padding: 0; margin: 0; }
            .receipt {
                border: none;
                width: 105mm;
                height: 148mm;
                margin: 0;
                page-break-after: always;
            }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        {{-- HEADER --}}
        <div class="header">
    <img src="{{    $schoolLogo = $setting->logo_sekolah
 }}" alt="Logo Sekolah">
    <h1>{{ strtoupper($schoolName) }}</h1>
    <p>Sistem Pembayaran Sekolah</p>
    <p><strong>BUKTI PEMBAYARAN SPP</strong></p>
</div>



        {{-- INFORMASI TRANSAKSI --}}
        <div class="section">
            <div class="row"><span class="label">No. Invoice:</span><span>{{ $transaction->kode ?? '-' }}</span></div>
            <div class="row">
    <span class="label">Tanggal Bayar:</span>
    <span>{{ \Carbon\Carbon::parse($tanggalBayar)->format('d/m/Y') }}</span>
</div>
        </div>

        <div class="divider"></div>

        {{-- DATA SISWA --}}
        <div class="section">
            <div class="row"><span class="label">Nama Siswa:</span><span>{{ $student->nama }}</span></div>
            <div class="row"><span class="label">NIS:</span><span>{{ $student->nis }}</span></div>
            <div class="row"><span class="label">Kelas:</span><span>{{ $classroom->nama_kelas }}</span></div>
            <div class="row"><span class="label">Jurusan:</span><span>{{ $major->kode ?? '-' }}</span></div>
        </div>

        <div class="divider"></div>

        {{-- DETAIL TAGIHAN --}}
        <div class="section">
            <div class="row"><span class="label">Jenis Pembayaran:</span><span>{{ $paymentType->nama_jenis }}</span></div>
            <div class="row"><span class="label">Tahun Ajaran:</span><span>{{ $schoolYear->tahun_ajaran }}</span></div>
            <div class="row"><span class="label">Kode Tagihan:</span><span>{{ $bill->kode }}</span></div>
        </div>

        {{-- NOMINAL --}}
        <div class="amount">
            Rp {{ number_format($bill->total_tagihan, 0, ',', '.') }}
        </div>

        <div class="status {{ strtolower($transaction->status) }}">
            {{ strtoupper($transaction->status) }}
        </div>

        <div class="divider"></div>

        {{-- RINCIAN PEMBAYARAN --}}
        <div class="section">
            <div class="row"><span>Total Tagihan:</span><span>Rp {{ number_format($bill->total_tagihan, 0, ',', '.') }}</span></div>
            
        </div>

        <div class="divider"></div>

        {{-- FOOTER --}}
        <div class="footer">
    <p>Terima kasih telah melakukan pembayaran</p>
    <p>Simpan struk ini sebagai bukti pembayaran yang sah.</p>
    <br>
    <p>Petugas: {{ $transaction->user->name ?? '-' }}</p>
    <p>{{ $setting->alamat }}</p>
    <p>{{ $setting->telepon }} | {{ $setting->email }}</p>
</div>


        <button class="print-btn" onclick="window.print()">🖨️ Cetak Struk</button>
    </div>
</body>
</html>
