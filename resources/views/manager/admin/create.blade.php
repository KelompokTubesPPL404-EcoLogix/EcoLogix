@extends('layouts.manager')

@section('title', 'Tambah Admin Baru')

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

    <!-- Header -->
    <div class="eco-header p-4 rounded-3 mb-4">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-2 text-success fw-bold">
                    <i class="bi bi-person-plus-fill me-2"></i>Tambah Admin Baru
                    <small class="text-muted fs-6">untuk {{ Auth::user()->perusahaan->nama_perusahaan }}</small>
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>Buat akun admin baru untuk perusahaan Anda
                </p>
            </div>
            <a href="{{ route('manager.admin.index') }}" class="btn btn-outline-success shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Admin
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Error!</strong> Terdapat masalah dengan input Anda.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
                <div class="card-header py-3 bg-gradient-success-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-success">
                            <i class="bi bi-person-plus-fill me-2"></i>Form Tambah Admin Baru
                        </h6>
                        <span class="badge bg-success">
                            <i class="bi bi-building me-1"></i>{{ Auth::user()->perusahaan->kode_perusahaan }} - {{ Auth::user()->perusahaan->nama_perusahaan }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('manager.admin.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4 row">
                            <label for="nama" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-person me-1"></i>Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap admin" required autofocus>
                                    @error('nama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="email" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-envelope me-1"></i>Alamat Email <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email admin" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="no_hp" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-telephone me-1"></i>Nomor HP <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="Nomor telepon aktif" required>
                                    @error('no_hp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="password" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-lock me-1"></i>Password <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimal 8 karakter" required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="text-muted mt-1">Password harus minimal 8 karakter dan kombinasi huruf dan angka.</small>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="password_confirmation" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-check-circle me-1"></i>Konfirmasi Password <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <a href="{{ route('manager.admin.index') }}" class="btn btn-outline-secondary me-3">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-success px-4 shadow-sm">
                                <i class="bi bi-check-circle me-1"></i>Simpan Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection