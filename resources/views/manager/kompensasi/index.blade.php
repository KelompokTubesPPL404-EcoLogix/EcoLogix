@extends('layouts.manager')

@section('title', 'Kompensasi Emisi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kompensasi Emisi</h1>
    </div>

    <!-- Ringkasan Emisi per Kategori -->
    <div class="row">
        @foreach($kategoriEmisi as $data)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ $data['kategori'] }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($data['total'], 2) }} ton</div>
                            <div class="mt-2 small">
                                <span class="text-success">Terkompensasi: {{ number_format($data['terkompensasi'], 2) }} ton</span><br>
                                <span class="text-danger">Sisa Emisi: {{ number_format($data['sisa'], 2) }} ton</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cloud-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Detail Emisi Disetujui -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-success">Detail Emisi Disetujui</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Sub Kategori</th>
                            <th>Nilai Aktivitas</th>
                            <th>Faktor Emisi</th>
                            <th>Kadar Emisi (kg)</th>
                            <th>Emisi (ton)</th>
                            <th>Kompensasi (ton)</th>
                            <th>Sisa Emisi (ton)</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($emisiApproved as $item)
                        <tr>
                            <td class="text-center">{{ $item->kode_emisi_carbon }}</td>
                            <td>{{ $item->kategori_emisi_karbon }}</td>
                            <td>{{ $item->sub_kategori }}</td>
                            <td class="text-end">{{ number_format($item->nilai_aktivitas, 2) }}</td>
                            <td class="text-end">{{ number_format($item->faktor_emisi, 4) }}</td>
                            <td class="text-end">{{ number_format($item->kadar_emisi_karbon, 2) }}</td>
                            <td class="text-end">{{ number_format($item->emisi_ton, 2) }}</td>
                            <td class="text-end text-success">{{ number_format($item->kompensasi_ton, 2) }}</td>
                            <td class="text-end text-danger">{{ number_format($item->sisa_emisi_ton, 2) }}</td>
                            <td>{{ $item->deskripsi }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_emisi)->timezone('Asia/Jakarta')->format('d M Y') }} WIB</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Form Kompensasi Emisi (Fixed) -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-success">Form Kompensasi Emisi</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <form action="{{ route('manager.kompensasi.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="kode_emisi_carbon" class="form-label fw-semibold">Pilih Emisi</label>
                        <select name="kode_emisi_carbon" id="kode_emisi_carbon" class="form-select" required>
                            <option value="" disabled selected>-- Pilih --</option>
                            @foreach($emisiApproved as $item)
                            <option value="{{ $item->kode_emisi_carbon }}" {{ old('kode_emisi_carbon') == $item->kode_emisi_carbon ? 'selected' : '' }}>
                                {{ $item->kode_emisi_carbon }} - {{ $item->kategori_emisi_karbon }} (Sisa: {{ number_format($item->sisa_emisi_ton, 2) }} ton)
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="jumlah_kompensasi" class="form-label fw-semibold">Jumlah Kompensasi (kg)</label>
                        <input type="number" name="jumlah_kompensasi" id="jumlah_kompensasi" step="0.01" class="form-control" value="{{ old('jumlah_kompensasi') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="tanggal_kompensasi" class="form-label fw-semibold">Tanggal Kompensasi</label>
                        <input type="date" name="tanggal_kompensasi" id="tanggal_kompensasi" class="form-control" value="{{ old('tanggal_kompensasi', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Kompensasi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Riwayat Kompensasi -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-success">Riwayat Kompensasi</h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center mb-3">
                <div class="col-md-3">
                    <input type="text" name="search" placeholder="Cari kode atau kategori..." class="form-control" value="{{ request()->search }}">
                </div>
                <div class="col-md-2">
                    <select name="kategori" class="form-select">
                        <option value="">-- Kategori --</option>
                        @foreach($kategoris as $kat)
                        <option value="{{ $kat->kategori_emisi_karbon }}" {{ request('kategori_emisi_karbon') == $kat->kategori_emisi_karbon ? 'selected' : '' }}>
                            {{ $kat->kategori_emisi_karbon }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="start_date" class="form-control" value="{{ request()->start_date }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="end_date" class="form-control" value="{{ request()->end_date }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">-- Status --</option>
                        <option value="approved" {{ request('status') == 'Terkompensasi' ? 'selected' : '' }}>Terkompensasi</option>
                        <option value="pending" {{ request('status') == 'Belum Terkompensasi' ? 'selected' : '' }}>Belum Terkompensasi</option>
                    </select>
                </div>
                <div class="col-md-1 d-grid">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="bg-secondary text-white text-center">
                        <tr>
                            <th>Kode Kompensasi</th>
                            <th>Kode Emisi</th>
                            <th>Kategori</th>
                            <th>Sub Kategori</th>
                            <th>Kompensasi (ton)</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatKompensasi as $row)
                        <tr>
                            <td class="text-center">{{ $row->kode_kompensasi }}</td>
                            <td class="text-center">{{ $row->kode_emisi_carbon }}</td>
                            <td>{{ $row->kategori_emisi }}</td>
                            <td>{{ $row->sub_kategori }}</td>
                            <td class="text-end text-success">{{ number_format($row->jumlah_ton, 2) }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($row->tanggal_kompensasi)->timezone('Asia/Jakarta')->format('d M Y') }} WIB</td>
                            <td class="text-center">
                                @php
                                    $statusClass = match($row->status) {
                                        'Terkompensasi' => 'badge bg-success',
                                        'Belum Terkompensasi' => 'badge bg-warning text-dark',
                                        default => 'badge bg-secondary',
                                    };
                                @endphp
                                <span class="{{ $statusClass }}">{{ ucfirst($row->status) }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('manager.kompensasi.show', $row->kode_kompensasi) }}" 
                                  class="btn btn-sm btn-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($row->status === 'Belum Terkompensasi')
                                    <a href="{{ route('manager.kompensasi.edit', $row->kode_kompensasi) }}" 
                                      class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('manager.kompensasi.destroy', $row->kode_kompensasi) }}" 
                                          method="POST" style="display:inline;" 
                                          onsubmit="return confirm('Hapus data kompensasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data kompensasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>
@endsection