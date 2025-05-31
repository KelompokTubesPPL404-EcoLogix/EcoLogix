@extends('layouts.super-admin')

@section('title', 'Daftar Manager')

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
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .eco-table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.1);
        }
        .eco-table thead {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
        .eco-table thead th {
            color: white;
            font-weight: 600;
            border: none;
            padding: 15px;
        }
        .eco-table tbody tr {
            transition: all 0.3s ease;
        }
        .eco-table tbody tr:hover {
            background-color: rgba(40, 167, 69, 0.05);
            transform: scale(1.01);
        }
        .eco-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-left: 4px solid #28a745;
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15);
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="eco-header p-4 rounded-3 mb-4">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-2 text-success fw-bold">
                    <i class="bi bi-people-fill me-2"></i>@yield('title')
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-leaf me-1"></i>Kelola manager untuk monitoring emisi karbon perusahaan di Ecologix
                </p>
            </div>
            <div class="carbon-icon">
                <i class="bi bi-person-workspace"></i>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('superadmin.manager.create') }}" class="btn btn-success shadow-sm px-4">
            <i class="bi bi-plus-circle me-1"></i> Tambah Manager Baru
        </a>
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

    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Manager Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $managers->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Perusahaan Terdaftar
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $managers->pluck('kode_perusahaan')->unique()->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-building fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Monitoring Emisi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">🌱 Aktif</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="eco-card card border-0 shadow-lg mb-4">
        <div class="card-header eco-gradient text-white py-3">
            <h6 class="m-0 fw-bold text-success">
                <i class="bi bi-table me-2 text-success"></i>Daftar Manager Ecologix
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="eco-table table table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>No</th>
                            <th><i class="bi bi-person me-1"></i>Nama Manager</th>
                            <th><i class="bi bi-envelope me-1"></i>Email</th>
                            <th><i class="bi bi-phone me-1"></i>No HP</th>
                            <th><i class="bi bi-building me-1"></i>Perusahaan</th>
                            <th><i class="bi bi-gear me-1"></i>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($managers as $index => $manager)
                        <tr>
                            <td class="fw-medium">{{ $loop->iteration }}</td> {{-- Menggunakan $loop->iteration untuk nomor urut yang benar dengan pagination --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="carbon-icon me-2" style="width: 30px; height: 30px; font-size: 0.8em;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium text-success">{{ $manager->nama }}</div>
                                        <small class="text-muted">Manager Ecologix</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <i class="bi bi-envelope-fill text-success me-1"></i>
                                {{ $manager->email }}
                            </td>
                            <td>
                                <i class="bi bi-phone-fill text-success me-1"></i>
                                {{ $manager->no_hp }}
                            </td>
                            <td>
                                @if($manager->perusahaan)
                                    <div class="eco-badge">
                                        <i class="bi bi-building-fill me-1"></i>
                                        {{ $manager->perusahaan->nama_perusahaan }}
                                    </div>
                                    <small class="text-muted d-block mt-1">{{ $manager->kode_perusahaan }}</small>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Tidak ada perusahaan
                                    </span>
                                @endif
                            </td>
                            <td>
                               
                                    <a href="{{ route('superadmin.manager.show', $manager->kode_user) }}" class="btn btn-outline-info btn-sm" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.manager.edit', $manager->kode_user) }}" class="btn btn-outline-warning btn-sm" title="Edit Manager">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('superadmin.manager.destroy', $manager->kode_user) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('⚠️ Apakah Anda yakin ingin menghapus manager ini dari sistem Ecologix?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus Manager">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5"> {{-- Colspan disesuaikan menjadi 6 --}}
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-1 d-block mb-3"></i>
                                    <h5>Belum ada data manager</h5>
                                    <p>Silakan tambah manager baru untuk memulai monitoring emisi karbon</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($managers->hasPages())
            <div class="card-footer bg-light border-top-0 pt-3 pb-3"> {{-- Memindahkan pagination ke dalam card-footer untuk konsistensi UI --}}
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        Menampilkan {{ $managers->firstItem() }} - {{ $managers->lastItem() }} 
                        dari {{ $managers->total() }} manager
                    </div>
                    <div>
                        {{ $managers->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // $('#dataTable').DataTable(); // Dinonaktifkan karena sudah ada pagination server-side
            // Anda bisa menambahkan script lain di sini jika diperlukan
        });
    </script>
@endpush
