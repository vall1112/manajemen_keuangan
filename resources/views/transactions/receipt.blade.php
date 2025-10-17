<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; padding: 20px; background: #f5f5f5; }
        .receipt { max-width: 320px; margin: 0 auto; background: white; padding: 20px; border: 2px dashed #333; }
        .header { text-align: center; border-bottom: 1px dashed #333; padding-bottom: 10px; margin-bottom: 15px; }
        .header h1 { font-size: 16px; margin-bottom: 5px; }
        .header p { font-size: 11px; color: #666; }
        .logo { height: 60px; width: auto; margin-bottom: 8px; display: block; margin-left: auto; margin-right: auto; }
        .section { margin-bottom: 10px; font-size: 13px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 4px; }
        .label { font-weight: bold; }
        .divider { border-top: 1px dashed #333; margin: 12px 0; }
        .amount { text-align: center; font-size: 22px; font-weight: bold; margin: 15px 0; padding: 8px; background: #f0f0f0; }
        .status { text-align: center; font-size: 13px; font-weight: bold; padding: 8px; margin: 10px 0; border-radius: 4px; }
        .status.lunas { background: #d4edda; color: #155724; }
        .status.pending { background: #fff3cd; color: #856404; }
        .status.belum { background: #f8d7da; color: #721c24; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; color: #666; }
        .print-btn { display: block; width: 100%; padding: 10px; margin-top: 15px; background: #007bff; color: white; border: none; cursor: pointer; font-size: 14px; border-radius: 4px; }
        .print-btn:hover { background: #0056b3; }
        @media print {
            body { background: white; padding: 0; }
            .receipt { border: none; max-width: 100%; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- HEADER -->
        <div class="header">
            @php
                use Illuminate\Support\Str;

                $logoPath = null;
                if (!empty($setting->logo_sekolah)) {
                    $logoPath = Str::startsWith($setting->logo_sekolah, ['http://', 'https://'])
                        ? $setting->logo_sekolah
                        : asset($setting->logo_sekolah);
                }
            @endphp

            <img src="{{ $logoPath ?? asset('images/default-logo.png') }}" alt="Logo Sekolah" class="logo">

            <h1>{{ $setting->school ?? 'SMK AL-AZHAR MENGANTI GRESIK' }}</h1>
            <p>{{ $setting->app ?? 'Sistem Keuangan Sekolah' }}</p>
            <p><strong>STRUK PEMBAYARAN</strong></p>
        </div>

        <!-- DATA TRANSAKSI -->
        <div class="section">
            <div class="row"><span class="label">No. Invoice:</span><span>{{ $bill->kode ?? '-' }}</span></div>
            <div class="row"><span class="label">Tanggal:</span><span>{{ $bill->tanggal_tagih ?? '-' }}</span></div>
            <div class="row"><span class="label">Jatuh Tempo:</span><span>{{ $bill->jatuh_tempo ?? '-' }}</span></div>
        </div>

        <div class="divider"></div>

        <!-- DATA SISWA -->
        <div class="section">
            <div class="row"><span class="label">Nama:</span><span>{{ $bill->student->nama ?? '-' }}</span></div>
            <div class="row"><span class="label">NIS:</span><span>{{ $bill->student->nis ?? '-' }}</span></div>
            <div class="row"><span class="label">Kelas:</span><span>{{ $bill->student->classroom->nama_kelas ?? '-' }}</span></div>
        </div>

        <div class="divider"></div>

        <!-- DETAIL PEMBAYARAN -->
        <div class="section">
            <div class="row"><span class="label">Jenis Pembayaran:</span><span>{{ $bill->paymentType->nama_jenis ?? '-' }}</span></div>
            <div class="row"><span class="label">Tahun Ajaran:</span><span>{{ $bill->schoolYear->tahun_ajaran ?? '-' }}</span></div>
        </div>

        <!-- NOMINAL -->
        <div class="amount">
            Rp {{ number_format($bill->total_tagihan ?? 0, 0, ',', '.') }}
        </div>

        <!-- STATUS -->
        @php
            $statusClass = match($bill->status ?? '') {
                'Lunas' => 'lunas',
                'Pending' => 'pending',
                default => 'belum',
            };
        @endphp

        <div class="status {{ $statusClass }}">
            {{ strtoupper($bill->status ?? 'BELUM DIBAYAR') }}
        </div>

        <div class="divider"></div>

        <!-- RINGKASAN -->
        <div class="section">
            <div class="row"><span>Total Tagihan:</span><span>Rp {{ number_format($bill->total_tagihan ?? 0, 0, ',', '.') }}</span></div>
            <div class="row"><span>Dibayar:</span><span>Rp {{ number_format($bill->dibayar ?? 0, 0, ',', '.') }}</span></div>
            <div class="row"><span class="label">Sisa:</span><span class="label">Rp {{ number_format($bill->sisa ?? 0, 0, ',', '.') }}</span></div>
        </div>

        <div class="divider"></div>

        <!-- FOOTER -->
        <div class="footer">
            <p>Terima kasih atas pembayaran Anda</p>
            <p>Simpan struk ini sebagai bukti pembayaran yang sah.</p>
            <p style="margin-top: 10px;">Admin: {{ Auth::user()->name ?? 'Admin' }}</p>
            <p style="margin-top: 5px;">{{ $setting->alamat ?? '' }}</p>
            <p>{{ $setting->telepon ?? '' }} | {{ $setting->email ?? '' }}</p>
        </div>

        <button class="print-btn" onclick="window.print()">🖨️ Cetak Struk</button>
    </div>
</body>
</html>
