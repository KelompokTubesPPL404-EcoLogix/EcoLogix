@extends('layouts.admin')

@section('title', 'Manajemen Staff')

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
        .staff-badge {
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
                    <i class="bi bi-person-badge me-2"></i>@yield('title')
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-people me-1"></i>Kelola staff perusahaan dalam sistem monitoring emisi karbon Ecologix
                </p>
            </div>
            <div class="d-flex align-items-center">
                <div class="staff-badge me-3">
                    <i class="bi bi-person-badge me-1"></i>{{ count($staffs) }} Staff
                </div>
                <a href="{{ route('admin.staff.create') }}" class="btn btn-success shadow-sm px-4">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Staff Baru
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

    <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
        <div class="card-header eco-gradient text-white py-3">
            <h6 class="m-0 fw-bold text-success">
                <i class="bi bi-table me-2"></i>Daftar Staff Terdaftar
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-eco table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="border-0 fw-bold text-center">
                                <i class="bi bi-hash me-1"></i>No
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-person me-1"></i>Nama
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-envelope me-1"></i>Email
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-building me-1"></i>Perusahaan
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-telephone me-1"></i>No. HP
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-geo-alt me-1"></i>Alamat
                            </th>
                            <th class="border-0 fw-bold text-center">
                                <i class="bi bi-gear me-1"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($staffs as $staff)
                        <tr class="align-middle">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-success-subtle p-2 me-2">
                                        <i class="bi bi-person-fill text-success"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium text-success">{{ $staff->nama }}</div>
                                        <small class="text-muted">Staff Ecologix</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <i class="bi bi-envelope-fill text-success me-1"></i>
                                {{ $staff->email }}
                            </td>
                            <td>
                                @if($staff->perusahaan)
                                    <div class="staff-badge">
                                        <i class="bi bi-building-fill me-1"></i>
                                        {{ $staff->perusahaan->nama_perusahaan }}
                                    </div>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Tidak ada perusahaan
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($staff->no_hp)
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-phone-fill text-success me-2"></i>
                                        <span>{{ $staff->no_hp }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 150px;" title="{{ $staff->alamat }}">
                                    @if($staff->alamat)
                                        <i class="bi bi-geo-alt-fill text-success me-1"></i>
                                        {{ Str::limit($staff->alamat, 30) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                
                                    <a href="{{ route('admin.staff.show', $staff->kode_user) }}" class="btn btn-info btn-action btn-sm" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.staff.edit', $staff->kode_user) }}" class="btn btn-warning btn-action btn-sm" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.staff.destroy', $staff->kode_user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-action btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus staff ini?')" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data staff
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json"
            }
        });
    });
</script>
@endpush