@extends('layouts.staff')

@section('title', 'Data Emisi Karbon')

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
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-left: 4px solid #28a745;
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
        }
        .stats-icon {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .table-eco {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .table-eco thead {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        .table-eco tbody tr:hover {
            background-color: rgba(40, 167, 69, 0.05);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
        .btn-action {
            padding: 0.25rem 0.5rem;
            margin: 0 0.1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        .btn-action:hover {
            transform: translateY(-1px);
        }
        .carbon-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
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
                    <i class="bi bi-cloud me-2"></i>Data Emisi Karbon
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>Kelola data emisi karbon yang telah diinput
                </p>
            </div>
            <div class="d-flex align-items-center">
                <div class="carbon-badge me-3">
                    <i class="bi bi-cloud-check me-1"></i>{{ $emisiKarbons->count() }} Data
                </div>
                <a href="{{ route('staff.emisicarbon.create') }}" class="btn btn-success shadow-sm px-4 me-2">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Data Emisi
                </a>
                <a href="{{ route('staff.emisicarbon.report') }}" class="btn btn-primary shadow-sm px-4" target="_blank">
                    <i class="bi bi-file-pdf me-1"></i> Laporan PDF
                </a>
            </div>
        </div>
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
    <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
        <div class="card-header eco-gradient text-white py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 fw-bold text-success">
                <i class="bi bi-list-ul me-2"></i>Data Emisi Karbon Tercatat
            </h6>
            <div>
                <form action="{{ route('staff.emisicarbon.index') }}" method="GET" class="d-none d-md-inline-block form-inline ml-auto mr-0 my-2 my-md-0">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Cari data..." aria-label="Search" aria-describedby="basic-addon2" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-light" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-eco table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 fw-bold text-center">
                                <i class="bi bi-calendar-date me-1"></i>Tanggal
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-tags me-1"></i>Kategori
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-tag me-1"></i>Sub Kategori
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-activity me-1"></i>Nilai Aktivitas
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-calculator me-1"></i>Hasil Konversi
                            </th>
                            <th class="border-0 fw-bold text-center">
                                <i class="bi bi-circle-fill me-1"></i>Status
                            </th>
                            <th class="border-0 fw-bold text-center">
                                <i class="bi bi-gear me-1"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emisiKarbons as $emisi)
                        <tr class="align-middle">
                            <td class="text-center">
                                <span class="badge bg-light text-dark fw-bold">
                                    {{ \Carbon\Carbon::parse($emisi->tanggal_emisi)->timezone('Asia/Jakarta')->format('d/m/Y') }} WIB
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-success">{{ $emisi->kategori_emisi_karbon }}</div>
                            </td>
                            <td>{{ $emisi->sub_kategori }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-activity text-success me-2"></i>
                                    <span class="fw-bold">
                                        {{ $emisi->nilai_aktivitas }} {{ $emisi->faktorEmisi ? $emisi->faktorEmisi->satuan : '' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calculator text-success me-2"></i>
                                    <span class="fw-bold">
                                        {{ number_format($emisi->kadar_emisi_karbon, 2) }} kg CO₂e
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($emisi->status == 'approved')
                                    <span class="badge bg-success text-white px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                    </span>
                                @elseif($emisi->status == 'rejected')
                                    <span class="badge bg-danger text-white px-3 py-2">
                                        <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="bi bi-hourglass-split me-1"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('staff.emisicarbon.show', $emisi->kode_emisi_carbon) }}" class="btn btn-outline-info btn-action" title="Lihat Detail Emisi">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('staff.emisicarbon.edit', $emisi->kode_emisi_carbon) }}" class="btn btn-outline-warning btn-action" title="Edit Data Emisi">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('staff.emisicarbon.destroy', $emisi->kode_emisi_carbon) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-action" title="Hapus Data Emisi">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data emisi karbon
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
