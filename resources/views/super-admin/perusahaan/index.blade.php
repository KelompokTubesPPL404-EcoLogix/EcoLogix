@extends('layouts.super-admin')

@section('title', 'Daftar Perusahaan')

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
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-left: 4px solid #28a745;
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
        }
        .stats-icon {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .table-eco {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .table-eco thead {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        .table-eco tbody tr:hover {
            background-color: rgba(40, 167, 69, 0.05);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
        .btn-action {
            padding: 0.25rem 0.5rem;
            margin: 0 0.1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        .btn-action:hover {
            transform: translateY(-1px);
        }
        .carbon-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
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
                    <i class="bi bi-buildings me-2"></i>@yield('title')
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-leaf me-1"></i>Kelola perusahaan yang terdaftar dalam sistem monitoring emisi karbon Ecologix
                </p>
            </div>
            <div class="d-flex align-items-center">
                <div class="carbon-badge me-3">
                    <i class="bi bi-building-check me-1"></i>{{ $perusahaan->count() }} Perusahaan
                </div>
                <a href="{{ route('superadmin.perusahaan.create') }}" class="btn btn-success shadow-sm px-4">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Perusahaan Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stats-icon me-3">
                        <i class="bi bi-buildings"></i>
                    </div>
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Perusahaan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $perusahaan->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stats-icon me-3" style="background: linear-gradient(45deg, #17a2b8, #20c997);">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Monitoring Aktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $perusahaan->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stats-icon me-3" style="background: linear-gradient(45deg, #ffc107, #fd7e14);">
                        <i class="bi bi-cloud-arrow-down"></i>
                    </div>
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Emisi Terpantau</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">100%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stats-icon me-3" style="background: linear-gradient(45deg, #e74c3c, #c0392b);">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Compliance Rate</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">95%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
        <div class="card-header eco-gradient text-white py-3">
            <h6 class="m-0 fw-bold text-success">
                <i class="bi bi-buildings me-2"></i>Data Perusahaan Terdaftar di Ecologix
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-eco table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 fw-bold text-center">
                                <i class="bi bi-hash me-1"></i>No
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-code-square me-1"></i>Kode Perusahaan
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-building me-1"></i>Nama Perusahaan
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-geo-alt me-1"></i>Alamat
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-telephone me-1"></i>Telepon
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-envelope me-1"></i>Email
                            </th>
                            <th class="border-0 fw-bold text-center">
                                <i class="bi bi-gear me-1"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perusahaan as $index => $p)
                        <tr class="align-middle">
                            <td class="text-center">
                                <span class="badge bg-light text-dark fw-bold">{{ $index + 1 }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="carbon-icon me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <span class="badge bg-success text-white px-3 py-2">{{ $p->kode_perusahaan }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-success">{{ $p->nama_perusahaan }}</div>
                                <small class="text-muted">
                                    <i class="bi bi-building-check me-1"></i>Perusahaan Terdaftar
                                </small>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $p->alamat_perusahaan }}">
                                    <i class="bi bi-geo-alt-fill text-success me-1"></i>
                                    {{ Str::limit($p->alamat_perusahaan, 50) }}
                                </div>
                            </td>
                            <td>
                                @if($p->no_telp_perusahaan)
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-telephone-fill text-success me-2"></i>
                                        <span>{{ $p->no_telp_perusahaan }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($p->email_perusahaan)
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-envelope-fill text-success me-2"></i>
                                        <span>{{ $p->email_perusahaan }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                
                                    <a href="{{ route('superadmin.perusahaan.show', $p->kode_perusahaan) }}" class="btn btn-outline-info btn-action" title="Lihat Detail Perusahaan">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('superadmin.perusahaan.edit', $p->kode_perusahaan) }}" class="btn btn-outline-warning btn-action" title="Edit Data Perusahaan">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('superadmin.perusahaan.destroy', $p->kode_perusahaan) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perusahaan {{ $p->nama_perusahaan }} dari sistem Ecologix?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-action" title="Hapus Perusahaan">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data perusahaan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection