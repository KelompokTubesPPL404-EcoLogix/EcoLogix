@extends('layouts.manager')

@section('title', 'Penyedia Carbon Credit')

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
                    <i class="bi bi-building-check me-2"></i>Daftar Penyedia Carbon Credit
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-leaf me-1"></i>Kelola daftar penyedia carbon credit untuk kompensasi emisi karbon
                </p>
            </div>
            <div class="d-flex align-items-center">
                <div class="carbon-badge me-3">
                    <i class="bi bi-building-check me-1"></i>{{ count($penyediaList) }} Penyedia
                </div>
                <a href="{{ route('manager.penyedia-carbon-credit.create') }}" class="btn btn-success shadow-sm px-4">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Penyedia
                </a>
            </div>
        </div>
    </div>

    <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
        <div class="card-header eco-gradient text-white py-3">
            <h6 class="m-0 fw-bold text-success">
                <i class="bi bi-building-check me-2"></i>Data Penyedia Carbon Credit Terdaftar
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-eco table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 fw-bold text-center">
                                <i class="bi bi-hash me-1"></i>No
                            </th>
                            {{-- <th class="border-0 fw-bold">
                                <i class="bi bi-code-square me-1"></i>Kode Penyedia
                            </th> --}}
                            <th class="border-0 fw-bold">
                                <i class="bi bi-building me-1"></i>Nama Penyedia
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-building-gear me-1"></i>Perusahaan
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-cash-coin me-1"></i>Harga per Ton
                            </th>
                            <th class="border-0 fw-bold">
                                <i class="bi bi-currency-exchange me-1"></i>Mata Uang
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
                        @forelse ($penyediaList as $index => $penyedia)
                        <tr class="align-middle">
                            <td class="text-center">
                                <span class="badge bg-light text-dark fw-bold">{{ $index + 1 }}</span>
                            </td>
                            {{-- <td>
                                <span class="badge bg-success text-white px-3 py-2">{{ $penyedia->kode_penyedia }}</span>
                            </td> --}}
                            <td>
                                <div class="fw-bold text-success">{{ $penyedia->nama_penyedia }}</div>
                                <small class="text-muted">
                                    <i class="bi bi-building-check me-1"></i>Carbon Credit Provider
                                </small>
                            </td>
                            <td>
                                @if($penyedia->perusahaan)
                                <div class="d-flex align-items-center">
                                    {{-- <span class="badge bg-light text-success border border-success me-2">
                                        {{ $penyedia->kode_perusahaan }}
                                    </span> --}}
                                    <span class="fw-bold">{{ $penyedia->perusahaan->nama_perusahaan }}</span>
                                </div>
                                @else
                                <span class="text-muted">
                                    <i class="bi bi-dash-circle me-1"></i>Tidak terkait dengan perusahaan
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-cash-coin text-success me-2"></i>
                                    <span class="fw-bold">{{ $penyedia->mata_uang }} {{ number_format($penyedia->harga_per_ton, 2) }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-currency-exchange text-success me-2"></i>
                                    <span class="fw-bold">{{ $penyedia->mata_uang }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($penyedia->is_active)
                                    <span class="badge bg-success text-white px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge bg-danger text-white px-3 py-2">
                                        <i class="bi bi-x-circle-fill me-1"></i> Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('manager.penyedia-carbon-credit.show', $penyedia->kode_penyedia) }}" class="btn btn-outline-info btn-action" title="Lihat Detail Penyedia">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('manager.penyedia-carbon-credit.edit', $penyedia->kode_penyedia) }}" class="btn btn-outline-warning btn-action" title="Edit Data Penyedia">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('manager.penyedia-carbon-credit.destroy', $penyedia->kode_penyedia) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penyedia {{ $penyedia->nama_penyedia }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-action" title="Hapus Penyedia">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data penyedia carbon credit
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