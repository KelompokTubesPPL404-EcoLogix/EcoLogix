@extends('layouts.super-admin')

@section('title', 'Edit Perusahaan')

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
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .input-group-text {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
        }
        .carbon-icon {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .edit-badge {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
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
                    <i class="bi bi-building-gear me-2"></i>@yield('title')
                    <span class="edit-badge ms-2">Mode Edit</span>
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-leaf me-1"></i>Perbarui informasi perusahaan {{ $perusahaan->nama_perusahaan }} di sistem Ecologix
                </p>
            </div>
            <div class="carbon-icon">
                <i class="bi bi-building-gear"></i>
            </div>
        </div>
    </div>

    <!-- Navigation Button -->
    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('superadmin.perusahaan.index') }}" class="btn btn-outline-success shadow-sm px-4">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Perusahaan
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

    <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
        <div class="card-header eco-gradient text-white py-3">
            <h6 class="m-0 fw-bold text-success">
                <i class="bi bi-building-gear me-2"></i>Formulir Edit Perusahaan Ecologix
            </h6>
        </div>
        <div class="card-body p-4 bg-light">
            <form action="{{ route('superadmin.perusahaan.update', $perusahaan->kode_perusahaan) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4 row">
                <label for="kode_perusahaan" class="col-sm-3 col-form-label fw-medium text-success">
                    <i class="bi bi-code-square me-1"></i>Kode Perusahaan
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-hash"></i></span>
                        <input type="text" class="form-control bg-light" id="kode_perusahaan" value="{{ $perusahaan->kode_perusahaan }}" readonly>
                    </div>
                    <small class="form-text text-muted mt-1">
                        <i class="bi bi-info-circle me-1"></i>Kode unik untuk identifikasi perusahaan di sistem Ecologix
                    </small>
                </div>
            </div>

            <div class="mb-4 row">
                <label for="nama_perusahaan" class="col-sm-3 col-form-label fw-medium text-success">
                    <i class="bi bi-building me-1"></i>Nama Perusahaan <span class="text-danger">*</span>
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-building-fill"></i></span>
                        <input type="text" class="form-control @error('nama_perusahaan') is-invalid @enderror" id="nama_perusahaan" name="nama_perusahaan" value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan) }}" placeholder="Masukkan nama lengkap perusahaan" required>
                        @error('nama_perusahaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-4 row">
                <label for="alamat_perusahaan" class="col-sm-3 col-form-label fw-medium text-success">
                    <i class="bi bi-geo-alt me-1"></i>Alamat Perusahaan <span class="text-danger">*</span>
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                        <textarea class="form-control @error('alamat_perusahaan') is-invalid @enderror" id="alamat_perusahaan" name="alamat_perusahaan" rows="3" placeholder="Alamat lengkap perusahaan untuk keperluan monitoring emisi" required>{{ old('alamat_perusahaan', $perusahaan->alamat_perusahaan) }}</textarea>
                        @error('alamat_perusahaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-4 row">
                <label for="no_telp_perusahaan" class="col-sm-3 col-form-label fw-medium text-success">
                    <i class="bi bi-telephone me-1"></i>No. Telepon <span class="text-danger">*</span>
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                        <input type="text" class="form-control @error('no_telp_perusahaan') is-invalid @enderror" id="no_telp_perusahaan" name="no_telp_perusahaan" value="{{ old('no_telp_perusahaan', $perusahaan->no_telp_perusahaan) }}" placeholder="Nomor telepon kantor pusat" required>
                        @error('no_telp_perusahaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-4 row">
                <label for="email_perusahaan" class="col-sm-3 col-form-label fw-medium text-success">
                    <i class="bi bi-envelope me-1"></i>Email Perusahaan <span class="text-danger">*</span>
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" class="form-control @error('email_perusahaan') is-invalid @enderror" id="email_perusahaan" name="email_perusahaan" value="{{ old('email_perusahaan', $perusahaan->email_perusahaan) }}" placeholder="info@perusahaan.com" required>
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

            <div class="mb-4 row">
                <label for="kode_super_admin" class="col-sm-3 col-form-label fw-medium text-success">
                    <i class="bi bi-person-badge me-1"></i>Super Admin <span class="text-danger">*</span>
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-badge-fill"></i></span>
                        <select class="form-select @error('kode_super_admin') is-invalid @enderror" 
                                id="kode_super_admin" name="kode_super_admin" required>
                            <option value="">Pilih Super Admin</option>
                            @foreach($superAdmins as $admin)
                                <option value="{{ $admin->kode_user }}" 
                                    {{ (old('kode_super_admin', $perusahaan->kode_super_admin) == $admin->kode_user) ? 'selected' : '' }}>
                                    {{ $admin->nama }} ({{ $admin->kode_user }})
                                </option>
                            @endforeach
                        </select>
                        @error('kode_super_admin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                <a href="{{ route('superadmin.perusahaan.index') }}" class="btn btn-outline-secondary me-3 px-4">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-success px-4 shadow-sm">
                    <i class="bi bi-arrow-repeat me-1"></i>Perbarui Data Perusahaan
                </button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection