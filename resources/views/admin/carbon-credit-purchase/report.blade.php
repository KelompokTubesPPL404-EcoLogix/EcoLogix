<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Pembelian Carbon Credit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            border-bottom: 2px solid #2c3e50;
            background: #f8f9fa;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 24px;
            color: #2c3e50;
            text-transform: uppercase;
        }
        .header p {
            color: #7f8c8d;
            margin: 5px 0;
        }
        .meta-info {
            margin-bottom: 20px;
            padding: 15px;
            background: #ecf0f1;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            word-wrap: break-word;
            max-width: 300px;
        }
        th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin: 20px 0;
            padding: 15px;
            background: #e8f4f8;
            border-radius: 5px;
            font-size: 14px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 20px 0;
            border-top: 1px solid #eee;
            background: white;
        }
        .signature-section {
            margin-top: 50px;
            margin-bottom: 100px;
            page-break-inside: avoid;
        }
        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
            margin-top: 30px;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            margin-bottom: 10px;
            height: 40px;
        }
        .clear {
            clear: both;
        }
        .content-wrapper {
            padding-bottom: 100px;
        }
        .summary-section {
            margin-bottom: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #28a745;
        }
        .summary-title {
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        .summary-item {
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .summary-item-title {
            font-weight: bold;
            color: #2c3e50;
        }
        .summary-item-value {
            font-size: 14px;
            color: #28a745;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #ffc107;
            color: #212529;
        }
        .status-completed {
            background-color: #28a745;
            color: white;
        }
        .status-cancelled {
            background-color: #dc3545;
            color: white;
        }
        .currency {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="header">
            <h1>Laporan Pembelian Carbon Credit</h1>
            <p>Tanggal Cetak: {{ date('d/m/Y') }}</p>
        </div>

        <div class="meta-info">
            <p><strong>Nomor Dokumen:</strong> CC-RPT-{{ date('Ymd-His') }}</p>
            <p><strong>Perusahaan:</strong> {{ $user->perusahaan->nama_perusahaan ?? 'Tidak Tersedia' }}</p>
            <p><strong>Dibuat Oleh:</strong> {{ $user->nama }} ({{ ucfirst($user->role) }})</p>
        </div>

        <div class="summary-section">
            <div class="summary-title">Ringkasan Pembelian Carbon Credit</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-item-title">Total Transaksi</div>
                    <div class="summary-item-value">{{ $purchases->count() }} transaksi</div>
                </div>
                <div class="summary-item">
                    <div class="summary-item-title">Total Carbon Credit</div>
                    <div class="summary-item-value">{{ number_format($totalCarbon, 2) }} ton CO₂e</div>
                </div>
                <div class="summary-item">
                    <div class="summary-item-title">Total Pengeluaran</div>
                    <div class="summary-item-value">Rp {{ number_format($totalSpent, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        @if($purchasesByProvider->count() > 0)
        <div class="summary-section">
            <div class="summary-title">Pembelian Berdasarkan Penyedia</div>
            <table>
                <thead>
                    <tr>
                        <th>Penyedia</th>
                        <th>Jumlah Transaksi</th>
                        <th>Total Carbon Credit (ton CO₂e)</th>
                        <th>Total Pengeluaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchasesByProvider as $provider)
                    <tr>
                        <td>{{ $provider['nama_penyedia'] }}</td>
                        <td>{{ $provider['count'] }}</td>
                        <td style="text-align: right">{{ number_format($provider['total_carbon'], 2) }}</td>
                        <td class="currency">Rp {{ number_format($provider['total_spent'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($purchasesByMonth->count() > 0)
        <div class="summary-section">
            <div class="summary-title">Pembelian Berdasarkan Bulan</div>
            <table>
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Jumlah Transaksi</th>
                        <th>Total Carbon Credit (ton CO₂e)</th>
                        <th>Total Pengeluaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchasesByMonth as $month => $data)
                    <tr>
                        <td>{{ $month }}</td>
                        <td>{{ $data['count'] }}</td>
                        <td style="text-align: right">{{ number_format($data['total_carbon'], 2) }}</td>
                        <td class="currency">Rp {{ number_format($data['total_spent'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="summary-title">Detail Transaksi Pembelian Carbon Credit</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Pembelian</th>
                    <th>Penyedia</th>
                    <th>Jumlah (ton CO₂e)</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $index => $purchase)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $purchase->kode_pembelian }}</td>
                    <td>{{ $purchase->penyedia->nama_penyedia ?? '-' }}</td>
                    <td style="text-align: right">{{ number_format($purchase->jumlah_kompensasi / 1000, 2) }}</td>
                    <td class="currency">{{ $purchase->mata_uang }} {{ number_format($purchase->harga_satuan, 0, ',', '.') }}</td>
                    <td class="currency">Rp {{ number_format($purchase->total_harga, 0, ',', '.') }}</td>
                    <td>{{ date('d/m/Y', strtotime($purchase->tanggal_pembelian)) }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($purchase->status) }}">
                            {{ $purchase->status }}
                        </span>
                    </td>
                    <td>{{ $purchase->admin->nama ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center">Tidak ada data pembelian carbon credit</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total">
            <p>Total Pembelian: {{ number_format($totalCarbon, 2) }} ton CO₂e</p>
            <p>Total Pengeluaran: Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
            <p>Harga Rata-rata: Rp {{ number_format($averagePrice, 0, ',', '.') }} / ton CO₂e</p>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <p>Disetujui oleh:</p>
                <div class="signature-line"></div>
                <p><strong>{{ ucfirst($user->role) }}</strong></p>
                <p>{{ date('d F Y') }}</p>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Carbon Footprint</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>