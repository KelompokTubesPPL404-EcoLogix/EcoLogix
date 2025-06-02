@extends('layouts.super-admin')

@section('title', 'Detail Perusahaan')

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
        .company-profile {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .company-avatar {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }
        .info-item {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #28a745;
            transition: all 0.3s ease;
        }
        .info-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.1);
        }
        .info-icon {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .eco-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 1rem;
            font-weight: 600;
        }
        .detail-badge {
            background: linear-gradient(45deg, #17a2b8, #20c997);
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
                    <i class="bi bi-building-fill me-2"></i>@yield('title')
                    <span class="detail-badge ms-2">Detail View</span>
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-leaf me-1"></i>Informasi lengkap perusahaan {{ $perusahaan->nama_perusahaan }} di sistem Ecologix
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('superadmin.perusahaan.edit', $perusahaan->kode_perusahaan) }}" class="btn btn-warning shadow-sm px-4">
                    <i class="bi bi-pencil-square me-1"></i> Edit Perusahaan
                </a>
                <a href="{{ route('superadmin.perusahaan.index') }}" class="btn btn-outline-success shadow-sm px-4">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Company Profile Section -->
    <div class="company-profile mb-4">
        <div class="d-flex align-items-center mb-4">
            <div class="company-avatar me-4">
                <i class="bi bi-building-fill"></i>
            </div>
            <div>
                <h2 class="text-success fw-bold mb-1">{{ $perusahaan->nama_perusahaan }}</h2>
                <div class="eco-badge">
                    <i class="bi bi-code-square me-1"></i>{{ $perusahaan->kode_perusahaan }}
                </div>
            </div>
        </div>
    </div>

    <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
        <div class="card-header eco-gradient text-white py-3">
            <h6 class="m-0 fw-bold text-success">
                <i class="bi bi-building-fill me-2 "></i>Informasi Detail Perusahaan Ecologix
            </h6>
        </div>
        <div class="card-body p-4 bg-light">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="info-item">
                        <div class="d-flex align-items-center mb-3">
                            <div class="info-icon me-3">
                                <i class="bi bi-code-square"></i>
                            </div>
                            <div>
                                <h6 class="text-success fw-bold mb-0">Kode Perusahaan</h6>
                                <small class="text-muted">Identifikasi Unik</small>
                            </div>
                        </div>
                        <div class="eco-badge">
                            <i class="bi bi-hash me-1"></i>{{ $perusahaan->kode_perusahaan }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="info-item">
                        <div class="d-flex align-items-center mb-3">
                            <div class="info-icon me-3">
                                <i class="bi bi-building"></i>
                            </div>
                            <div>
                                <h6 class="text-success fw-bold mb-0">Nama Perusahaan</h6>
                                <small class="text-muted">Nama Resmi Terdaftar</small>
                            </div>
                        </div>
                        <div class="fw-bold text-dark fs-5">{{ $perusahaan->nama_perusahaan }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="info-item">
                        <div class="d-flex align-items-center mb-3">
                            <div class="info-icon me-3">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div>
                                <h6 class="text-success fw-bold mb-0">Alamat Perusahaan</h6>
                                <small class="text-muted">Lokasi Kantor Pusat</small>
                            </div>
                        </div>
                        <div class="text-dark">
                            <i class="bi bi-geo-alt-fill text-success me-2"></i>
                            {{ $perusahaan->alamat_perusahaan ?? 'Alamat belum diisi' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="info-item">
                        <div class="d-flex align-items-center mb-3">
                            <div class="info-icon me-3">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div>
                                <h6 class="text-success fw-bold mb-0">Nomor Telepon</h6>
                                <small class="text-muted">Kontak Perusahaan</small>
                            </div>
                        </div>
                        <div class="text-dark">
                            @if($perusahaan->no_telp_perusahaan)
                                <i class="bi bi-telephone-fill text-success me-2"></i>
                                <a href="tel:{{ $perusahaan->no_telp_perusahaan }}" class="text-decoration-none text-dark">{{ $perusahaan->no_telp_perusahaan }}</a>
                            @else
                                <span class="text-muted">Nomor telepon belum diisi</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="info-item">
                        <div class="d-flex align-items-center mb-3">
                            <div class="info-icon me-3">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div>
                                <h6 class="text-success fw-bold mb-0">Email Perusahaan</h6>
                                <small class="text-muted">Alamat Email Resmi</small>
                            </div>
                        </div>
                        <div class="text-dark">
                            @if($perusahaan->email_perusahaan)
                                <i class="bi bi-envelope-fill text-success me-2"></i>
                                <a href="mailto:{{ $perusahaan->email_perusahaan }}" class="text-decoration-none text-dark">{{ $perusahaan->email_perusahaan }}</a>
                            @else
                                <span class="text-muted">Email belum diisi</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                <div class="d-flex align-items-center">
                    <div class="info-icon me-3" style="width: 30px; height: 30px; font-size: 0.9rem;">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <small class="text-muted">Status Monitoring</small>
                        <div class="eco-badge">
                            <i class="bi bi-check-circle me-1"></i>Aktif di Sistem Ecologix
                        </div>
                    </div>
                </div>
                <div>
                    <form action="{{ route('superadmin.perusahaan.destroy', $perusahaan->kode_perusahaan) }}" method="POST" class="d-inline me-3" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perusahaan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger px-4"><i class="bi bi-trash me-1"></i> Hapus</button>
                    </form>
                    <a href="{{ route('superadmin.perusahaan.edit', $perusahaan->kode_perusahaan) }}" class="btn btn-warning me-3 px-4">
                        <i class="bi bi-pencil-square me-1"></i> Edit Data Perusahaan
                    </a>
                    <a href="{{ route('superadmin.perusahaan.index') }}" class="btn btn-outline-success px-4">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection