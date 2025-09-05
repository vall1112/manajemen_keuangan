<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi Pembayaran Keuangan Siswa</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0;
            padding: 20px;
            line-height: 1.4;
            color: #333;
            background: #f8f9fa;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,0 1000,80 0,100"/></svg>');
            background-size: cover;
        }
        
        .header h1 {
            margin: 0;
            font-size: 2.2em;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }
        
        .header .subtitle {
            margin: 8px 0 0 0;
            font-size: 1em;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .content {
            padding: 35px;
        }
        
        .student-info {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .student-info::before {
            content: '👨‍🎓';
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3em;
            opacity: 0.3;
        }
        
        .student-info h2 {
            margin: 0 0 15px 0;
            font-size: 1.4em;
            font-weight: 600;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 0.85em;
            opacity: 0.9;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 1.1em;
            font-weight: 600;
        }
        
        .payment-section {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .payment-section::before {
            content: '💳';
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3em;
            opacity: 0.3;
        }
        
        .payment-section h2 {
            margin: 0 0 20px 0;
            font-size: 1.4em;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .amount-highlight {
            font-size: 2em;
            font-weight: bold;
            text-align: center;
            margin: 15px 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .unpaid-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            color: #495057;
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 8px;
        }
        
        .section-title::before {
            content: '📋';
            font-size: 1.2em;
        }
        
        .table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 0.95em;
        }
        
        th { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 0.5px;
        }
        
        td { 
            padding: 15px 12px;
            border-bottom: 1px solid #e9ecef;
            transition: background-color 0.3s ease;
        }
        
        tr:hover td {
            background-color: #f8f9fa;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        .no-bills {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .no-bills::before {
            content: '✅';
            display: block;
            font-size: 3em;
            margin-bottom: 15px;
        }
        
        .footer {
            border-top: 2px solid #e9ecef;
            padding-top: 25px;
            margin-top: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: end;
        }
        
        .date-section {
            color: #6c757d;
        }
        
        .signature-section {
            text-align: right;
        }
        
        .officer-name {
            font-weight: bold;
            font-size: 1.1em;
            color: #495057;
            margin-top: 50px;
            border-top: 1px solid #495057;
            padding-top: 8px;
            display: inline-block;
            min-width: 200px;
        }
        
        @media print {
            body { 
                background: white; 
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .receipt-container {
                box-shadow: none;
                border-radius: 0;
            }
            
            .header, .student-info, .payment-section {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }
            
            .header {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 1.8em;
            }
            
            .footer {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .signature-section {
                text-align: left;
            }
        }
        
        .badge {
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            display: inline-block;
            margin-top: 5px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt-container">
        <div class="header">
            <h1>KWITANSI PEMBAYARAN</h1>
        </div>

        <div class="content">
            <!-- Student Information -->
            <div class="student-info">
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

            <!-- Payment Information -->
            <div class="payment-section">
                <h2>📄 Detail Pembayaran</h2>
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
                <div class="badge">LUNAS ✓</div>
            </div>

            <!-- Unpaid Bills Section -->
            <div class="unpaid-section">
                <h2 class="section-title">Tagihan Belum Dibayar</h2>
                
                @if($unpaidBills->count() > 0)
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Jenis Pembayaran</th>
                                    <th>Jumlah</th>
                                    <th>Tahun Ajaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unpaidBills as $bill)
                                    <tr>
                                        <td><strong>{{ $bill->kode }}</strong></td>
                                        <td>{{ $bill->paymentType->nama_jenis ?? '-' }}</td>
                                        <td><strong>Rp {{ number_format($bill->total_tagihan, 0, ',', '.') }}</strong></td>
                                        <td>{{ $bill->schoolYear->tahun_ajaran ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="no-bills">
                        <strong>Selamat! Semua tagihan telah lunas.</strong>
                        <p>Tidak ada tagihan yang belum dibayar saat ini.</p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="date-section">
                    <p><strong>Tanggal Cetak:</strong></p>
                    <p>{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                </div>
                <div class="signature-section">
                    <p><strong>Petugas Keuangan</strong></p>
                    <div class="officer-name">
                        {{ auth()->user()->name ?? '---' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>