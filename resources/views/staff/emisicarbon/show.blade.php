@extends('layouts.staff')

@section('title', 'Detail Emisi Karbon')

@push('css')
    <style>
        .eco-gradient {
            background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%);
        }
        .eco-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 3px solid #28a745;
        }
        .carbon-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 1.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
        }
        .icon-circle {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #28a745, #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
        }
        .icon-circle-large {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #28a745, #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }
        .table tbody tr:hover {
            background-color: rgba(40, 167, 69, 0.02);
        }
        .table td {
            vertical-align: middle;
        }
        .card {
            border-radius: 12px;
            overflow: hidden;
        }
        .card-header {
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.2rem;
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .text-success {
            color: #28a745 !important;
        }
        .border-bottom {
            border-bottom: 1px solid rgba(0,0,0,0.05) !important;
        }
        .status-info .badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        .price-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid rgba(40, 167, 69, 0.1);
        }
        .alert-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 5px 20px rgba(220, 53, 69, 0.1);
            padding: 2rem;
        }
        .alert-custom .icon-circle-large {
            background: linear-gradient(45deg, #dc3545, #c82333);
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
                <a href="{{ route('staff.emisicarbon.index') }}" class="btn btn-outline-success shadow-sm px-4">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Emisi
                </a>
            </div>
        </div>
    </div>

    @if(isset($emisiCarbon) && $emisiCarbon)
        <div class="row">
            <!-- Main Content - Informasi Emisi Karbon -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle me-3">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <h5 class="mb-0 text-success fw-semibold">Informasi Emisi Karbon</h5>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr class="border-bottom">
                                        <td class="py-3 px-4 text-muted fw-medium" style="width: 200px;">
                                            <i class="bi bi-hash me-2"></i>Kode Emisi
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill">{{ $emisiCarbon->kode_emisi_carbon }}</span>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="py-3 px-4 text-muted fw-medium">
                                            <i class="bi bi-tags me-2"></i>Kategori Emisi
                                        </td>
                                        <td class="py-3 px-4 fw-medium">{{ $emisiCarbon->kategori_emisi_karbon ?? 'N/A' }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="py-3 px-4 text-muted fw-medium">
                                            <i class="bi bi-tag me-2"></i>Sub Kategori
                                        </td>
                                        <td class="py-3 px-4">{{ $emisiCarbon->sub_kategori ?? 'N/A' }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="py-3 px-4 text-muted fw-medium">
                                            <i class="bi bi-card-text me-2"></i>Deskripsi
                                        </td>
                                        <td class="py-3 px-4">{{ $emisiCarbon->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="py-3 px-4 text-muted fw-medium">
                                            <i class="bi bi-activity me-2"></i>Nilai Aktivitas
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="fw-semibold">{{ number_format($emisiCarbon->nilai_aktivitas, 2) }}</span>
                                            <span class="text-muted">{{ $emisiCarbon->faktorEmisi ? $emisiCarbon->faktorEmisi->satuan : '' }}</span>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="py-3 px-4 text-muted fw-medium">
                                            <i class="bi bi-calculator me-2"></i>Faktor Emisi
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($emisiCarbon->faktorEmisi)
                                                <div>{{ $emisiCarbon->faktorEmisi->nama_kegiatan ?? $emisiCarbon->faktorEmisi->sub_kategori }}</div>
                                                <small class="text-success fw-semibold">{{ number_format($emisiCarbon->faktorEmisi->nilai_faktor, 4) }} {{ $emisiCarbon->faktorEmisi->satuan }}</small>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="py-3 px-4 text-muted fw-medium">
                                            <i class="bi bi-cloud me-2"></i>Total Emisi Karbon
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="fw-bold text-success fs-5">{{ number_format($emisiCarbon->kadar_emisi_karbon, 2) }}</span>
                                            <span class="text-muted">kg CO₂e</span>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="py-3 px-4 text-muted fw-medium">
                                            <i class="bi bi-circle-fill me-2"></i>Status
                                        </td>
                                        <td class="py-3 px-4">
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
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="py-3 px-4 text-muted fw-medium">
                                            <i class="bi bi-person me-2"></i>Staff Input
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="badge bg-primary text-white px-3 py-1">{{ $emisiCarbon->staff ? $emisiCarbon->staff->nama : 'N/A' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 text-muted fw-medium">
                                            <i class="bi bi-clock me-2"></i>Terakhir Diperbarui
                                        </td>
                                        <td class="py-3 px-4">
                                            <i class="bi bi-clock me-1 text-muted"></i>{{ $emisiCarbon->updated_at ? $emisiCarbon->updated_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') . ' WIB' : 'N/A' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0 py-3">
                        <div class="d-flex gap-2">
                            @if($emisiCarbon->status != 'approved')
                                <a href="{{ route('staff.emisicarbon.edit', $emisiCarbon->kode_emisi_carbon) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Data Emisi
                                </a>
                            @endif
                            <button type="button" class="btn btn-danger" onclick="deleteEmisi()">
                                <i class="bi bi-trash me-1"></i> Hapus Emisi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Summary Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="icon-circle-large mx-auto mb-3">
                                <i class="bi bi-cloud"></i>
                            </div>
                            <h4 class="fw-bold text-dark">{{ $emisiCarbon->kode_emisi_carbon }}</h4>
                            <p class="text-muted mb-3">Data Emisi Karbon</p>
                        </div>
                        
                        <div class="status-info mb-4">
                            @if($emisiCarbon->status == 'approved')
                            <span class="badge bg-success text-white px-4 py-2 fs-6">
                                <i class="bi bi-check-circle-fill me-2"></i> Aktif
                            </span>
                            @elseif($emisiCarbon->status == 'rejected')
                            <span class="badge bg-danger text-white px-4 py-2 fs-6">
                                <i class="bi bi-x-circle-fill me-2"></i> Ditolak
                            </span>
                            @else
                            <span class="badge bg-warning text-dark px-4 py-2 fs-6">
                                <i class="bi bi-hourglass-split me-2"></i> Pending
                            </span>
                            @endif
                        </div>

                        <div class="price-info">
                            <div class="text-muted">Total Emisi Karbon:</div>
                            <div class="h3 fw-bold text-success mb-1">{{ number_format($emisiCarbon->kadar_emisi_karbon, 2) }}</div>
                            <div class="text-muted">kg CO₂e</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger alert-custom" role="alert">
            <div class="d-flex align-items-center">
                <div class="icon-circle-large me-4">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-2">Data Tidak Ditemukan</h4>
                    <p class="mb-0">Data emisi karbon yang Anda cari tidak ditemukan dalam sistem. Silakan periksa kembali atau hubungi administrator.</p>
                </div>
            </div>
            <div class="mt-3 text-end">
                <a href="{{ route('staff.emisicarbon.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    @endif
</div>

<script>
function deleteEmisi() {
    if (confirm('Apakah Anda yakin ingin menghapus data emisi karbon ini?')) {
        // Create a form to submit delete request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('staff.emisicarbon.destroy', $emisiCarbon->kode_emisi_carbon ?? '') }}";
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = "{{ csrf_token() }}";
        form.appendChild(csrfInput);
        
        // Add method spoofing for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection