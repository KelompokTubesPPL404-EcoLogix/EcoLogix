@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Penyedia Carbon Credit</span>
                    <a href="{{ route('manager.penyedia-carbon-credit.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Penyedia
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kode Penyedia</th>
                                    <th>Nama Penyedia</th>
                                    <th>Harga per Ton</th>
                                    <th>Mata Uang</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penyediaList as $penyedia)
                                <tr>
                                    <td>{{ $penyedia->kode_penyedia }}</td>
                                    <td>{{ $penyedia->nama_penyedia }}</td>
                                    <td>{{ number_format($penyedia->harga_per_ton, 2) }}</td>
                                    <td>{{ $penyedia->mata_uang }}</td>
                                    <td>
                                        @if($penyedia->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('manager.penyedia-carbon-credit.show', $penyedia->kode_penyedia) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('manager.penyedia-carbon-credit.edit', $penyedia->kode_penyedia) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('manager.penyedia-carbon-credit.destroy', $penyedia->kode_penyedia) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penyedia ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data penyedia carbon credit</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection