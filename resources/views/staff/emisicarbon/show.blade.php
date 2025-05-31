@extends('layouts.staff')

@section('title', 'Detail Emisi Karbon')

@push('css')
    <style>
        .eco-gradient {
            background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%);
        }
        .eco-card {
            border-left: 4px solid #28a745;
            transition: all 0.3s ease;
        }
        .eco-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15);
        }
        .eco-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 3px solid #28a745;
        }
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-left: 4px solid #28a745;
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
        }
        .stats-icon {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .detail-item {
            padding: 1rem;
            border-radius: 0.5rem;
            background-color: #f8f9fa;
            margin-bottom: 1rem;
            border-left: 3px solid #28a745;
        }
        .detail-item .label {
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .detail-item .value {
            font-weight: 500;
        }
        .detail-icon {
            margin-right: 0.5rem;
            color: #28a745;
        }
        .carbon-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .btn-action {
            padding: 0.25rem 0.5rem;
            margin: 0 0.1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        .btn-action:hover {
            transform: translateY(-1px);
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Enhanced Eco Header Section -->
    <div class="eco-header p-4 rounded-3 mb-4">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-2 text-success fw-bold">
                    <i class="bi bi-eye me-2"></i>Detail Emisi Karbon
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>Informasi lengkap mengenai data emisi karbon
                </p>
            </div>
            <div class="d-flex align-items-center">
                <div class="carbon-badge me-3">
                    <i class="bi bi-hash me-1"></i>{{ $emisiCarbon->kode_emisi_carbon ?? 'N/A' }}
                </div>
                <a href="{{ route('staff.emisicarbon.index') }}" class="btn btn-outline-secondary shadow-sm px-4">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    @if(isset($emisiCarbon) && $emisiCarbon)
        <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
            <div class="card-header eco-gradient text-white py-3">
                <h6 class="m-0 fw-bold">
                    <i class="bi bi-clipboard-data me-2"></i>Data Emisi Karbon {{ $emisiCarbon->kode_emisi_carbon }}
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <div class="label">
                                <i class="bi bi-calendar-date detail-icon"></i>Tanggal Emisi
                            </div>
                            <div class="value">
                                {{ $emisiCarbon->tanggal_emisi ? \Carbon\Carbon::parse($emisiCarbon->tanggal_emisi)->timezone('Asia/Jakarta')->format('d M Y') . ' WIB' : 'N/A' }}
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="label">
                                <i class="bi bi-tags detail-icon"></i>Kategori Emisi
                            </div>
                            <div class="value">
                                {{ $emisiCarbon->kategori_emisi_karbon ?? 'N/A' }}
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="label">
                                <i class="bi bi-tag detail-icon"></i>Sub Kategori
                            </div>
                            <div class="value">
                                {{ $emisiCarbon->sub_kategori ?? 'N/A' }}
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="label">
                                <i class="bi bi-card-text detail-icon"></i>Deskripsi
                            </div>
                            <div class="value">
                                {{ $emisiCarbon->deskripsi ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="detail-item">
                            <div class="label">
                                <i class="bi bi-calculator detail-icon"></i>Faktor Emisi
                            </div>
                            <div class="value">
                                {{ $emisiCarbon->faktorEmisi ? ($emisiCarbon->faktorEmisi->nama_kegiatan ?? $emisiCarbon->faktorEmisi->sub_kategori) : 'N/A' }} 
                                <span class="badge bg-light text-success">
                                    {{ $emisiCarbon->faktorEmisi ? number_format($emisiCarbon->faktorEmisi->nilai_faktor, 2) : '' }} 
                                    {{ $emisiCarbon->faktorEmisi ? $emisiCarbon->faktorEmisi->satuan : '' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="label">
                                <i class="bi bi-activity detail-icon"></i>Nilai Aktivitas
                            </div>
                            <div class="value">
                                {{ number_format($emisiCarbon->nilai_aktivitas, 2) ?? 'N/A' }} 
                                {{ $emisiCarbon->faktorEmisi ? $emisiCarbon->faktorEmisi->satuan : '' }}
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="label">
                                <i class="bi bi-cloud detail-icon"></i>Total Emisi
                            </div>
                            <div class="value fs-4 fw-bold text-success">
                                {{ number_format($emisiCarbon->kadar_emisi_karbon, 2) ?? 'N/A' }} kg CO₂e
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="label">
                                <i class="bi bi-circle-fill detail-icon"></i>Status
                            </div>
                            <div class="value">
                                @if($emisiCarbon->status == 'approved')
                                <span class="badge bg-success text-white px-3 py-2">
                                    <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                </span>
                                @elseif($emisiCarbon->status == 'rejected')
                                <span class="badge bg-danger text-white px-3 py-2">
                                    <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                </span>
                                @else
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="bi bi-hourglass-split me-1"></i> Pending
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light py-3 d-flex justify-content-between">
                <a href="{{ route('staff.emisicarbon.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
                @if($emisiCarbon->status != 'approved')
                    <a href="{{ route('staff.emisicarbon.edit', $emisiCarbon->kode_emisi_carbon) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-square me-1"></i> Edit Data
                    </a>
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-danger shadow-sm p-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                <div>
                    <h5 class="fw-bold mb-1">Data Tidak Ditemukan</h5>
                    <p class="mb-0">Data emisi karbon yang Anda cari tidak ditemukan dalam sistem.</p>
                </div>
            </div>
        </div>
        <a href="{{ route('staff.emisicarbon.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    @endif
</div>
@endsection