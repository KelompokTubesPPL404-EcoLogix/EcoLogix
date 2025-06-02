@extends('layouts.admin')

@section('title', 'Detail Pembelian Carbon Credit')

@push('css')
<style>
    .eco-gradient {
        background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%);
    }
    .form-label {
        font-weight: 500;
        color: #2c3e50;
    }
    .btn-back {
        transition: all 0.2s;
    }
    .btn-back:hover {
        transform: translateX(-3px);
    }
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .detail-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
    }
    .detail-item:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-weight: 500;
        color: #6c757d;
    }
    .detail-value {
        font-weight: 600;
        color: #212529;
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
    .detail-section {
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #28a745;
    }
    .detail-section-title {
        color: #28a745;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    .impact-card {
        text-align: center;
        padding: 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s;
    }
    .impact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .impact-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: #28a745;
    }
    .impact-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #28a745;
        margin-bottom: 0.25rem;
    }
    .impact-label {
        color: #6c757d;
        font-size: 0.875rem;
    }
    .qr-code {
        max-width: 150px;
        margin: 0 auto;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <a href="{{ route('admin.carbon-credit-purchase.index') }}" class="btn btn-sm btn-outline-primary btn-back mr-2">
                    <i class="bi bi-arrow-left"></i>
                </a>
                Detail Pembelian Carbon Credit
            </h1>
            <p class="text-muted">Informasi detail mengenai pembelian carbon credit</p>
        </div>
        <div>
            <a href="{{ route('admin.carbon-credit-purchase.edit', $purchase->kode_pembelian_carbon_credit) }}" class="btn btn-warning btn-sm shadow-sm">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Purchase Details -->
            <div class="card shadow">
                <div class="card-header py-3 eco-gradient">
                    <h6 class="m-0 font-weight-bold text-white">Informasi Pembelian</h6>
                </div>
                <div class="card-body">
                    <div class="detail-section">
                        <h5 class="detail-section-title">
                            <i class="bi bi-info-circle me-2"></i>Informasi Umum
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label">Kode Pembelian</div>
                                    <div class="detail-value">{{ $purchase->kode_pembelian_carbon_credit }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Tanggal Pembelian</div>
                                    <div class="detail-value">{{ \Carbon\Carbon::parse($purchase->tanggal_pembelian)->format('d F Y') }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Status</div>
                                    <div class="detail-value">
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
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label">Kode Kompensasi</div>
                                    <div class="detail-value">{{ $purchase->kode_kompensasi }}
                                        @if($purchase->kompensasiEmisiCarbon)
                                            <small class="text-muted d-block">{{ $purchase->kompensasiEmisiCarbon->tanggal_kompensasi }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Admin</div>
                                    <div class="detail-value">
                                        @if($purchase->admin)
                                            {{ $purchase->admin->nama }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Tanggal Verifikasi</div>
                                    <div class="detail-value">
                                        @if(isset($purchase->tanggal_verifikasi))
                                            {{ \Carbon\Carbon::parse($purchase->tanggal_verifikasi)->format('d F Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h5 class="detail-section-title">
                            <i class="bi bi-building me-2"></i>Informasi Perusahaan & Penyedia
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label">Perusahaan</div>
                                    <div class="detail-value">
                                        @if($purchase->perusahaan)
                                            {{ $purchase->perusahaan->nama_perusahaan }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Kode Perusahaan</div>
                                    <div class="detail-value">{{ $purchase->kode_perusahaan }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label">Penyedia Carbon Credit</div>
                                    <div class="detail-value">
                                        @if($purchase->penyedia)
                                            {{ $purchase->penyedia->nama_penyedia }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Kode Penyedia</div>
                                    <div class="detail-value">{{ $purchase->kode_penyedia }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h5 class="detail-section-title">
                            <i class="bi bi-cash me-2"></i>Detail Transaksi
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label">Jumlah Kompensasi</div>
                                    <div class="detail-value">{{ number_format($purchase->jumlah_kompensasi, 2) }} ton</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Harga Per Ton</div>
                                    <div class="detail-value">
                                        @if($purchase->penyedia)
                                            {{ $purchase->penyedia->mata_uang }} 
                                        @endif
                                        {{ number_format($purchase->harga_per_ton, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Total Harga</div>
                                    <div class="detail-value">
                                        @if($purchase->penyedia)
                                            {{ $purchase->penyedia->mata_uang }} 
                                        @endif
                                        {{ number_format($purchase->total_harga, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label">Deskripsi</div>
                                    <div class="detail-value">{{ $purchase->deskripsi }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Environmental Impact -->
            <div class="card shadow">
                <div class="card-header py-3 eco-gradient">
                    <h6 class="m-0 font-weight-bold text-white">Dampak Lingkungan</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="impact-card bg-light">
                                <div class="impact-icon">
                                    <i class="bi bi-tree"></i>
                                </div>
                                <div class="impact-value">{{ $impact['trees_equivalent'] }}</div>
                                <div class="impact-label">Pohon setara</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="impact-card bg-light">
                                <div class="impact-icon">
                                    <i class="bi bi-lightning"></i>
                                </div>
                                <div class="impact-value">{{ number_format($impact['energy_savings']) }}</div>
                                <div class="impact-label">kWh energi terhemat</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="impact-card bg-light">
                                <div class="impact-icon">
                                    <i class="bi bi-cloud-minus"></i>
                                </div>
                                <div class="impact-value">{{ number_format($impact['emission_reduction'], 2) }}</div>
                                <div class="impact-label">kg CO₂ dikurangi</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <p class="text-muted small">* Estimasi berdasarkan rata-rata industri untuk dampak carbon offset.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Certificate Card -->
            <div class="card shadow">
                <div class="card-header py-3 eco-gradient">
                    <h6 class="m-0 font-weight-bold text-white">Sertifikat Carbon Credit</h6>
                </div>
                <div class="card-body text-center">
                    <div class="qr-code mb-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('admin.carbon-credit-purchase.show', $purchase->kode_pembelian_carbon_credit)) }}" alt="QR Code" class="img-fluid">
                    </div>
                    <p class="mb-3">Scan QR code untuk memverifikasi sertifikat carbon credit.</p>
                    <a href="#" class="btn btn-success btn-block" onclick="window.print();">
                        <i class="bi bi-printer me-1"></i> Cetak Sertifikat
                    </a>
                </div>
            </div>

            <!-- Bukti Pembayaran Card -->
            <div class="card shadow">
                <div class="card-header py-3 eco-gradient">
                    <h6 class="m-0 font-weight-bold text-white">Bukti Pembayaran</h6>
                </div>
                <div class="card-body text-center">
                    @if($purchase->bukti_pembayaran)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $purchase->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="img-fluid">
                    </div>
                    <a href="{{ asset('storage/' . $purchase->bukti_pembayaran) }}" class="btn btn-primary btn-block" download>
                        <i class="bi bi-download me-1"></i> Download Bukti Pembayaran
                    </a>
                    @else
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle me-1"></i> Bukti pembayaran tidak tersedia.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<style>
    /* Timeline Styling */
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    .timeline-marker {
        position: absolute;
        left: -30px;
        width: 15px;
        height: 15px;
        border-radius: 50%;
    }
    .timeline-marker::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 50%;
        transform: translateX(-50%);
        width: 2px;
        height: calc(100% - 15px);
        background-color: #e9ecef;
    }
    .timeline-item:last-child .timeline-marker::before {
        display: none;
    }
    .timeline-title {
        margin-bottom: 0.25rem;
        font-weight: 600;
    }
    .timeline-date {
        margin-bottom: 0;
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    /* Print Styling */
    @media print {
        .btn-back, .btn-warning, .btn-primary, .btn-success, nav, footer, .sidebar {
            display: none !important;
        }
        .container-fluid {
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
            break-inside: avoid;
        }
        .eco-gradient {
            background: #f8f9fa !important;
            color: #28a745 !important;
        }
        .col-lg-8, .col-lg-4 {
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
        }
    }
</style>
@endpush
