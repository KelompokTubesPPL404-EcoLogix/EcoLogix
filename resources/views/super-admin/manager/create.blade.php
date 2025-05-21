@extends('layouts.super-admin')

@section('title', 'Tambah Manager Baru')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-success fw-bold">@yield('title')</h1>
        <a href="{{ route('superadmin.manager.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Manager
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
            <h6 class="m-0 fw-bold text-success"><i class="bi bi-person-plus me-2"></i>Formulir Tambah Manager</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('superadmin.manager.store') }}" method="POST">
                @csrf
                
                <div class="form-group row">
                    <label for="nama" class="col-sm-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-3 col-form-label">Email <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="no_hp" class="col-sm-3 col-form-label">Nomor HP <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required>
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="kode_perusahaan" class="col-sm-3 col-form-label">Perusahaan <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control @error('kode_perusahaan') is-invalid @enderror" id="kode_perusahaan" name="kode_perusahaan" required>
                            <option value="">-- Pilih Perusahaan --</option>
                            @foreach($perusahaanList as $perusahaan)
                                <option value="{{ $perusahaan->kode_perusahaan }}" {{ (old('kode_perusahaan', $kode_perusahaan_selected ?? '') == $perusahaan->kode_perusahaan) ? 'selected' : '' }}>
                                    {{ $perusahaan->nama_perusahaan }} ({{ $perusahaan->kode_perusahaan }})
                                </option>
                            @endforeach
                        </select>
                        @error('kode_perusahaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-sm-3 col-form-label">Password <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password_confirmation" class="col-sm-3 col-form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary">Simpan Manager</button>
                        <a href="{{ route('superadmin.manager.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
// Tambahkan script khusus jika diperlukan, misalnya untuk select2
@endpush