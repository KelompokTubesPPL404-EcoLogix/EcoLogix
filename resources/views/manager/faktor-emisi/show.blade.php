@extends('layouts.manager')

@section('title', 'Detail Faktor Emisi')

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
                    <i class="bi bi-calculator me-2"></i>Detail Faktor Emisi
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>Informasi lengkap tentang faktor emisi: {{ $faktorEmisi->kategori_emisi_karbon }} - {{ $faktorEmisi->sub_kategori }}
                </p>
            </div>
            <a href="{{ route('manager.faktor-emisi.index') }}" class="btn btn-outline-success shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Faktor Emisi
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Summary Card -->
        <div class="col-xl-4 col-lg-3 mb-4">
            <div class="card eco-card border-0 shadow-lg rounded-3">
                <div class="card-body text-center py-4">
                    <div class="detail-icon mx-auto mb-3">
                        <i class="bi bi-calculator"></i>
                    </div>
                    <h5 class="fw-bold text-success mb-1">{{ $faktorEmisi->kategori_emisi_karbon }}</h5>
                    <div class="text-muted small mb-3">{{ $faktorEmisi->sub_kategori }}</div>
                    
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-success text-white px-3 py-2">
                            {{ $faktorEmisi->nilai_faktor }} {{ $faktorEmisi->satuan }}
                        </span>
                    </div>
                    
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Kode Faktor:</span>
                            <span class="fw-bold">{{ $faktorEmisi->kode_faktor }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detail Card -->
        <div class="col-xl-8 col-lg-9">
            <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
                <div class="card-header eco-gradient py-3">
                    <h6 class="m-0 fw-bold text-success">
                        <i class="bi bi-info-circle me-2"></i>Informasi Detail Faktor Emisi
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover detail-table mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-hash text-success me-2"></i>Kode Faktor
                                    </th>
                                    <td>
                                        <span class="badge bg-success text-white px-3 py-2">{{ $faktorEmisi->kode_faktor }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-tag text-success me-2"></i>Kategori Emisi Karbon
                                    </th>
                                    <td class="fw-bold">{{ $faktorEmisi->kategori_emisi_karbon }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-layers text-success me-2"></i>Sub Kategori
                                    </th>
                                    <td>{{ $faktorEmisi->sub_kategori }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-calculator text-success me-2"></i>Nilai Faktor
                                    </th>
                                    <td>{{ $faktorEmisi->nilai_faktor }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-rulers text-success me-2"></i>Satuan
                                    </th>
                                    <td>{{ $faktorEmisi->satuan }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-end py-3">
                        <a href="{{ route('manager.faktor-emisi.edit', $faktorEmisi->kode_faktor) }}" class="btn btn-warning btn-action shadow-sm me-3">
                            <i class="bi bi-pencil me-1"></i> Edit Faktor Emisi
                        </a>
                        <form action="{{ route('manager.faktor-emisi.destroy', $faktorEmisi->kode_faktor) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus faktor emisi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-action shadow-sm">
                                <i class="bi bi-trash me-1"></i> Hapus Faktor Emisi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection