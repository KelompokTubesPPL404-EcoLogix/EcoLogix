@extends('layouts.manager')

@section('title', 'Daftar Faktor Emisi')

@push('css')
<style>
    .eco-gradient {
        background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%);
    }
    .eco-card {
        border-left: 4px solid #28a745;
        transition: all 0.3s ease;
    }
    .eco-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15);
    }
    .eco-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 3px solid #28a745;
    }
    .btn-action {
        padding: 0.5rem 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        margin-right: 0.5rem;
    }
    .btn-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .table-action {
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Enhanced Eco Header Section -->
    <div class="eco-header p-4 rounded-3 mb-4">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-2 text-success fw-bold">
                    <i class="bi bi-calculator me-2"></i>Daftar Faktor Emisi
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>Kelola faktor emisi yang digunakan untuk perhitungan emisi karbon
                </p>
            </div>
            <a href="{{ route('manager.faktor-emisi.create') }}" class="btn btn-success shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Faktor Emisi
            </a>
        </div>
    </div>

    

    <div class="card eco-card border-0 shadow-lg mb-4 rounded-3">
        <div class="card-header eco-gradient py-3">
            <h6 class="m-0 fw-bold text-success">
                <i class="bi bi-table me-2"></i>Data Faktor Emisi
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Faktor</th>
                            <th>Kategori Emisi Karbon</th>
                            <th>Sub Kategori</th>
                            <th>Nilai Faktor</th>
                            <th>Satuan</th>
                            <th class="table-action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($faktorEmisi as $item)
                        <tr>
                            <td>
                                <span class="badge bg-success text-white px-3 py-2">{{ $item->kode_faktor }}</span>
                            </td>
                            <td>{{ $item->kategori_emisi_karbon }}</td>
                            <td>{{ $item->sub_kategori }}</td>
                            <td>{{ $item->nilai_faktor }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('manager.faktor-emisi.show', $item->kode_faktor) }}" class="btn btn-info btn-sm me-1">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('manager.faktor-emisi.edit', $item->kode_faktor) }}" class="btn btn-warning btn-sm me-1">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('manager.faktor-emisi.destroy', $item->kode_faktor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">Tidak ada data faktor emisi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection