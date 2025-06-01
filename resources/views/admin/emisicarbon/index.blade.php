@extends('layouts.admin')

@section('title', 'Data Emisi Karbon')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Emisi Karbon</h1>
        <div>
            <select id="filterStatus" class="form-select form-select-sm d-inline-block w-auto me-2">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Disetujui</option>
                <option value="rejected">Ditolak</option>
            </select>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('admin.emisicarbon.admin-report') }}" class="btn btn-success btn-sm ms-2" target="_blank">
                <i class="fas fa-print"></i> Cetak Laporan Admin
            </a>
        </div>
    </div>


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
                <form action="{{ route('admin.emisicarbon.index') }}" method="GET" class="d-none d-md-inline-block form-inline ml-auto mr-0 my-2 my-md-0">
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
                <table class="table table-bordered" width="100%" cellspacing="0" id="emisiTable">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode Emisi</th>
                            <th>Staff</th>
                            <th>Kategori</th>
                            <th>Nilai Aktivitas</th>
                            <th>Hasil Konversi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emisiCarbons ?? [] as $emisi)
                        <tr data-status="{{ $emisi->status }}">
                            <td>{{ \Carbon\Carbon::parse($emisi->tanggal_emisi)->timezone('Asia/Jakarta')->format('d/m/Y') }} WIB</td>
                            <td>{{ $emisi->kode_emisi_carbon }}</td>
                            <td>{{ $emisi->staff->nama ?? 'N/A' }}</td>
                            <td>{{ $emisi->kategori_emisi_karbon }} - {{ $emisi->sub_kategori }}</td>
                            <td>{{ $emisi->nilai_aktivitas }} {{ $emisi->faktorEmisi->satuan ?? '' }}</td>
                            <td>{{ number_format($emisi->nilai_aktivitas * $emisi->faktor_emisi, 2) }} kg CO₂e</td>
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
                                <a href="{{ route('admin.emisicarbon.editStatus', $emisi) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-check2-square"></i> Review
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data emisi karbon</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Filter table by status
    document.getElementById('filterStatus').addEventListener('change', function() {
        const status = this.value;
        const rows = document.querySelectorAll('#emisiTable tbody tr');
        
        rows.forEach(row => {
            if (!status || row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection
