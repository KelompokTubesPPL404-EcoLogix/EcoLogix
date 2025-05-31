@extends('layouts.manager')

@section('title', 'Edit Faktor Emisi')

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
    .btn-action {
        padding: 0.5rem 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    .btn-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
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
                    <i class="bi bi-pencil-square me-2"></i>Edit Faktor Emisi
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>Update informasi untuk faktor emisi: {{ $faktorEmisi->kategori_emisi_karbon }} - {{ $faktorEmisi->sub_kategori }}
                </p>
            </div>
            <a href="{{ route('manager.faktor-emisi.index') }}" class="btn btn-outline-success shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Faktor Emisi
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card eco-card border-0 shadow-lg rounded-3">
                <div class="card-header eco-gradient py-3">
                    <h6 class="m-0 fw-bold text-success">
                        <i class="bi bi-pencil me-2"></i>Form Edit Faktor Emisi
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('manager.faktor-emisi.update', $faktorEmisi->kode_faktor) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="kode_faktor" class="form-label fw-bold">
                                <i class="bi bi-hash text-success me-1"></i>Kode Faktor
                            </label>
                            <input type="text" class="form-control bg-light" id="kode_faktor" value="{{ $faktorEmisi->kode_faktor }}" readonly>
                            <small class="text-muted">Kode faktor tidak dapat diubah</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="kategori_emisi_karbon" class="form-label fw-bold">
                                <i class="bi bi-tag text-success me-1"></i>Kategori Emisi Karbon
                            </label>
                            <input type="text" class="form-control @error('kategori_emisi_karbon') is-invalid @enderror" id="kategori_emisi_karbon" name="kategori_emisi_karbon" value="{{ old('kategori_emisi_karbon', $faktorEmisi->kategori_emisi_karbon) }}" required>
                            @error('kategori_emisi_karbon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="sub_kategori" class="form-label fw-bold">
                                <i class="bi bi-layers text-success me-1"></i>Sub Kategori
                            </label>
                            <input type="text" class="form-control @error('sub_kategori') is-invalid @enderror" id="sub_kategori" name="sub_kategori" value="{{ old('sub_kategori', $faktorEmisi->sub_kategori) }}" required>
                            @error('sub_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="nilai_faktor" class="form-label fw-bold">
                                <i class="bi bi-calculator text-success me-1"></i>Nilai Faktor
                            </label>
                            <input type="number" step="0.01" class="form-control @error('nilai_faktor') is-invalid @enderror" id="nilai_faktor" name="nilai_faktor" value="{{ old('nilai_faktor', $faktorEmisi->nilai_faktor) }}" required>
                            @error('nilai_faktor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="satuan" class="form-label fw-bold">
                                <i class="bi bi-rulers text-success me-1"></i>Satuan
                            </label>
                            <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan" value="{{ old('satuan', $faktorEmisi->satuan) }}" required>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-action shadow-sm">
                                <i class="bi bi-save me-1"></i> Update Faktor Emisi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-4 d-flex justify-content-center">
                <a href="{{ route('manager.faktor-emisi.show', $faktorEmisi->kode_faktor) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-eye me-1"></i> Lihat Detail Faktor Emisi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection