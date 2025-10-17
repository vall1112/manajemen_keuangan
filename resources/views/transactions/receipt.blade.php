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
        .header { text-align: center; border-bottom: 1px dashed #333; padding-bottom: 15px; margin-bottom: 15px; }
        .header h1 { font-size: 18px; margin-bottom: 5px; }
        .header p { font-size: 12px; color: #666; }
        .logo { height: 60px; width: auto; margin-bottom: 8px; display: block; margin-left: auto; margin-right: auto; }
        .section { margin-bottom: 15px; font-size: 13px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .label { font-weight: bold; }
        .divider { border-top: 1px dashed #333; margin: 15px 0; }
        .amount { text-align: center; font-size: 24px; font-weight: bold; margin: 20px 0; padding: 10px; background: #f0f0f0; }
        .status { text-align: center; font-size: 14px; font-weight: bold; padding: 8px; margin: 10px 0; border-radius: 4px; }
        .status.lunas { background: #d4edda; color: #155724; }
        .status.sebagian { background: #fff3cd; color: #856404; }
        .status.belum { background: #f8d7da; color: #721c24; }
        .footer { text-align: center; margin-top: 20px; font-size: 11px; color: #666; }
        .print-btn { display: block; width: 100%; padding: 10px; margin-top: 20px; background: #007bff; color: white; border: none; cursor: pointer; font-size: 14px; border-radius: 4px; }
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
            <a href="{{ url('/') }}">
                @php
                    use Illuminate\Support\Str;

                    // Deteksi logo otomatis (bisa http://, storage/, atau public/)
                    $logoPath = null;
                    if (!empty($setting->logo_sekolah)) {
                        if (Str::startsWith($setting->logo_sekolah, ['http://', 'https://'])) {
                            $logoPath = $setting->logo_sekolah;
                        } else {
                            $logoPath = asset($setting->logo_sekolah);
                        }
                    }
                @endphp

                @if ($logoPath)
                    <img src="{{ $logoPath }}" alt="Logo Sekolah" class="logo">
                @else
                    {{-- Fallback jika logo belum diatur --}}
                    <img src="{{ asset('images/default-logo.png') }}" alt="Logo Sekolah" class="logo">
                @endif
            </a>

            <h1>{{ $setting->nama_sekolah ?? 'SMK AL-AZHAR' }}</h1>
            <p>{{ $setting->nama_aplikasi ?? 'Sistem Keuangan Siswa' }}</p>
            <p><strong>STRUK PEMBAYARAN</strong></p>
        </div>

        <!-- DATA TRANSAKSI -->
        <div class="section">
            <div class="row"><span class="label">No. Invoice:</span><span>{{ $data->invoice ?? '-' }}</span></div>
            <div class="row"><span class="label">Tanggal:</span><span>{{ $data->tanggal ?? '-' }}</span></div>
        </div>

        <div class="divider"></div>

        <!-- DATA SISWA -->
        <div class="section">
            <div class="row"><span class="label">Nama:</span><span>{{ $data->nama ?? '-' }}</span></div>
            <div class="row"><span class="label">NIS:</span><span>{{ $data->nis ?? '-' }}</span></div>
            <div class="row"><span class="label">Kelas:</span><span>{{ $data->kelas ?? '-' }}</span></div>
        </div>

        <div class="divider"></div>

        <!-- DETAIL PEMBAYARAN -->
        <div class="section">
            <div class="row"><span class="label">Jenis:</span><span>{{ $data->jenis ?? '-' }}</span></div>
            <div class="row"><span class="label">Tahun Ajaran:</span><span>{{ $data->tahun_ajaran ?? '-' }}</span></div>
        </div>

        <!-- NOMINAL -->
        <div class="amount">
            Rp {{ number_format($data->nominal ?? 0, 0, ',', '.') }}
        </div>

        <!-- STATUS -->
        @php
            $statusClass = 'belum';
            $statusText = '✗ BELUM DIBAYAR';
            if (($data->status ?? '') === 'Lunas') {
                $statusClass = 'lunas';
                $statusText = '✓ LUNAS';
            } elseif (($data->status ?? '') === 'Dibayar Sebagian') {
                $statusClass = 'sebagian';
                $statusText = '⚠ DIBAYAR SEBAGIAN';
            }
        @endphp
        <div class="status {{ $statusClass }}">
            {{ $statusText }}
        </div>

        <div class="divider"></div>

        <!-- RINGKASAN -->
        <div class="section">
            <div class="row"><span>Total Tagihan:</span><span>Rp {{ number_format($data->total_tagihan ?? 0, 0, ',', '.') }}</span></div>
            <div class="row"><span>Dibayar:</span><span>Rp {{ number_format($data->dibayar ?? 0, 0, ',', '.') }}</span></div>
            <div class="row"><span class="label">Sisa:</span><span class="label">Rp {{ number_format($data->sisa ?? 0, 0, ',', '.') }}</span></div>
        </div>

        <div class="divider"></div>

        <!-- FOOTER -->
        <div class="footer">
            <p>Terima kasih atas pembayaran Anda</p>
            <p>Simpan struk ini sebagai bukti pembayaran</p>
            <p style="margin-top: 10px;">Admin: {{ $data->admin ?? 'Admin' }}</p>
        </div>

        <button class="print-btn" onclick="window.print()">🖨️ Cetak Struk</button>
    </div>
</body>
</html>
            