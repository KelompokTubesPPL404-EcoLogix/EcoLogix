@extends('layouts.manager')

@section('title', 'Detail Admin')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@yield('title'): {{ $admin->nama }}</h1>
        <div>
            <a href="{{ route('manager.admin.edit', $admin->kode_user) }}" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Admin
            </a>
            <a href="{{ route('manager.admin.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar Admin
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Detail Admin</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <tbody>
                        <tr>
                            <th width="30%">Kode Admin</th>
                            <td>{{ $admin->kode_user }}</td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>{{ $admin->nama }}</td>
                        </tr>
                        <tr>
                            <th>Alamat Email</th>
                            <td>{{ $admin->email }}</td>
                        </tr>
                        <tr>
                            <th>Nomor HP</th>
                            <td>{{ $admin->no_hp ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Perusahaan</th>
                            <td>{{ $admin->perusahaan->nama_perusahaan ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td><span class="badge badge-info">{{ ucfirst($admin->role) }}</span></td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>{{ $admin->created_at ? $admin->created_at->format('d M Y, H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Diperbarui</th>
                            <td>{{ $admin->updated_at ? $admin->updated_at->format('d M Y, H:i') : 'N/A' }}</td>
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
            <a href="{{ route('manager.admin.edit', $admin->kode_user) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Data Admin
            </a>
            <form action="{{ route('manager.admin.destroy', $admin->kode_user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini? Tindakan ini tidak dapat diurungkan.');">                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus Admin
                </button>
            </form>
        </div>
    </div>
</div>
@endsection