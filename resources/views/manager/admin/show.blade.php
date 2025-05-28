@extends('layouts.manager')

@section('title', 'Detail Admin')

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
        .detail-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .detail-table th {
            background-color: rgba(40, 167, 69, 0.05);
            width: 30%;
            vertical-align: middle;
        }
        .detail-icon {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
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
    <!-- Enhanced Eco Header Section -->
    <div class="eco-header p-4 rounded-3 mb-4">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-2 text-success fw-bold">
                    <i class="bi bi-person-circle me-2"></i>Detail Admin
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>Informasi lengkap mengenai admin: {{ $admin->nama }}
                </p>
            </div>
            <a href="{{ route('manager.admin.index') }}" class="btn btn-outline-success shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Admin
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <!-- Summary Card (now on left) -->
        <div class="col-xl-4 col-lg-3 mb-4">
            <div class="card eco-card border-0 shadow-lg rounded-3">
                <div class="card-body text-center py-4">
                    <div class="detail-icon mx-auto mb-3">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h5 class="fw-bold text-success mb-1">{{ $admin->nama }}</h5>
                    <div class="text-muted small mb-3">Admin - {{ $admin->perusahaan->nama_perusahaan ?? 'N/A' }}</div>
                    
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-info text-white px-3 py-2">
                            <i class="fas fa-user-tag me-1"></i> {{ ucfirst($admin->role) }}
                        </span>
                    </div>
                    
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Email:</span>
                            <a href="mailto:{{ $admin->email }}" class="fw-bold text-success">{{ $admin->email }}</a>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Telepon:</span>
                            <a href="tel:{{ $admin->no_hp }}" class="fw-bold text-success">{{ $admin->no_hp ?? 'N/A' }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detail Card (now on right) -->
        <div class="col-xl-8 col-lg-9">
            <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
                <div class="card-header eco-gradient py-3">
                    <h6 class="m-0 fw-bold text-success">
                        <i class="bi bi-info-circle me-2"></i>Informasi Detail Admin
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover detail-table mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-person-badge text-success me-2"></i>Kode Admin
                                    </th>
                                    <td>
                                        <span class="badge bg-success text-white px-3 py-2">{{ $admin->kode_user }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-person text-success me-2"></i>Nama Lengkap
                                    </th>
                                    <td class="fw-bold">{{ $admin->nama }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-envelope text-success me-2"></i>Alamat Email
                                    </th>
                                    <td>
                                        <a href="mailto:{{ $admin->email }}" class="text-success">{{ $admin->email }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="bi bi-telephone text-success me-2"></i>Nomor HP
                                    </th>
                                    <td>
                                        <a href="tel:{{ $admin->no_hp }}" class="text-success">{{ $admin->no_hp ?? 'N/A' }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="fas fa-building text-success me-2"></i>Perusahaan
                                    </th>
                                    <td>
                                        @if($admin->perusahaan)
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-light text-success border border-success me-2">
                                                    {{ $admin->perusahaan->kode_perusahaan }}
                                                </span>
                                                <span class="fw-bold">{{ $admin->perusahaan->nama_perusahaan }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="fas fa-user-tag text-success me-2"></i>Role
                                    </th>
                                    <td>
                                        <span class="badge bg-info text-white px-3 py-2">
                                            <i class="fas fa-user-shield me-1"></i> {{ ucfirst($admin->role) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="fas fa-calendar-plus text-success me-2"></i>Tanggal Dibuat
                                    </th>
                                    <td>
                                        <i class="fas fa-clock me-1 text-muted"></i>
                                        {{ $admin->created_at ? $admin->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB' : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4">
                                        <i class="fas fa-calendar-check text-success me-2"></i>Terakhir Diperbarui
                                    </th>
                                    <td>
                                        <i class="fas fa-history me-1 text-muted"></i>
                                        {{ $admin->updated_at ? $admin->updated_at->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB' : 'N/A' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-end py-3">
                        <a href="{{ route('manager.admin.edit', $admin->kode_user) }}" class="btn btn-warning btn-action shadow-sm me-3">
                            <i class="fas fa-edit me-1"></i> Edit Data Admin
                        </a>
                        <form action="{{ route('manager.admin.destroy', $admin->kode_user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin {{ $admin->nama }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-action shadow-sm">
                                <i class="fas fa-trash me-1"></i> Hapus Admin
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection