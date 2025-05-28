@extends('layouts.manager')

@section('title', 'Pembelian Carbon Credit')

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
                    <i class="bi bi-cart-check me-2"></i>Daftar Pembelian Carbon Credit
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-receipt me-1"></i>Kelola daftar pembelian carbon credit untuk kompensasi emisi karbon
                </p>
            </div>
            <div class="d-flex align-items-center">
                <div class="carbon-badge me-3">
                    <i class="bi bi-cart-check me-1"></i>{{ count($pembelianList) }} Pembelian
                </div>
                <a href="{{ route('manager.pembelian-carbon-credit.create') }}" class="btn btn-success shadow-sm px-4">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Pembelian
                </a>
            </div>
        </div>
    </div>

    <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
        <div class="card-header eco-gradient text-white py-3">
            <h6 class="m-0 fw-bold text-white">
                <i class="bi bi-table me-2"></i>Data Pembelian Carbon Credit
            </h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-eco table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Kode Pembelian</th>
                            <th>Tanggal Pembelian</th>
                            <th>Jumlah Kompensasi</th>
                            <th>Deskripsi</th>
                            <th>Bukti Pembayaran</th>
                            <th>Kode Penyedia</th>
                            <th>Total Harga</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembelianList as $index => $pembelian)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $pembelian->kode_pembelian_carbon_credit }}</td>
                                <td>{{ date('d/m/Y', strtotime($pembelian->tanggal_pembelian)) }}</td>
                                <td>{{ number_format($pembelian->jumlah_kompensasi, 2) }} ton</td>
                                <td>{{ Str::limit($pembelian->deskripsi, 30) }}</td>
                                <td>
                                    @if($pembelian->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $pembelian->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-image me-1"></i>Lihat Bukti
                                        </a>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Ada</span>
                                    @endif
                                </td>                                <td>
                                    @if($pembelian->penyedia)
                                        <span class="badge bg-info">{{ $pembelian->penyedia->nama_penyedia }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Tidak ada data penyedia</span>
                                    @endif
                                </td>
                                <td>{{ number_format($pembelian->total_harga, 2) }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('manager.pembelian-carbon-credit.show', $pembelian->kode_pembelian_carbon_credit) }}" class="btn btn-sm btn-info btn-action" data-bs-toggle="tooltip" title="Detail">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('manager.pembelian-carbon-credit.edit', $pembelian->kode_pembelian_carbon_credit) }}" class="btn btn-sm btn-warning btn-action" data-bs-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <form action="{{ route('manager.pembelian-carbon-credit.destroy', $pembelian->kode_pembelian_carbon_credit) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pembelian ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-action" data-bs-toggle="tooltip" title="Hapus">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3 mb-0">Belum ada data pembelian carbon credit</h5>
                                        <p class="text-muted mb-3">Silahkan tambahkan data pembelian carbon credit baru</p>
                                        <a href="{{ route('manager.pembelian-carbon-credit.create') }}" class="btn btn-outline-success">
                                            <i class="bi bi-plus-circle me-1"></i> Tambah Pembelian
                                        </a>
                                    </div>
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

@push('js')
<script>
    $(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush
