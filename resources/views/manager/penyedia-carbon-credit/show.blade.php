@extends('layouts.manager')

@section('title', 'Detail Penyedia Carbon Credit')

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
        .detail-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .detail-table th {
            background-color: rgba(40, 167, 69, 0.05);
            width: 30%;
            vertical-align: middle;
        }
        .detail-icon {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }
        .btn-action {
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            margin-right: 0.5rem;
        }
        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
                    <i class="bi bi-building-check me-2"></i>Detail Penyedia Carbon Credit
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>Informasi lengkap mengenai penyedia carbon credit
                </p>
            </div>
            <a href="{{ route('manager.penyedia-carbon-credit.index') }}" class="btn btn-outline-success shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Penyedia
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-9">
            <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
                <div class="card-header eco-gradient py-3">
                    <h6 class="m-0 fw-bold text-success">
                        <i class="bi bi-info-circle me-2"></i>Informasi Penyedia Carbon Credit
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover detail-table mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-hash text-success me-2"></i>Kode Penyedia
                                    </th>
                                    <td>
                                        <span class="badge bg-success text-white px-3 py-2">{{ $penyedia->kode_penyedia }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-building text-success me-2"></i>Nama Penyedia
                                    </th>
                                    <td class="fw-bold">{{ $penyedia->nama_penyedia }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-card-text text-success me-2"></i>Deskripsi
                                    </th>
                                    <td>{{ $penyedia->deskripsi }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-cash-coin text-success me-2"></i>Harga per Ton
                                    </th>
                                    <td class="fw-bold">
                                        {{ number_format($penyedia->harga_per_ton, 2) }} 
                                        <span class="badge bg-light text-dark border">{{ $penyedia->mata_uang }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-building text-success me-2"></i>Perusahaan
                                    </th>
                                    <td>
                                        @if($penyedia->perusahaan)
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-light text-success border border-success me-2">
                                                    {{ $penyedia->perusahaan->kode_perusahaan }}
                                                </span>
                                                <span class="fw-bold">{{ $penyedia->perusahaan->nama_perusahaan }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">Tidak terkait dengan perusahaan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-circle-fill text-success me-2"></i>Status
                                    </th>
                                    <td>
                                        @if($penyedia->is_active)
                                            <span class="badge bg-success text-white px-3 py-2">
                                                <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-danger text-white px-3 py-2">
                                                <i class="bi bi-x-circle-fill me-1"></i> Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-calendar-plus text-success me-2"></i>Tanggal Dibuat
                                    </th>
                                    <td>
                                        <i class="bi bi-clock-history me-1 text-muted"></i>
                                        {{ $penyedia->created_at->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') }} WIB
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-calendar-check text-success me-2"></i>Terakhir Diperbarui
                                    </th>
                                    <td>
                                        <i class="bi bi-clock me-1 text-muted"></i>
                                        {{ $penyedia->updated_at->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') }} WIB
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-end py-3">
                        <a href="{{ route('manager.penyedia-carbon-credit.edit', $penyedia->kode_penyedia) }}" class="btn btn-warning btn-action shadow-sm me-3">
                            <i class="bi bi-pencil-square me-1"></i> Edit Data Penyedia
                        </a>
                        <form action="{{ route('manager.penyedia-carbon-credit.destroy', $penyedia->kode_penyedia) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penyedia {{ $penyedia->nama_penyedia }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-action shadow-sm">
                                <i class="bi bi-trash me-1"></i> Hapus Penyedia
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-3">
            <div class="card eco-card border-0 shadow-lg mb-4 rounded-3">
                <div class="card-body text-center py-4">
                    <div class="detail-icon mx-auto mb-3">
                        <i class="bi bi-building"></i>
                    </div>
                    <h5 class="fw-bold text-success mb-1">{{ $penyedia->nama_penyedia }}</h5>
                    <div class="text-muted small mb-3">Penyedia Carbon Credit</div>
                    
                    <div class="d-flex justify-content-center mb-3">
                        @if($penyedia->is_active)
                            <span class="badge bg-success text-white px-3 py-2">
                                <i class="bi bi-check-circle-fill me-1"></i> Aktif
                            </span>
                        @else
                            <span class="badge bg-danger text-white px-3 py-2">
                                <i class="bi bi-x-circle-fill me-1"></i> Tidak Aktif
                            </span>
                        @endif
                    </div>
                    
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Harga per Ton:</span>
                            <span class="fw-bold">{{ number_format($penyedia->harga_per_ton, 2) }} {{ $penyedia->mata_uang }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection