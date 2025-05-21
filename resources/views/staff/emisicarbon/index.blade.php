@extends('layouts.staff')

@section('title', 'Data Emisi Karbon')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Emisi Karbon</h1>
        <a href="{{ route('staff.emisicarbon.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Tambah Data Emisi
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Data Emisi Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-success">Daftar Emisi Karbon</h6>
            <div>
                <form action="{{ route('staff.emisicarbon.index') }}" method="GET" class="d-none d-md-inline-block form-inline ml-auto mr-0 my-2 my-md-0">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Cari data..." aria-label="Search" aria-describedby="basic-addon2" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-success" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Sub Kategori</th>
                            <th>Nilai Aktivitas</th>
                            <th>Hasil Konversi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emisiKarbons as $emisi)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($emisi->tanggal_emisi)->format('d/m/Y') }}</td>
                            <td>{{ $emisi->kategori_emisi_karbon }}</td>
                            <td>{{ $emisi->sub_kategori }}</td>
                            <td>{{ $emisi->nilai_aktivitas }} {{ $emisi->faktorEmisi ? $emisi->faktorEmisi->satuan : '' }}</td>
                            <td>{{ number_format($emisi->kadar_emisi_karbon, 2) }} kg CO₂e</td>
                            <td>
                                @if($emisi->status == 'approved')
                                <span class="badge bg-success text-white">Disetujui</span>
                                @elseif($emisi->status == 'rejected')
                                <span class="badge bg-danger text-white">Ditolak</span>
                                @else
                                <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('staff.emisicarbon.edit', $emisi->kode_emisi_carbon) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('staff.emisicarbon.destroy', $emisi->kode_emisi_carbon) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data emisi karbon</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
