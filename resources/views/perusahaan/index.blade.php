@extends('layouts.app')

@section('title', 'Daftar Perusahaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Perusahaan</h3>
        <div class="float-end">
            <a href="{{ route('perusahaan.create') }}" class="btn btn-primary">Tambah Perusahaan</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
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
                        <td>{{ $p->kode_perusahaan }}</td>
                        <td>{{ $p->nama_perusahaan }}</td>
                        <td>{{ $p->alamat_perusahaan }}</td>
                        <td>{{ $p->no_telp_perusahaan }}</td>
                        <td>{{ $p->email_perusahaan }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('perusahaan.show', $p->kode_perusahaan) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('perusahaan.edit', $p->kode_perusahaan) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('perusahaan.destroy', $p->kode_perusahaan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perusahaan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data perusahaan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection