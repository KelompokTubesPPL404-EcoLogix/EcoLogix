@extends('layouts.manager')

@section('title', 'Detail Pembelian Carbon Credit')

@push('css')
<style>
    .eco-gradient {
        background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%);
    }
    .detail-card {
        border-left: 4px solid #28a745;
        transition: all 0.3s ease;
    }
    .detail-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15);
    }
    .detail-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 3px solid #28a745;
    }
    .detail-label {
        font-weight: 600;
        color: #28a745;
    }
    .detail-value {
        font-size: 1.05rem;
    }
    .detail-section {
        border-radius: 0.5rem;
        background: rgba(40, 167, 69, 0.03);
        border-left: 3px solid #28a745;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Enhanced Detail Header -->
    <div class="detail-header p-4 rounded-3 mb-4">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-2 text-success fw-bold">
                    <i class="bi bi-card-heading me-2"></i>Detail Pembelian Carbon Credit
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>Informasi lengkap pembelian carbon credit {{ $pembelian->kode_pembelian_carbon_credit }}
                </p>
            </div>
            <div>
                <a href="{{ route('manager.pembelian-carbon-credit.edit', $pembelian->kode_pembelian_carbon_credit) }}" class="btn btn-warning shadow-sm me-2">
                    <i class="bi bi-pencil-fill me-1"></i> Edit Data
                </a>
                <a href="{{ route('manager.pembelian-carbon-credit.index') }}" class="btn btn-outline-success shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Detail Card -->
            <div class="card detail-card shadow-sm mb-4">
                <div class="card-header eco-gradient text-white py-3">
                    <h6 class="m-0 fw-bold text-white">
                        <i class="bi bi-card-list me-2"></i>Informasi Pembelian Carbon Credit
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3 pb-3 border-bottom">
                        <div class="col-md-4">
                            <p class="detail-label mb-1"><i class="bi bi-upc me-1"></i>Kode Pembelian:</p>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-value">{{ $pembelian->kode_pembelian_carbon_credit }}</p>
                        </div>
                    </div>

                    <div class="row mb-3 pb-3 border-bottom">
                        <div class="col-md-4">
                            <p class="detail-label mb-1"><i class="bi bi-calendar-date me-1"></i>Tanggal Pembelian:</p>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-value">{{ date('d F Y', strtotime($pembelian->tanggal_pembelian)) }}</p>
                        </div>
                    </div>

                    <div class="row mb-3 pb-3 border-bottom">
                        <div class="col-md-4">
                            <p class="detail-label mb-1"><i class="bi bi-upc-scan me-1"></i>Kode Kompensasi:</p>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-value">{{ $pembelian->kode_kompensasi }}</p>
                        </div>
                    </div>

                    <div class="row mb-3 pb-3 border-bottom">
                        <div class="col-md-4">
                            <p class="detail-label mb-1"><i class="bi bi-calculator me-1"></i>Jumlah Kompensasi:</p>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-value">{{ number_format($pembelian->jumlah_kompensasi, 2) }} ton</p>
                        </div>
                    </div>

                    <div class="row mb-3 pb-3 border-bottom">                        <div class="col-md-4">
                            <p class="detail-label mb-1"><i class="bi bi-building me-1"></i>Penyedia Carbon Credit:</p>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-value">
                                @if($pembelian->penyedia)
                                    {{ $pembelian->penyedia->nama_penyedia }} 
                                    <span class="badge bg-info ms-1">{{ $pembelian->kode_penyedia }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">Tidak ada data penyedia</span>
                                    <small class="d-block text-muted">Kode Penyedia: {{ $pembelian->kode_penyedia ?? 'Tidak tersedia' }}</small>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3 pb-3 border-bottom">
                        <div class="col-md-4">
                            <p class="detail-label mb-1"><i class="bi bi-cash-coin me-1"></i>Total Harga:</p>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-value">{{ number_format($pembelian->total_harga, 2) }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="detail-label mb-1"><i class="bi bi-card-text me-1"></i>Deskripsi:</p>
                        </div>
                        <div class="col-md-8">
                            <div class="detail-section p-3">
                                <p class="detail-value">{{ $pembelian->deskripsi }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Bukti Pembayaran Card -->
            <div class="card detail-card shadow-sm mb-4">
                <div class="card-header eco-gradient text-white py-3">
                    <h6 class="m-0 fw-bold text-white">
                        <i class="bi bi-receipt me-2"></i>Bukti Pembayaran
                    </h6>
                </div>
                <div class="card-body p-4 text-center">
                    @if($pembelian->bukti_pembayaran)
                        <img src="{{ asset('storage/' . $pembelian->bukti_pembayaran) }}" class="img-fluid img-thumbnail rounded mb-3" alt="Bukti Pembayaran">
                        <div class="d-grid gap-2">
                            <a href="{{ asset('storage/' . $pembelian->bukti_pembayaran) }}" class="btn btn-outline-primary" target="_blank">
                                <i class="bi bi-eye-fill me-1"></i>Lihat Ukuran Penuh
                            </a>
                        </div>
                    @else
                        <div class="p-5 text-center">
                            <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                            <p class="mt-3 text-muted">Bukti pembayaran tidak tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Card -->
            <div class="card detail-card shadow-sm mb-4">
                <div class="card-header eco-gradient text-white py-3">
                    <h6 class="m-0 fw-bold text-white">
                        <i class="bi bi-gear me-2"></i>Aksi
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('manager.pembelian-carbon-credit.edit', $pembelian->kode_pembelian_carbon_credit) }}" class="btn btn-warning">
                            <i class="bi bi-pencil-square me-1"></i>Edit Data Pembelian
                        </a>
                        <a href="{{ route('manager.pembelian-carbon-credit.index') }}" class="btn btn-outline-success">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                        </a>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-1"></i>Hapus Data Pembelian
                        </button>
                    </div>
                </div>
            </div>

            <!-- Created/Updated Info -->
            <div class="card detail-card shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="small text-muted">
                        <p class="mb-1"><i class="bi bi-clock-history me-1"></i>Dibuat: {{ $pembelian->created_at->format('d M Y, H:i') }}</p>
                        <p class="mb-0"><i class="bi bi-clock-history me-1"></i>Diperbarui: {{ $pembelian->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle me-1"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menghapus data pembelian carbon credit ini?</p>
                <p class="mb-0"><strong>Kode:</strong> {{ $pembelian->kode_pembelian_carbon_credit }}</p>
                <p class="mb-0"><strong>Tanggal:</strong> {{ date('d F Y', strtotime($pembelian->tanggal_pembelian)) }}</p>
                <p><strong>Total Harga:</strong> {{ number_format($pembelian->total_harga, 2) }}</p>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>Tindakan ini tidak dapat dibatalkan!
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('manager.pembelian-carbon-credit.destroy', $pembelian->kode_pembelian_carbon_credit) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash-fill me-1"></i>Ya, Hapus Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
