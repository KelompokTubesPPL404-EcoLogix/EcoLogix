@extends('layouts.super-admin')

@section('title', 'Daftar Perusahaan')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-success fw-bold">@yield('title')</h1>
        <a href="{{ route('superadmin.perusahaan.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Perusahaan
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

    <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom border-light">
            <h6 class="m-0 fw-bold text-success"><i class="bi bi-building me-2"></i>Daftar Perusahaan</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Perusahaan</th>
                            <th>Nama Perusahaan</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perusahaan as $p)
                        <tr>
                            <td><span class="badge bg-success-subtle text-success">{{ $p->kode_perusahaan }}</span></td>
                            <td class="fw-medium">{{ $p->nama_perusahaan }}</td>
                            <td>{{ $p->alamat_perusahaan }}</td>
                            <td>{{ $p->no_telp_perusahaan }}</td>
                            <td>{{ $p->email_perusahaan }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('superadmin.perusahaan.show', $p->kode_perusahaan) }}" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.perusahaan.edit', $p->kode_perusahaan) }}" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('superadmin.perusahaan.destroy', $p->kode_perusahaan) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus perusahaan ini?')" data-bs-toggle="tooltip" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
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