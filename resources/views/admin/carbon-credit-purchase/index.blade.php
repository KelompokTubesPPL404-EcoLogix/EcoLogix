@extends('layouts.admin')

@section('title', 'Pembelian Carbon Credit')

@push('css')
<style>
    .eco-gradient {
        background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%);
    }
    .card-stats {
        border-radius: 0.5rem;
        transition: transform 0.3s;
    }
    .card-stats:hover {
        transform: translateY(-5px);
    }
    .btn-action {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .stats-icon {
        font-size: 2rem;
        opacity: 0.7;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(40, 167, 69, 0.05);
    }
    .badge-status {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }
    .badge-status-success {
        background-color: #28a745;
        color: #fff;
    }
    .badge-status-pending {
        background-color: #ffc107;
        color: #212529;
    }
    .badge-status-failed {
        background-color: #dc3545;
        color: #fff;
    }
    .chart-container {
        height: 300px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pembelian Carbon Credit</h1>
        <div>
            <a href="{{ route('admin.carbon-credit-purchase.dashboard') }}" class="btn btn-info btn-sm shadow-sm mr-2">
                <i class="bi bi-graph-up me-1"></i> Dashboard
            </a>
            <a href="{{ route('admin.carbon-credit-purchase.report') }}" class="btn btn-secondary btn-sm shadow-sm mr-2">
                <i class="bi bi-file-earmark-text me-1"></i> Laporan
            </a>
            <a href="{{ route('admin.carbon-credit-purchase.create') }}" class="btn btn-success btn-sm shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Pembelian
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 card-stats">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Carbon Credit
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalCarbon, 2) }} ton</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-tree stats-icon text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 card-stats">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pengeluaran
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalSpent, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-stack stats-icon text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 card-stats">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Harga Rata-rata
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($averagePrice, 0, ',', '.') }}/ton</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calculator stats-icon text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 card-stats">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Jumlah Transaksi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $purchases->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-receipt stats-icon text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Purchases Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 eco-gradient">
                    <h6 class="m-0 font-weight-bold text-white">Pembelian Carbon Credit Per Bulan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyPurchasesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 eco-gradient">
            <h6 class="m-0 font-weight-bold text-white">Daftar Pembelian Carbon Credit</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>Kode Pembelian</th>
                            <th>Tanggal</th>
                            <th>Perusahaan</th>
                            <th>Penyedia</th>
                            <th>Jumlah (ton)</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->kode_pembelian_carbon_credit }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->tanggal_pembelian)->format('d M Y') }}</td>
                            <td>
                                @if($purchase->perusahaan)
                                    {{ $purchase->perusahaan->nama_perusahaan }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($purchase->penyedia)
                                    {{ $purchase->penyedia->nama_penyedia }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ number_format($purchase->jumlah_kompensasi/100, 2) }}</td>
                            <td>
                                @if($purchase->penyedia)
                                    {{ $purchase->penyedia->mata_uang }} 
                                @endif
                                {{ number_format($purchase->total_harga, 0, ',', '.') }}
                            </td>
                            <td>
                                @if(isset($purchase->status_pembelian))
                                    @if($purchase->status_pembelian == 'Sukses')
                                        <span class="badge badge-status badge-status-success">Sukses</span>
                                    @elseif($purchase->status_pembelian == 'Pending')
                                        <span class="badge badge-status badge-status-pending">Pending</span>
                                    @elseif($purchase->status_pembelian == 'Gagal')
                                        <span class="badge badge-status badge-status-failed">Gagal</span>
                                    @else
                                        <span class="badge badge-status badge-secondary">{{ $purchase->status_pembelian }}</span>
                                    @endif
                                @else
                                    <span class="badge badge-status badge-status-success">Sukses</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.carbon-credit-purchase.show', $purchase->kode_pembelian_carbon_credit) }}" class="btn btn-sm btn-info btn-action me-1" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.carbon-credit-purchase.edit', $purchase->kode_pembelian_carbon_credit) }}" class="btn btn-sm btn-warning btn-action me-1" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.carbon-credit-purchase.destroy', $purchase->kode_pembelian_carbon_credit) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-action" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data pembelian carbon credit</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#dataTable').DataTable({
            "order": [[1, "desc"]], // Sort by date descending
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(disaring dari _MAX_ total data)",
                "zeroRecords": "Tidak ada data yang cocok",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });

        // Monthly Purchases Chart
        let months = [];
        let purchaseCounts = [];
        let purchaseAmounts = [];
        let purchaseValues = [];
        
        // Check if monthlyPurchases exists and is not empty
        const chartData = @json($monthlyPurchases) || {};
        
        if (Object.keys(chartData).length > 0) {
            months = Object.keys(chartData);
            purchaseCounts = months.map(month => chartData[month].count);
            purchaseAmounts = months.map(month => chartData[month].amount);
            purchaseValues = months.map(month => chartData[month].value / 1000000); // Convert to millions
        }

        const ctx = document.getElementById('monthlyPurchasesChart').getContext('2d');
        
        // Only create chart if we have data
        if (months.length > 0) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Jumlah Transaksi',
                            data: purchaseCounts,
                            backgroundColor: 'rgba(255, 193, 7, 0.5)',
                            borderColor: 'rgba(255, 193, 7, 1)',
                            borderWidth: 1,
                            yAxisID: 'y1'
                        },
                        {
                            label: 'Jumlah Carbon Credit (ton)',
                            data: purchaseAmounts,
                            backgroundColor: 'rgba(40, 167, 69, 0.5)',
                            borderColor: 'rgba(40, 167, 69, 1)',
                            borderWidth: 1,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Nilai (Juta Rupiah)',
                            data: purchaseValues,
                            backgroundColor: 'rgba(13, 110, 253, 0.5)',
                            borderColor: 'rgba(13, 110, 253, 1)',
                            borderWidth: 1,
                            type: 'line',
                            yAxisID: 'y'
                        }
                    ]
                },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Jumlah & Nilai'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: 'Jumlah Transaksi'
                            }
                        }
                    }
                }
            });
        } else {
            // Display a message when no data is available
            const noDataMessage = document.createElement('div');
            noDataMessage.className = 'text-center py-5 text-muted';
            noDataMessage.innerHTML = '<i class="bi bi-bar-chart" style="font-size: 3rem;"></i><p class="mt-3">Tidak ada data pembelian untuk ditampilkan</p>';
            
            const chartContainer = document.querySelector('.chart-container');
            chartContainer.appendChild(noDataMessage);
        }
    });
</script>
@endpush
