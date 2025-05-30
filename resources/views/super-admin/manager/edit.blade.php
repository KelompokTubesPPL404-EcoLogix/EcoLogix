@extends('layouts.super-admin')

@section('title', 'Edit Data Manager')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-success fw-bold">@yield('title'): {{ $manager->nama }}</h1>
        <a href="{{ route('superadmin.manager.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> <strong>Error!</strong> Terdapat masalah dengan input Anda.
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom border-light">
            <h6 class="m-0 fw-bold text-success"><i class="bi bi-pencil-square me-2"></i>Formulir Edit Manager</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('superadmin.manager.update', $manager->kode_user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4 row">
                    <label for="kode_user" class="col-sm-3 col-form-label fw-medium">Kode Manager</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control bg-light" id="kode_user" name="kode_user" value="{{ $manager->kode_user }}" readonly>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="nama" class="col-sm-3 col-form-label fw-medium text-success">
                        <i class="bi bi-person me-1"></i>Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $manager->nama) }}" placeholder="Masukkan nama lengkap manager" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="email" class="col-sm-3 col-form-label fw-medium text-success">
                        <i class="bi bi-envelope me-1"></i>Email <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $manager->email) }}" placeholder="manager@perusahaan.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="no_hp" class="col-sm-3 col-form-label fw-medium text-success">
                        <i class="bi bi-phone me-1"></i>Nomor HP <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-phone-fill"></i></span>
                            <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp', $manager->no_hp) }}" placeholder="08xxxxxxxxxx" required>
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="kode_perusahaan" class="col-sm-3 col-form-label fw-medium text-success">
                        <i class="bi bi-building me-1"></i>Perusahaan <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-building-fill"></i></span>
                            <select class="form-select @error('kode_perusahaan') is-invalid @enderror" id="kode_perusahaan" name="kode_perusahaan" required>
                                <option value="">🏢 Pilih Perusahaan untuk Monitoring Emisi</option>
                                @foreach($perusahaanList as $perusahaan)
                                    <option value="{{ $perusahaan->kode_perusahaan }}" {{ (old('kode_perusahaan', $manager->kode_perusahaan) == $perusahaan->kode_perusahaan) ? 'selected' : '' }}>
                                        🌱 {{ $perusahaan->nama_perusahaan }} ({{ $perusahaan->kode_perusahaan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_perusahaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="password" class="col-sm-3 col-form-label fw-medium text-success">
                        <i class="bi bi-lock me-1"></i>Password Baru
                    </label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted mt-1">
                            <i class="bi bi-info-circle me-1"></i>Kosongkan jika tidak ingin mengubah password
                        </small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="password_confirmation" class="col-sm-3 col-form-label fw-medium text-success">
                        <i class="bi bi-shield-lock me-1"></i>Konfirmasi Password Baru
                    </label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                    <a href="{{ route('superadmin.manager.index') }}" class="btn btn-outline-secondary me-3 px-4">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4 shadow-sm">
                        <i class="bi bi-check-circle me-1"></i>Update Manager Ecologix
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
// Tambahkan script khusus jika diperlukan
@endpush