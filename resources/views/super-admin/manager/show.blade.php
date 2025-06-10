@extends('layouts.super-admin')

@section('title', 'Detail Manager')

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
        .carbon-icon {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5em;
        }
        .info-item {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-left: 3px solid #28a745;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .info-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.1);
        }
        .info-label {
            color: #28a745;
            font-weight: 600;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            color: #495057;
            font-size: 1.1em;
            margin-top: 5px;
        }
        .eco-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 500;
        }
        .profile-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.1);
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
                    <i class="bi bi-person-badge me-2"></i>@yield('title')
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-leaf me-1"></i>Informasi lengkap manager sistem monitoring emisi karbon Ecologix
                </p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end mb-4">
        <!-- <a href="{{ route('superadmin.manager.edit', $manager->kode_user) }}" class="btn btn-warning shadow-sm me-2 px-4">
            <i class="bi bi-pencil me-1"></i> Edit Manager
        </a> -->
        <a href="{{ route('superadmin.manager.index') }}" class="btn btn-outline-success shadow-sm px-4">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Manager
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Manager Profile Section -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="profile-section text-center">
                <div class="carbon-icon mx-auto mb-3">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h4 class="text-success fw-bold mb-2">{{ $manager->nama }}</h4>
                <div class="eco-badge mb-3">
                    <i class="bi bi-shield-check me-1"></i>Manager Ecologix
                </div>
                <p class="text-muted mb-0">
                    <i class="bi bi-calendar-check me-1"></i>
                    Bergabung {{ $manager->created_at->timezone('Asia/Jakarta')->format('d F Y') }} WIB
                </p>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="eco-card card border-0 shadow-lg mb-4">
                <div class="card-header eco-gradient text-white py-3">
                    <h6 class="m-0 fw-bold" style="color: #198754;">
                        <i class="bi bi-info-circle me-2" style="color: #198754;"></i>Informasi Detail Manager
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-hash me-1"></i>Kode Manager
                                </div>
                                <div class="info-value">
                                    <code class="bg-light p-2 rounded text-success">
                                        {{ $manager->kode_user }}
                                    </code>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-person me-1"></i>Nama Lengkap
                                </div>
                                <div class="info-value">{{ $manager->nama }}</div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-envelope me-1"></i>Alamat Email
                                </div>
                                <div class="info-value">
                                    <a href="mailto:{{ $manager->email }}" class="text-decoration-none text-success">
                                        {{ $manager->email }}
                                    </a>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-phone me-1"></i>Nomor Telepon
                                </div>
                                <div class="info-value">
                                    <a href="tel:{{ $manager->no_hp }}" class="text-decoration-none text-success">
                                        {{ $manager->no_hp }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-shield-check me-1"></i>Role
                                </div>
                                <div class="info-value">
                                    <span class="eco-badge">
                                        {{ ucfirst($manager->role) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-building me-1"></i>Perusahaan
                                </div>
                                <div class="info-value">
                                    @if($manager->perusahaan)
                                        <div class="eco-badge d-inline-block">
                                            <i class="bi bi-building-fill me-1"></i>
                                            <a href="{{ route('superadmin.perusahaan.show', $manager->perusahaan->kode_perusahaan) }}" class="text-decoration-none" style="color: #198754;">
                                                {{ $manager->perusahaan->nama_perusahaan }}
                                            </a>
                                        </div>
                                        <br>
                                        <small class="text-muted">
                                            Kode: {{ $manager->perusahaan->kode_perusahaan }}
                                        </small>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Tidak ada perusahaan
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-calendar-plus me-1"></i>Tanggal Dibuat
                                </div>
                                <div class="info-value">
                                    {{ $manager->created_at->timezone('Asia/Jakarta')->format('d F Y H:i:s') }} WIB
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-clock-history me-1"></i>Terakhir Diperbarui
                                </div>
                                <div class="info-value">
                                    {{ $manager->updated_at->timezone('Asia/Jakarta')->format('d F Y H:i:s') }} WIB
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tindakan</h6>
        </div>
        <div class="card-body">
            <a href="{{ route('superadmin.manager.edit', $manager->kode_user) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Data Manager
            </a>
            <form action="{{ route('superadmin.manager.destroy', $manager->kode_user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus manager ini? Tindakan ini tidak dapat diurungkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus Manager
                </button>
            </form>
        </div>
    </div>

</div>
@endsection