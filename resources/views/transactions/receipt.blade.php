<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi Pembayaran Keuangan Siswa</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0;
            padding: 0;
            line-height: 1.4;
            color: #212529;
            background: #fff;
        }

        .receipt-container {
            width: 105mm;   /* A6 width */
            height: 148mm;  /* A6 height */
            margin: 0 auto;
            background: white;
            border: 1px solid #dee2e6;
            padding: 15px 20px;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 1.2em;
            margin: 0;
            font-weight: 600;
            color: #212529;
        }

        .header img {
            height: 40px;
        }

        .section {
            margin-bottom: 15px;
        }

        .section h2 {
            font-size: 1em;
            font-weight: 600;
            margin-bottom: 8px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 4px;
            color: #495057;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 8px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.75em;
            color: #6c757d;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 0.85em;
            font-weight: 600;
        }

        .amount-highlight {
            font-size: 1.1em;
            font-weight: bold;
            margin-top: 8px;
            text-align: center;
            padding: 6px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: #f8f9fa;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8em;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f1f3f5;
            font-weight: 600;
        }

        .footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 0.8em;
        }

        .signature {
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #495057;
            margin-top: 30px;
            padding-top: 3px;
            font-weight: 600;
        }

        @media print {
            body {
                background: white;
                margin: 0;
            }
            .receipt-container {
                border: none;
                border-radius: 0;
                box-shadow: none;
                width: 105mm;
                height: 148mm;
                page-break-after: always;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt-container">
        <!-- HEADER -->
        <div class="header">
            <img alt="Logo" :src="setting?.logo_sekolah" />
            <h1>Kwitansi Pembayaran</h1>
        </div>

        <!-- INFORMASI SISWA -->
        <div class="section">
            <h2>Informasi Siswa</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nama</span>
                    <span class="info-value">{{ $student->nama }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">NIS</span>
                    <span class="info-value">{{ $student->nis }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kelas</span>
                    <span class="info-value">{{ $student->classroom->nama_kelas ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- INFORMASI PEMBAYARAN -->
        <div class="section">
            <h2>Detail Pembayaran</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Kode Transaksi</span>
                    <span class="info-value">{{ $transaction->bill->kode }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jenis Pembayaran</span>
                    <span class="info-value">{{ $transaction->bill->paymentType->nama_jenis ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tahun Ajaran</span>
                    <span class="info-value">{{ $transaction->bill->schoolYear->tahun_ajaran ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Pembayaran</span>
                    <span class="info-value">{{ $transaction->created_at->format('d F Y') }}</span>
                </div>
            </div>

            <div class="amount-highlight">
                Rp {{ number_format($transaction->nominal, 0, ',', '.') }}
            </div>

            <div class="info-grid" style="margin-top:10px;">
                <div class="info-item">
                    <span class="info-label">Total Tagihan</span>
                    <span class="info-value">Rp {{ number_format($transaction->bill->total, 0, ',', '.') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Sudah Dibayar</span>
                    <span class="info-value">Rp {{ number_format($transaction->bill->dibayar, 0, ',', '.') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Sisa Tagihan</span>
                    <span class="info-value">Rp {{ number_format($transaction->bill->sisa, 0, ',', '.') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value">{{ strtoupper($transaction->bill->status) }}</span>
                </div>
            </div>
        </div>

        <!-- TAGIHAN BELUM DIBAYAR -->
        <div class="section">
            <h2>Tagihan Belum Dibayar</h2>
            @if($unpaidBills->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Jenis Pembayaran</th>
                        <th>Jumlah</th>
                        <th>Tahun</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unpaidBills as $bill)
                    <tr>
                        <td>{{ $bill->kode }}</td>
                        <td>{{ $bill->paymentType->nama_jenis ?? '-' }}</td>
                        <td>Rp {{ number_format($bill->total_tagihan, 0, ',', '.') }}</td>
                        <td>{{ $bill->schoolYear->tahun_ajaran ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="text-align:center; font-style:italic; color:#6c757d;">
                ✅ Semua tagihan telah lunas.
            </p>
            @endif
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div>
                <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            </div>
            <div class="signature">
                <p>Petugas Keuangan</p>
                <div class="signature-line">{{ auth()->user()->name ?? '---' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
