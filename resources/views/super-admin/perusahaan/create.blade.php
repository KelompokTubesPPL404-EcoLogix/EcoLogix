@extends('layouts.super-admin')

@section('title', 'Tambah Perusahaan Baru')

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
    </style>
@endpush

@section('content')
<div class="container-fluid">

    <!-- Enhanced Eco Header Section -->
    <div class="eco-header p-4 rounded-3 mb-4">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-2 text-success fw-bold">
                    <i class="bi bi-building-add me-2"></i>@yield('title')
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-leaf me-1"></i>Daftarkan perusahaan baru untuk monitoring emisi karbon di sistem Ecologix
                </p>
            </div>
            <div class="carbon-icon">
                <i class="bi bi-building-fill"></i>
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
                <i class="bi bi-building-add me-2"></i>Formulir Pendaftaran Perusahaan Ecologix
            </h6>
        </div>
        <div class="card-body p-4 bg-light">
            <form action="{{ route('superadmin.perusahaan.store') }}" method="POST">
            @csrf
            <div class="mb-4 row">
                {{-- <label for="kode_perusahaan" class="col-sm-3 col-form-label fw-medium text-success">
                    <i class="bi bi-code-square me-1"></i>Kode Perusahaan <span class="text-danger">*</span>
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-hash"></i></span>
                        <input type="text" class="form-control @error('kode_perusahaan') is-invalid @enderror" id="kode_perusahaan" name="kode_perusahaan" value="{{ old('kode_perusahaan') }}" placeholder="Contoh: COMP001" required>
                        @error('kode_perusahaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text text-muted mt-1">
                        <i class="bi bi-info-circle me-1"></i>Kode unik untuk identifikasi perusahaan di sistem Ecologix
                    </small>
                </div> --}}
            </div>

            <div class="mb-4 row">
                <label for="nama_perusahaan" class="col-sm-3 col-form-label fw-medium text-success">
                    <i class="bi bi-building me-1"></i>Nama Perusahaan <span class="text-danger">*</span>
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-building-fill"></i></span>
                        <input type="text" class="form-control @error('nama_perusahaan') is-invalid @enderror" id="nama_perusahaan" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}" placeholder="Masukkan nama lengkap perusahaan" required>
                        @error('nama_perusahaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-4 row">
                <label for="alamat" class="col-sm-3 col-form-label fw-medium text-success">
                    <i class="bi bi-geo-alt me-1"></i>Alamat Perusahaan
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" placeholder="Alamat lengkap perusahaan untuk keperluan monitoring emisi">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-4 row">
                <label for="telepon" class="col-sm-3 col-form-label fw-medium text-success">
                    <i class="bi bi-telephone me-1"></i>Telepon Perusahaan
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                        <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon') }}" placeholder="Nomor telepon kantor pusat">
                        @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-4 row">
                <label for="email" class="col-sm-3 col-form-label fw-medium text-success">
                    <i class="bi bi-envelope me-1"></i>Email Perusahaan
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="info@perusahaan.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-4 row">
                <label for="password_perusahaan" class="col-sm-3 col-form-label fw-medium">Password <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control @error('password_perusahaan') is-invalid @enderror" id="password_perusahaan" name="password_perusahaan" required>
                        @error('password_perusahaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-4 row">
                <label for="kode_super_admin" class="col-sm-3 col-form-label fw-medium">Super Admin <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person-badge"></i></span>
                        <select class="form-select @error('kode_super_admin') is-invalid @enderror" id="kode_super_admin" name="kode_super_admin" required>
                            <option value="">Pilih Super Admin</option>
                            @foreach($superAdmins as $admin)
                            <option value="{{ $admin->kode_user }}" {{ old('kode_super_admin') == $admin->kode_user ? 'selected' : '' }}>
                                {{ $admin->nama }} - {{ $admin->kode_user }}
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
                    <i class="bi bi-check-circle me-1"></i>Daftarkan Perusahaan ke Ecologix
                </button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection