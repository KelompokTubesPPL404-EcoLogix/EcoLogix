@extends('layouts.super-admin')

@section('title', 'Detail Perusahaan')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-success fw-bold">@yield('title'): {{ $perusahaan->nama_perusahaan }}</h1>
        <div>
            <a href="{{ route('superadmin.perusahaan.edit', $perusahaan->kode_perusahaan) }}" class="btn btn-warning shadow-sm me-2">
                <i class="bi bi-pencil me-1"></i> Edit Perusahaan
            </a>
            <a href="{{ route('superadmin.perusahaan.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom border-light">
            <h6 class="m-0 fw-bold text-success"><i class="bi bi-building me-2"></i>Informasi Detail Perusahaan</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover" width="100%" cellspacing="0">
                    <tbody>
                        <tr>
                            <th width="30%" class="bg-light">Kode Perusahaan</th>
                            <td><span class="badge bg-success-subtle text-success">{{ $perusahaan->kode_perusahaan }}</span></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Nama Perusahaan</th>
                            <td class="fw-medium">{{ $perusahaan->nama_perusahaan }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Alamat</th>
                            <td>{{ $perusahaan->alamat_perusahaan }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">No. Telepon</th>
                            <td>{{ $perusahaan->no_telp_perusahaan }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Email</th>
                            <td><a href="mailto:{{ $perusahaan->email_perusahaan }}" class="text-decoration-none text-success">{{ $perusahaan->email_perusahaan }}</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                <form action="{{ route('superadmin.perusahaan.destroy', $perusahaan->kode_perusahaan) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perusahaan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i> Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection