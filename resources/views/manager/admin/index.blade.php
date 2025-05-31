@extends('layouts.manager')

@section('title', 'Manajemen Admin Perusahaan')

@push('css')
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
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
        .admin-badge {
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
                    <i class="bi bi-people-fill me-2"></i>Daftar Admin Perusahaan
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-person-badge me-1"></i>Kelola daftar admin untuk perusahaan Anda
                </p>
            </div>
            <div class="d-flex align-items-center">
                <div class="admin-badge me-3">
                    <i class="bi bi-people-fill me-1"></i>{{ count($admins) }} Admin
                </div>
                <a href="{{ route('manager.admin.create') }}" class="btn btn-success shadow-sm px-4">
                    <i class="bi bi-plus-circle-fill me-1"></i> Tambah Admin
                </a>
            </div>
        </div>
    </div>


    <!-- @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif -->

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- DataTales Example -->
    <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
        <div class="card-header eco-gradient text-white py-3">
            <h6 class="m-0 fw-bold text-success">
                <i class="bi bi-person-badge me-2"></i>Data Admin untuk Perusahaan: {{ Auth::user()->perusahaan->nama_perusahaan ?? 'N/A' }}
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
                                <i class="bi bi-person-badge me-1"></i>Kode Admin
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-person me-1"></i>Nama
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-envelope me-1"></i>Email
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-telephone me-1"></i>No. HP
                            </th>
                            <th class="border-0 fw-bold text-center">
                                <i class="bi bi-gear me-1"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $index => $admin)
                        <tr class="align-middle">
                            <td class="text-center">
                                <span class="badge bg-light text-dark fw-bold">{{ $admins->firstItem() + $index }}</span>
                            </td>
                            <td>
                                <span class="badge bg-success text-white px-3 py-2">{{ $admin->kode_user }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-success">{{ $admin->nama }}</div>
                                <small class="text-muted">
                                    <i class="bi bi-person-badge me-1"></i>Admin
                                </small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-envelope text-success me-2"></i>
                                    <span>{{ $admin->email }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-telephone text-success me-2"></i>
                                    <span>{{ $admin->no_hp }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('manager.admin.show', $admin->kode_user) }}" class="btn btn-outline-info btn-action" title="Lihat Detail Admin">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('manager.admin.edit', $admin->kode_user) }}" class="btn btn-outline-warning btn-action" title="Edit Data Admin">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('manager.admin.destroy', $admin->kode_user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin {{ $admin->nama }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-action" title="Hapus Admin">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fs-1 d-block mb-2"></i>
                                Belum ada data admin untuk perusahaan Anda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">
                    <i class="bi bi-info-circle me-1"></i> Menampilkan {{ $admins->count() }} dari {{ $admins->total() }} data admin
                </span>
                <div>
                    {{ $admins->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        // $(document).ready(function() {
        //     $('#dataTable').DataTable(); // Pagination server-side sudah ada
        // });
    </script>
@endpush