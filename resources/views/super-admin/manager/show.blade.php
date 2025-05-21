@extends('layouts.super-admin')

@section('title', 'Detail Manager')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-success fw-bold">@yield('title'): {{ $manager->nama }}</h1>
        <div>
            <a href="{{ route('superadmin.manager.edit', $manager->kode_user) }}" class="btn btn-warning shadow-sm me-2">
                <i class="bi bi-pencil me-1"></i> Edit Manager
            </a>
            <a href="{{ route('superadmin.manager.index') }}" class="btn btn-outline-secondary shadow-sm">
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
            <h6 class="m-0 fw-bold text-success"><i class="bi bi-person-badge me-2"></i>Informasi Detail Manager</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <tbody>
                        <tr>
                            <th width="30%">Kode Manager</th>
                            <td>{{ $manager->kode_user }}</td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>{{ $manager->nama }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $manager->email }}</td>
                        </tr>
                        <tr>
                            <th>Nomor HP</th>
                            <td>{{ $manager->no_hp }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td><span class="badge badge-info">{{ ucfirst($manager->role) }}</span></td>
                        </tr>
                        <tr>
                            <th>Perusahaan</th>
                            <td>
                                @if($manager->perusahaan)
                                    <a href="{{ route('superadmin.perusahaan.show', $manager->perusahaan->kode_perusahaan) }}">
                                        {{ $manager->perusahaan->nama_perusahaan }} ({{ $manager->perusahaan->kode_perusahaan }})
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>{{ $manager->created_at->format('d F Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Diperbarui</th>
                            <td>{{ $manager->updated_at->format('d F Y H:i:s') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tindakan</h6>
        </div>
        <div class="card-body">
            <a href="{{ route('superadmin.manager.edit', $manager->kode_user) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Data Manager
            </a>
            <form action="{{ route('superadmin.manager.destroy', $manager->kode_user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus manager ini? Tindakan ini tidak dapat diurungkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus Manager
                </button>
            </form>
        </div>
    </div>

</div>
@endsection