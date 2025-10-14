<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran</title>
    <style>
        * {
            margin: 0;
            padding: 0;
<<<<<<< HEAD
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
=======
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            padding: 20px;
            background: #f5f5f5;
        }

        .receipt {
            max-width: 300px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border: 2px dashed #333;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #333;
            padding-bottom: 15px;
>>>>>>> 6dd1ffdf71141e01469c263304458f7832cc551f
            margin-bottom: 15px;
        }

        .header h1 {
<<<<<<< HEAD
            font-size: 1.2em;
            margin: 0;
            font-weight: 600;
            color: #212529;
        }

        .header img {
            height: 40px;
=======
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            color: #666;
>>>>>>> 6dd1ffdf71141e01469c263304458f7832cc551f
        }

        .section {
            margin-bottom: 15px;
<<<<<<< HEAD
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
=======
            font-size: 13px;
        }

        .row {
>>>>>>> 6dd1ffdf71141e01469c263304458f7832cc551f
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

<<<<<<< HEAD
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
=======
        .label {
            font-weight: bold;
        }

        .divider {
            border-top: 1px dashed #333;
            margin: 15px 0;
        }

        .amount {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            padding: 10px;
            background: #f0f0f0;
        }

        .status {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            padding: 8px;
            margin: 10px 0;
            border-radius: 4px;
        }

        .status.lunas {
            background: #d4edda;
            color: #155724;
>>>>>>> 6dd1ffdf71141e01469c263304458f7832cc551f
        }

        .status.sebagian {
            background: #fff3cd;
            color: #856404;
        }

        .status.belum {
            background: #f8d7da;
            color: #721c24;
        }

        .footer {
<<<<<<< HEAD
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
=======
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
            color: #666;
        }

        .print-btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
            border-radius: 4px;
        }

        .print-btn:hover {
            background: #0056b3;
>>>>>>> 6dd1ffdf71141e01469c263304458f7832cc551f
        }

        @media print {
            body {
                background: white;
<<<<<<< HEAD
                margin: 0;
=======
                padding: 0;
>>>>>>> 6dd1ffdf71141e01469c263304458f7832cc551f
            }
            .receipt {
                border: none;
<<<<<<< HEAD
                border-radius: 0;
                box-shadow: none;
                width: 105mm;
                height: 148mm;
                page-break-after: always;
=======
                max-width: 100%;
            }
            .print-btn {
                display: none;
>>>>>>> 6dd1ffdf71141e01469c263304458f7832cc551f
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- HEADER -->
        <div class="header">
            <h1>SMK AL-AZHAR</h1>
            <p>Sistem Keuangan Siswa</p>
            <p>STRUK PEMBAYARAN</p>
        </div>

        <!-- DATA TRANSAKSI -->
        <div class="section">
            <div class="row">
                <span class="label">No. Invoice:</span>
                <span>SPP2500788</span>
            </div>
            <div class="row">
                <span class="label">Tanggal:</span>
                <span>04 September 2025</span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- DATA SISWA -->
        <div class="section">
            <div class="row">
                <span class="label">Nama:</span>
                <span>Achmad Aziz Aldiansyah</span>
            </div>
            <div class="row">
                <span class="label">NIS:</span>
                <span>1105/045.016</span>
            </div>
<<<<<<< HEAD

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
=======
            <div class="row">
                <span class="label">Kelas:</span>
                <span>XII TKR</span>
>>>>>>> 6dd1ffdf71141e01469c263304458f7832cc551f
            </div>
        </div>

        <div class="divider"></div>

        <!-- DETAIL PEMBAYARAN -->
        <div class="section">
<<<<<<< HEAD
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
=======
            <div class="row">
                <span class="label">Jenis:</span>
                <span>SPP Januari</span>
            </div>
            <div class="row">
                <span class="label">Tahun Ajaran:</span>
                <span>2025/2026 Ganjil</span>
            </div>
>>>>>>> 6dd1ffdf71141e01469c263304458f7832cc551f
        </div>

        <!-- NOMINAL -->
        <div class="amount">
            Rp 200.000
        </div>

        <!-- STATUS -->
        <div class="status lunas">
            ✓ LUNAS
        </div>

        <div class="divider"></div>

        <!-- RINGKASAN -->
        <div class="section">
            <div class="row">
                <span>Total Tagihan:</span>
                <span>Rp 200.000</span>
            </div>
            <div class="row">
                <span>Dibayar:</span>
                <span>Rp 200.000</span>
            </div>
            <div class="row">
                <span class="label">Sisa:</span>
                <span class="label">Rp 0</span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- FOOTER -->
        <div class="footer">
            <p>Terima kasih atas pembayaran Anda</p>
            <p>Simpan struk ini sebagai bukti pembayaran</p>
            <p style="margin-top: 10px;">Admin: Admin</p>
        </div>

        <!-- TOMBOL CETAK -->
        <button class="print-btn" onclick="window.print()">🖨️ Cetak Struk</button>
    </div>

    <script>
        // Auto format rupiah
        document.addEventListener('DOMContentLoaded', function() {
            // Contoh data dinamis - sesuaikan dengan data dari backend
            const data = {
                invoice: 'SPP2500788',
                tanggal: '04 September 2025',
                nama: 'Achmad Aziz Aldiansyah',
                nis: '1105/045.016',
                kelas: 'XII TKR',
                jenis: 'SPP Januari',
                tahunAjaran: '2025/2026 Ganjil',
                nominal: 200000,
                status: 'Lunas', // Lunas, Dibayar Sebagian, Belum Dibayar
                totalTagihan: 200000,
                dibayar: 200000,
                sisa: 0,
                admin: 'Admin'
            };

            // Update status class
            const statusEl = document.querySelector('.status');
            if (data.status === 'Lunas') {
                statusEl.className = 'status lunas';
                statusEl.textContent = '✓ LUNAS';
            } else if (data.status === 'Dibayar Sebagian') {
                statusEl.className = 'status sebagian';
                statusEl.textContent = '⚠ DIBAYAR SEBAGIAN';
            } else {
                statusEl.className = 'status belum';
                statusEl.textContent = '✗ BELUM DIBAYAR';
            }
        });
    </script>
</body>
</html>