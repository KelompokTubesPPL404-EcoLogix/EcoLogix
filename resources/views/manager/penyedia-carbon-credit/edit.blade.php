@extends('layouts.manager')

@section('title', 'Edit Penyedia Carbon Credit')

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
        .form-control:focus, .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .input-group-text {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
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
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Enhanced Eco Header Section -->
    <div class="eco-header p-4 rounded-3 mb-4">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-2 text-success fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>Edit Penyedia Carbon Credit
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>Perbarui informasi penyedia carbon credit
                </p>
            </div>
            <a href="{{ route('manager.penyedia-carbon-credit.index') }}" class="btn btn-outline-success shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Penyedia
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
                <div class="card-header eco-gradient py-3">
                    <h6 class="m-0 fw-bold text-success">
                        <i class="bi bi-building-check me-2"></i>Form Edit Penyedia Carbon Credit
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('manager.penyedia-carbon-credit.update', $penyedia->kode_penyedia) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4 row">
                            <label for="kode_penyedia" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-hash me-1"></i>Kode Penyedia
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-code-square"></i></span>
                                    <input id="kode_penyedia" type="text" class="form-control" value="{{ $penyedia->kode_penyedia }}" readonly>
                                </div>
                                <small class="text-muted mt-1">Kode penyedia tidak dapat diubah.</small>
                            </div>
                        </div>
                        
                        <div class="mb-4 row">
                            <label for="kode_perusahaan" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-building-gear me-1"></i>Perusahaan
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input id="kode_perusahaan" type="text" class="form-control" value="{{ $penyedia->perusahaan ? $penyedia->perusahaan->nama_perusahaan : 'Tidak terkait dengan perusahaan' }}" readonly>
                                    @if($penyedia->kode_perusahaan)
                                    <span class="input-group-text bg-light">{{ $penyedia->kode_perusahaan }}</span>
                                    @endif
                                </div>
                                <small class="text-muted mt-1">Penyedia carbon credit terhubung dengan perusahaan ini dan tidak dapat diubah.</small>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="nama_penyedia" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-building me-1"></i>Nama Penyedia <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building-fill"></i></span>
                                    <input id="nama_penyedia" type="text" class="form-control @error('nama_penyedia') is-invalid @enderror" name="nama_penyedia" value="{{ old('nama_penyedia', $penyedia->nama_penyedia) }}" required>
                                    @error('nama_penyedia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="deskripsi" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-card-text me-1"></i>Deskripsi <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                    <textarea id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="4" required>{{ old('deskripsi', $penyedia->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="harga_per_ton" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-cash-coin me-1"></i>Harga per Ton <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-cash-coin"></i></span>
                                    <input id="harga_per_ton" type="number" step="0.01" class="form-control @error('harga_per_ton') is-invalid @enderror" name="harga_per_ton" value="{{ old('harga_per_ton', $penyedia->harga_per_ton) }}" required>
                                    @error('harga_per_ton')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted mt-1">Harga per ton carbon credit dalam satuan mata uang yang dipilih</small>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="mata_uang" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-currency-exchange me-1"></i>Mata Uang <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-currency-exchange"></i></span>
                                    <select id="mata_uang" class="form-select @error('mata_uang') is-invalid @enderror" name="mata_uang" required>
                                        <option value="IDR" {{ old('mata_uang', $penyedia->mata_uang) == 'IDR' ? 'selected' : '' }}>IDR - Rupiah Indonesia</option>
                                        <option value="USD" {{ old('mata_uang', $penyedia->mata_uang) == 'USD' ? 'selected' : '' }}>USD - Dolar Amerika</option>
                                        <option value="EUR" {{ old('mata_uang', $penyedia->mata_uang) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    </select>
                                    @error('mata_uang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="is_active" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-circle-fill me-1"></i>Status <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                    <select id="is_active" class="form-select @error('is_active') is-invalid @enderror" name="is_active" required>
                                        <option value="1" {{ $penyedia->is_active == 1 ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ $penyedia->is_active == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted mt-1">Status aktif menentukan apakah penyedia carbon credit ini tersedia untuk transaksi</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <a href="{{ route('manager.penyedia-carbon-credit.index') }}" class="btn btn-outline-secondary me-3">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-success px-4 shadow-sm">
                                <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Debugging information for developers -->
@if(config('app.debug'))
<script>
    console.log('Current status value:', '{{ $penyedia->is_active }}');
    console.log('Current type:', '{{ gettype($penyedia->is_active) }}');
</script>
@endif
@endsection