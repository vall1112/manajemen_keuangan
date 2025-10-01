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
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            padding: 20px;
            background: #f5f5f5;
        }

        .receipt {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border: 2px dashed #333;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #333;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            color: #666;
        }

        .section {
            margin-bottom: 15px;
            font-size: 13px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

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
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .receipt {
                border: none;
                max-width: 100%;
            }
            .print-btn {
                display: none;
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
            <div class="row">
                <span class="label">Kelas:</span>
                <span>XII TKR</span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- DETAIL PEMBAYARAN -->
        <div class="section">
            <div class="row">
                <span class="label">Jenis:</span>
                <span>SPP Januari</span>
            </div>
            <div class="row">
                <span class="label">Tahun Ajaran:</span>
                <span>2025/2026 Ganjil</span>
            </div>
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