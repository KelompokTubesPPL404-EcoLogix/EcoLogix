@extends('layouts.admin')

@section('title', 'Detail Staff')

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
        .staff-profile {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .staff-avatar {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }
        .info-item {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .info-icon {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
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
        .detail-table th {
            background-color: rgba(40, 167, 69, 0.05);
            width: 30%;
            vertical-align: middle;
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
                    <i class="bi bi-leaf me-1"></i>Informasi lengkap staff sistem monitoring emisi karbon Ecologix
                </p>
            </div>
            <div class="carbon-icon">
                <i class="bi bi-person-badge-fill"></i>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('admin.staff.edit', $staff->kode_user) }}" class="btn btn-warning shadow-sm me-2 px-4">
            <i class="bi bi-pencil me-1"></i> Edit Staff
        </a>
        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-success shadow-sm px-4">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Staff
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- Staff Profile Section -->
            <div class="staff-profile mb-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="staff-avatar me-4">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <h4 class="text-success fw-bold mb-2">{{ $staff->nama }}</h4>
                        <div class="eco-badge mb-3">
                            <i class="bi bi-shield-check me-1"></i>Staff Ecologix
                        </div>
                        <p class="text-muted mb-0">
                            <i class="bi bi-calendar-check me-1"></i>
                            Bergabung {{ $staff->created_at->timezone('Asia/Jakarta')->format('d F Y') }} WIB
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="eco-card card border-0 shadow-lg mb-4">
                <div class="card-header eco-gradient text-white py-3">
                    <h6 class="m-0 fw-bold" style="color: #198754;">
                        <i class="bi bi-info-circle me-2" style="color: #198754;"></i>Informasi Detail Staff
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
                                        <h6 class="text-success fw-bold mb-0">Kode User</h6>
                                        <small class="text-muted">Identifikasi Unik</small>
                                    </div>
                                </div>
                                <div class="detail-badge">
                                    {{ $staff->kode_user }}
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
                                        <h6 class="text-success fw-bold mb-0">Email</h6>
                                        <small class="text-muted">Alamat Email</small>
                                    </div>
                                </div>
                                <div class="fw-bold text-dark">
                                    <i class="bi bi-envelope-fill text-success me-2"></i>
                                    <a href="mailto:{{ $staff->email }}" class="text-decoration-none text-dark">{{ $staff->email }}</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-item">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="info-icon me-3">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-success fw-bold mb-0">Role</h6>
                                        <small class="text-muted">Peran dalam Sistem</small>
                                    </div>
                                </div>
                                <span class="badge bg-info px-3 py-2">
                                    <i class="bi bi-person-badge me-1"></i>{{ ucfirst($staff->role) }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-item">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="info-icon me-3">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-success fw-bold mb-0">Perusahaan</h6>
                                        <small class="text-muted">Tempat Bekerja</small>
                                    </div>
                                </div>
                                <div class="fw-bold text-dark">
                                    @if($staff->perusahaan)
                                        <i class="bi bi-building-fill text-success me-2"></i>
                                        {{ $staff->perusahaan->nama_perusahaan }}
                                    @else
                                        <span class="text-muted">Tidak ada perusahaan</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-item">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="info-icon me-3">
                                        <i class="bi bi-telephone"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-success fw-bold mb-0">Nomor HP</h6>
                                        <small class="text-muted">Kontak Telepon</small>
                                    </div>
                                </div>
                                <div class="fw-bold text-dark">
                                    @if($staff->no_hp)
                                        <i class="bi bi-phone-fill text-success me-2"></i>
                                        {{ $staff->no_hp }}
                                    @else
                                        <span class="text-muted">Nomor HP belum diisi</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-item">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="info-icon me-3">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-success fw-bold mb-0">Alamat</h6>
                                        <small class="text-muted">Alamat Lengkap</small>
                                    </div>
                                </div>
                                <div class="fw-bold text-dark">
                                    @if($staff->alamat)
                                        <i class="bi bi-geo-alt-fill text-success me-2"></i>
                                        {{ $staff->alamat }}
                                    @else
                                        <span class="text-muted">Alamat belum diisi</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="info-icon me-3">
                                        <i class="bi bi-calendar-plus"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-success fw-bold mb-0">Terdaftar Pada</h6>
                                        <small class="text-muted">Tanggal Bergabung</small>
                                    </div>
                                </div>
                                <div class="fw-bold text-dark">
                                    <i class="bi bi-calendar-check me-1 text-muted"></i>
                                    {{ $staff->created_at ? $staff->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB' : '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="info-icon me-3">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-success fw-bold mb-0">Terakhir Diperbarui</h6>
                                        <small class="text-muted">Update Terakhir</small>
                                    </div>
                                </div>
                                <div class="fw-bold text-dark">
                                    <i class="bi bi-clock me-1 text-muted"></i>
                                    {{ $staff->updated_at ? $staff->updated_at->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB' : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
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

        <div class="d-flex gap-2">
            <a href="{{ route('admin.staff.edit', $staff->kode_user) }}" class="btn btn-warning px-4">
                <i class="bi bi-pencil me-1"></i> Edit Data Staff
            </a>
        </div>
    </div>
</div>
@endsection