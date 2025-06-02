<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Data Emisi Karbon Admin</title>
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
            grid-template-columns: repeat(2, 1fr);
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
        .status-approved {
            background-color: #28a745;
            color: white;
        }
        .status-rejected {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="header">
            <h1>Laporan Data Emisi Karbon (Admin)</h1>
            <p>Tanggal Cetak: {{ date('d/m/Y') }}</p>
        </div>

        <div class="meta-info">
            <p><strong>Nomor Dokumen:</strong> EMC-ADM-RPT-{{ date('Ymd-His') }}</p>
            <p><strong>Perusahaan:</strong> {{ $user->perusahaan->nama_perusahaan ?? 'Tidak Tersedia' }}</p>
            <p><strong>Dibuat Oleh:</strong> {{ $user->nama }} ({{ ucfirst($user->role) }})</p>
        </div>

        <div class="summary-section">
            <div class="summary-title">Ringkasan Emisi Karbon</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-item-title">Total Data Emisi</div>
                    <div class="summary-item-value">{{ $emisiKarbons->count() }} entri</div>
                </div>
                <div class="summary-item">
                    <div class="summary-item-title">Total Emisi Karbon</div>
                    <div class="summary-item-value">{{ number_format($totalEmisi, 2) }} ton CO₂e</div>
                </div>
            </div>
        </div>

        @if($emisiByKategori->count() > 0)
        <div class="summary-section">
            <div class="summary-title">Emisi Berdasarkan Kategori</div>
            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Jumlah Data</th>
                        <th>Total Emisi (ton CO₂e)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emisiByKategori as $kategori => $data)
                    <tr>
                        <td>{{ $kategori ?: 'Tidak Terkategori' }}</td>
                        <td>{{ $data['count'] }}</td>
                        <td style="text-align: right">{{ number_format($data['total'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($emisiByStaff->count() > 0)
        <div class="summary-section">
            <div class="summary-title">Emisi Berdasarkan Staff</div>
            <table>
                <thead>
                    <tr>
                        <th>Nama Staff</th>
                        <th>Jumlah Data</th>
                        <th>Total Emisi (ton CO₂e)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emisiByStaff as $staff => $data)
                    <tr>
                        <td>{{ $staff ?: 'Tidak Diketahui' }}</td>
                        <td>{{ $data['count'] }}</td>
                        <td style="text-align: right">{{ number_format($data['total'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($emisiByStatus->count() > 0)
        <div class="summary-section">
            <div class="summary-title">Emisi Berdasarkan Status</div>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Jumlah Data</th>
                        <th>Total Emisi (ton CO₂e)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emisiByStatus as $status => $data)
                    <tr>
                        <td>
                            @if($status == 'approved')
                                <span class="status-badge status-approved">Disetujui</span>
                            @elseif($status == 'rejected')
                                <span class="status-badge status-rejected">Ditolak</span>
                            @else
                                <span class="status-badge status-pending">Pending</span>
                            @endif
                        </td>
                        <td>{{ $data['count'] }}</td>
                        <td style="text-align: right">{{ number_format($data['total'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Emisi</th>
                    <th>Staff</th>
                    <th>Kategori</th>
                    <th>Sub Kategori</th>
                    <th>Nilai Aktivitas</th>
                    <th>Faktor Emisi</th>
                    <th>Emisi (ton CO₂e)</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($emisiKarbons as $index => $emisi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $emisi->kode_emisi_carbon }}</td>
                    <td>{{ $emisi->nama_staff ?: '-' }}</td>
                    <td>{{ $emisi->kategori_emisi_karbon ?: '-' }}</td>
                    <td>{{ $emisi->sub_kategori ?: '-' }}</td>
                    <td style="text-align: right">{{ number_format($emisi->nilai_aktivitas, 2) }}</td>
                    <td style="text-align: right">{{ number_format($emisi->faktor_emisi, 4) }}</td>
                    <td style="text-align: right">{{ number_format($emisi->kadar_emisi_ton, 2) }}</td>
                    <td>{{ date('d/m/Y', strtotime($emisi->tanggal_emisi)) }}</td>
                    <td>
                        @if($emisi->status == 'approved')
                            <span class="status-badge status-approved">Disetujui</span>
                        @elseif($emisi->status == 'rejected')
                            <span class="status-badge status-rejected">Ditolak</span>
                        @else
                            <span class="status-badge status-pending">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align: center">Tidak ada data emisi karbon</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total">
            <p>Total Emisi Karbon: {{ number_format($totalEmisi, 2) }} ton CO₂e</p>
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