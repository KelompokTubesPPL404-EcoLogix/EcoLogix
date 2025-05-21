@extends('layouts.super-admin')

@section('title', 'Manajemen Manager')

@push('css')
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-success fw-bold">@yield('title')</h1>
        <a href="{{ route('superadmin.manager.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Manager
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

    <!-- Filter by Perusahaan -->
    <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom border-light">
            <h6 class="m-0 fw-bold text-success"><i class="bi bi-funnel me-2"></i>Filter Manager</h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('superadmin.manager.index') }}">
                <div class="row align-items-center">
                    <label for="perusahaan_id" class="col-sm-2 col-form-label fw-medium">Perusahaan</label>
                    <div class="col-sm-8">
                        <select name="perusahaan_id" id="perusahaan_id" class="form-select">
                            <option value="">Semua Perusahaan</option>
                            @foreach($perusahaanList as $perusahaan)
                                <option value="{{ $perusahaan->kode_perusahaan }}" {{ $perusahaanId == $perusahaan->kode_perusahaan ? 'selected' : '' }}>
                                    {{ $perusahaan->nama_perusahaan }} ({{ $perusahaan->kode_perusahaan }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-success w-100"><i class="bi bi-search me-1"></i> Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom border-light">
            <h6 class="m-0 fw-bold text-success"><i class="bi bi-person-badge me-2"></i>Daftar Manager</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Manager</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Perusahaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($managers as $index => $manager)
                        <tr>
                            <td>{{ $managers->firstItem() + $index }}</td>
                            <td><span class="badge bg-success-subtle text-success">{{ $manager->kode_user }}</span></td>
                            <td>{{ $manager->nama }}</td>
                            <td>{{ $manager->email }}</td>
                            <td>{{ $manager->no_hp }}</td>
                            <td>
                                @if($manager->perusahaan)
                                    <a href="{{ route('superadmin.perusahaan.show', $manager->perusahaan->kode_perusahaan) }}" class="text-decoration-none text-success">
                                        {{ $manager->perusahaan->nama_perusahaan }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('superadmin.manager.show', $manager->kode_user) }}" class="btn btn-info btn-sm" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('superadmin.manager.edit', $manager->kode_user) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('superadmin.manager.destroy', $manager->kode_user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus manager ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data manager.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $managers->appends(request()->query())->links() }}
            </div>
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
        });
    </script>
@endpush