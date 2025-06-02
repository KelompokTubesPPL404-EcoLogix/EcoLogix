@extends('layouts.admin')

@section('title', 'Detail Staff')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@yield('title'): {{ $staff->nama }}</h1>
        <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar Staff
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Detail Staff</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="200px">Kode User</th>
                    <td>{{ $staff->kode_user }}</td>
                </tr>
                <tr>
                    <th>Nama Lengkap</th>
                    <td>{{ $staff->nama }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $staff->email }}</td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td><span class="badge badge-info">{{ ucfirst($staff->role) }}</span></td>
                </tr>
                <tr>
                    <th>Nomor HP</th>
                    <td>{{ $staff->no_hp ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $staff->alamat ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Perusahaan</th>
                    <td>{{ $staff->perusahaan->nama_perusahaan ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Terdaftar Pada</th>
                    <td>{{ $staff->created_at ? $staff->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB' : '-' }}</td>
                </tr>
                <tr>
                    <th>Diperbarui Pada</th>
                    <td>{{ $staff->updated_at ? $staff->updated_at->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB' : '-' }}</td>
                </tr>
            </table>
            <a href="{{ route('admin.staff.edit', $staff->kode_user) }}" class="btn btn-warning">Edit Data</a>
        </div>
    </div>
</div>
@endsection