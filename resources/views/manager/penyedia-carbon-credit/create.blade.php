@extends('layouts.manager')

@section('title', 'Tambah Penyedia Carbon Credit')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-building-add me-2"></i>Tambah Penyedia Carbon Credit
            <small class="text-muted fs-6">untuk {{ Auth::user()->perusahaan->nama_perusahaan }}</small>
        </h1>
        <a href="{{ route('manager.penyedia-carbon-credit.index') }}" class="btn btn-outline-success shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Penyedia
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Ada beberapa masalah dengan input Anda.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 bg-gradient-success-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-success">
                            <i class="bi bi-building me-2"></i>Form Penyedia Carbon Credit
                        </h6>
                        <span class="badge bg-success">
                            <i class="bi bi-building-gear me-1"></i>{{ Auth::user()->perusahaan->kode_perusahaan }} - {{ Auth::user()->perusahaan->nama_perusahaan }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('manager.penyedia-carbon-credit.store') }}">
                        @csrf

                        <div class="mb-4 row">
                            <label for="nama_penyedia" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-building me-1"></i>Nama Penyedia <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building-fill"></i></span>
                                    <input id="nama_penyedia" type="text" class="form-control @error('nama_penyedia') is-invalid @enderror" name="nama_penyedia" value="{{ old('nama_penyedia') }}" placeholder="Nama penyedia carbon credit" required autofocus>
                                    @error('nama_penyedia')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
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
                                    <textarea id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="4" placeholder="Deskripsi lengkap penyedia carbon credit" required>{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
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
                                    <input id="harga_per_ton" type="number" step="0.01" class="form-control @error('harga_per_ton') is-invalid @enderror" name="harga_per_ton" value="{{ old('harga_per_ton') }}" placeholder="Contoh: 100000" required>
                                    @error('harga_per_ton')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="text-muted">Harga per ton carbon credit dalam satuan mata uang yang dipilih</small>
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
                                        <option value="" disabled selected>Pilih mata uang</option>
                                        <option value="IDR" {{ old('mata_uang') == 'IDR' ? 'selected' : '' }}>IDR - Rupiah Indonesia</option>
                                        <option value="USD" {{ old('mata_uang') == 'USD' ? 'selected' : '' }}>USD - Dolar Amerika</option>
                                        <option value="EUR" {{ old('mata_uang') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    </select>
                                    @error('mata_uang')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 d-flex justify-content-end">
                                <a href="{{ route('manager.penyedia-carbon-credit.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="bi bi-x-circle me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Simpan Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection