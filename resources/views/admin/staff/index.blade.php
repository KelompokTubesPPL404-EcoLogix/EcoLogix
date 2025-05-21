@extends('layouts.admin')

@section('title', 'Manajemen Staff')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
        <a href="{{ route('admin.staff.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Staff
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Staff</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Perusahaan</th>
                            <th>No. HP</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($staffs as $staff)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $staff->nama }}</td>
                            <td>{{ $staff->email }}</td>
                            <td>{{ $staff->perusahaan->nama_perusahaan ?? 'N/A' }}</td>
                            <td>{{ $staff->no_hp ?? '-' }}</td>
                            <td>{{ $staff->alamat ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.staff.show', $staff->kode_user) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('admin.staff.edit', $staff->kode_user) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.staff.destroy', $staff->kode_user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus staff ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Data staff tidak ditemukan.</td>
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
        $('#dataTable').DataTable();
    });
</script>
@endpush