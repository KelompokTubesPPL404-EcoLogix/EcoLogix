@extends('layouts.super-admin')

@section('title', 'Edit Perusahaan')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-success fw-bold">@yield('title'): {{ $perusahaan->nama_perusahaan }}</h1>
        <a href="{{ route('superadmin.perusahaan.index') }}" class="btn btn-outline-secondary shadow-sm">
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
            <h6 class="m-0 fw-bold text-success"><i class="bi bi-pencil-square me-2"></i>Formulir Edit Perusahaan</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('superadmin.perusahaan.update', $perusahaan->kode_perusahaan) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4 row">
                <label for="kode_perusahaan" class="col-sm-3 col-form-label fw-medium">Kode Perusahaan</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control bg-light" id="kode_perusahaan" value="{{ $perusahaan->kode_perusahaan }}" readonly>
                </div>
            </div>

            <div class="mb-4 row">
                <label for="nama_perusahaan" class="col-sm-3 col-form-label fw-medium">Nama Perusahaan <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                        <input type="text" class="form-control @error('nama_perusahaan') is-invalid @enderror" id="nama_perusahaan" name="nama_perusahaan" value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan) }}" required>
                        @error('nama_perusahaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-4 row">
                <label for="alamat_perusahaan" class="col-sm-3 col-form-label fw-medium">Alamat Perusahaan <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-geo-alt"></i></span>
                        <textarea class="form-control @error('alamat_perusahaan') is-invalid @enderror" id="alamat_perusahaan" name="alamat_perusahaan" rows="3" required>{{ old('alamat_perusahaan', $perusahaan->alamat_perusahaan) }}</textarea>
                        @error('alamat_perusahaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-4 row">
                <label for="no_telp_perusahaan" class="col-sm-3 col-form-label fw-medium">No. Telepon <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                        <input type="text" class="form-control @error('no_telp_perusahaan') is-invalid @enderror" id="no_telp_perusahaan" name="no_telp_perusahaan" value="{{ old('no_telp_perusahaan', $perusahaan->no_telp_perusahaan) }}" required>
                        @error('no_telp_perusahaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-4 row">
                <label for="email_perusahaan" class="col-sm-3 col-form-label fw-medium">Email Perusahaan <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control @error('email_perusahaan') is-invalid @enderror" id="email_perusahaan" name="email_perusahaan" value="{{ old('email_perusahaan', $perusahaan->email_perusahaan) }}" required>
                        @error('email_perusahaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-4 row">
                <label for="password_perusahaan" class="col-sm-3 col-form-label fw-medium">Password <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small></label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control @error('password_perusahaan') is-invalid @enderror" id="password_perusahaan" name="password_perusahaan" minlength="8">
                        @error('password_perusahaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="kode_super_admin" class="form-label">Kode Super Admin</label>
                <select class="form-select @error('kode_super_admin') is-invalid @enderror" id="kode_super_admin" name="kode_super_admin" required>
                    <option value="">Pilih Super Admin</option>
                    @foreach($superAdmins as $admin)
                    <option value="{{ $admin->kode_super_admin }}" {{ old('kode_super_admin') == $admin->kode_super_admin ? 'selected' : '' }}>{{ $admin->nama_super_admin }}</option>
                    @endforeach
                </select>
                @error('kode_super_admin')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('superadmin.perusahaan.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection