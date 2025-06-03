@extends('layouts.manager')

@section('title', 'Kompensasi Emisi')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-arrow-repeat text-success me-2"></i>Kompensasi Emisi</h1>
        <div>
            <a href="{{ route('manager.kompensasi.index') }}" class="btn btn-sm btn-success shadow-sm">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
            </a>
        </div>
    </div>

    <!-- Ringkasan Emisi per Kategori -->
    <div class="row mb-4">
        @foreach($kategoriEmisi as $data)
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card shadow h-100 border-0 rounded-lg overflow-hidden">
                <div class="card-header bg-gradient-success text py-3">
                    <h6 class="m-0 font-weight-bold text">{{ $data['kategori'] }}</h6>
                </div>
                <div class="card-body position-relative">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="h3 font-weight-bold text-gray-800 mb-1">{{ number_format($data['total'], 2) }} <small class="text-gray-600">ton</small></div>
                            <div class="progress mb-3" style="height: 10px;">
                                @php $percentage = ($data['total'] > 0) ? min(100, ($data['terkompensasi'] / $data['total']) * 100) : 0; @endphp
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <div class="text-success">
                                    <i class="bi bi-check-circle me-1"></i> {{ number_format($data['terkompensasi'], 2) }} ton
                                </div>
                                <div class="text-{{ $data['sisa'] > 0 ? 'danger' : 'success' }}">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ number_format(max(0, $data['sisa']), 2) }} ton
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-cloud-fill fa-3x text-light-success opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Detail Emisi Disetujui -->
    <div class="card shadow mb-4 border-0 rounded-lg overflow-hidden">
        <div class="card-header py-3 bg-gradient-light d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="bi bi-clipboard-check me-2"></i>Detail Emisi Disetujui
            </h6>
            <div>
                <button class="btn btn-sm btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEmisiApproved">
                    <i class="bi bi-arrows-expand"></i>
                </button>
            </div>
        </div>
        <div class="collapse show" id="collapseEmisiApproved">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr class="bg-success text-white">
                                <th class="px-3 py-3">Kode</th>
                                <th class="px-3 py-3">Kategori</th>
                                <th class="px-3 py-3">Sub Kategori</th>
                                <th class="px-3 py-3 text-end">Nilai Aktivitas</th>
                                <th class="px-3 py-3 text-end">Faktor Emisi</th>
                                <th class="px-3 py-3 text-end">Kadar Emisi (kg)</th>
                                <th class="px-3 py-3 text-end">Emisi (ton)</th>
                                <th class="px-3 py-3 text-end">Kompensasi (ton)</th>
                                <th class="px-3 py-3 text-end">Sisa Emisi (ton)</th>
                                <th class="px-3 py-3">Deskripsi</th>
                                <th class="px-3 py-3 text-center">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($emisiApproved as $item)
                            <tr>
                                <td class="px-3 py-2 text-center"><span class="badge bg-light text-dark">{{ $item->kode_emisi_carbon }}</span></td>
                                <td class="px-3 py-2 fw-medium">{{ $item->kategori_emisi_karbon }}</td>
                                <td class="px-3 py-2">{{ $item->sub_kategori }}</td>
                                <td class="px-3 py-2 text-end">{{ number_format($item->nilai_aktivitas, 2) }}</td>
                                <td class="px-3 py-2 text-end">{{ number_format($item->faktor_emisi, 4) }}</td>
                                <td class="px-3 py-2 text-end">{{ number_format($item->kadar_emisi_karbon, 2) }}</td>
                                <td class="px-3 py-2 text-end">{{ number_format($item->emisi_ton, 2) }}</td>
                                <td class="px-3 py-2 text-end">
                                    <span class="text-success fw-medium">{{ number_format($item->kompensasi_ton, 2) }}</span>
                                </td>
                                <td class="px-3 py-2 text-end">
                                    <span class="text-{{ $item->sisa_emisi_ton > 0 ? 'danger' : 'success' }} fw-medium">{{ number_format(max(0, $item->sisa_emisi_ton), 2) }}</span>
                                </td>
                                <td class="px-3 py-2">{{ Str::limit($item->deskripsi, 50) }}</td>
                                <td class="px-3 py-2 text-center">
                                    <span class="small">
                                        <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($item->tanggal_emisi)->timezone('Asia/Jakarta')->format('d M Y') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">Tidak ada data emisi disetujui</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Kompensasi Emisi -->
    <div class="card shadow mb-4 border-0 rounded-lg overflow-hidden">
        <div class="card-header py-3 bg-gradient-primary d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="bi bi-plus-circle me-2"></i>Form Kompensasi Emisi
            </h6>
            <div>
                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKompensasiForm">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
        </div>
        <div class="collapse show" id="collapseKompensasiForm">
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5 mt-1"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                <form action="{{ route('manager.kompensasi.store') }}" method="POST" class="bg-light p-4 rounded-3 shadow-sm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label for="kode_emisi_carbon" class="form-label fw-medium">
                                <i class="bi bi-tags me-1 text-primary"></i> Pilih Emisi
                            </label>
                            <select name="kode_emisi_carbon" id="kode_emisi_carbon" class="form-select shadow-none border-0 bg-white" required>
                                <option value="" disabled selected>-- Pilih Emisi --</option>
                                @foreach($emisiApproved as $item)
                                <option value="{{ $item->kode_emisi_carbon }}" {{ old('kode_emisi_carbon') == $item->kode_emisi_carbon ? 'selected' : '' }}>
                                    {{ $item->kode_emisi_carbon }} - {{ $item->kategori_emisi_karbon }} (Sisa: {{ number_format(max(0, $item->sisa_emisi_ton), 2) }} ton)
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="jumlah_kompensasi" class="form-label fw-medium">
                                <i class="bi bi-calculator me-1 text-primary"></i> Jumlah Kompensasi (kg)
                            </label>
                            <div class="input-group">
                                <input type="number" name="jumlah_kompensasi" id="jumlah_kompensasi" step="0.01" 
                                    class="form-control shadow-none border-0" 
                                    value="{{ old('jumlah_kompensasi') }}" 
                                    placeholder="0.00" required>
                                <span class="input-group-text bg-white border-0">kg</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_kompensasi" class="form-label fw-medium">
                                <i class="bi bi-calendar-date me-1 text-primary"></i> Tanggal Kompensasi
                            </label>
                            <input type="date" name="tanggal_kompensasi" id="tanggal_kompensasi" 
                                class="form-control shadow-none border-0" 
                                value="{{ old('tanggal_kompensasi', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                <i class="bi bi-check2 me-1"></i> Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Riwayat Kompensasi -->
    <div class="card shadow mb-4 border-0 rounded-lg overflow-hidden">
        <div class="card-header eco-gradient text-white py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="bi bi-clock-history me-2"></i>Riwayat Kompensasi
            </h6>
            <div>
                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRiwayatKompensasi">
                    <i class="bi bi-arrows-expand"></i>
                </button>
            </div>
        </div>
        <div class="collapse show" id="collapseRiwayatKompensasi">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-center mb-4 bg-light p-3 rounded-3 shadow-sm">
                    <div class="col-lg-3 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0">
                                <i class="bi bi-search text-primary"></i>
                            </span>
                            <input type="text" name="search" placeholder="Cari kode atau kategori..." 
                                class="form-control shadow-none border-0" 
                                value="{{ request()->search }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select name="kategori" class="form-select shadow-none border-0">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                            <option value="{{ $kat->kategori_emisi_karbon }}" {{ request('kategori_emisi_karbon') == $kat->kategori_emisi_karbon ? 'selected' : '' }}>
                                {{ $kat->kategori_emisi_karbon }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0">
                                <i class="bi bi-calendar3 text-primary"></i>
                            </span>
                            <input type="date" name="start_date" 
                                class="form-control shadow-none border-0" 
                                placeholder="Tanggal Mulai" 
                                value="{{ request()->start_date }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0">
                                <i class="bi bi-calendar3 text-primary"></i>
                            </span>
                            <input type="date" name="end_date" 
                                class="form-control shadow-none border-0" 
                                placeholder="Tanggal Akhir" 
                                value="{{ request()->end_date }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select name="status" class="form-select shadow-none border-0">
                            <option value="">-- Pilih Status --</option>
                            <option value="approved" {{ request('status') == 'Terkompensasi' ? 'selected' : '' }}>Terkompensasi</option>
                            <option value="pending" {{ request('status') == 'Belum Terkompensasi' ? 'selected' : '' }}>Belum Terkompensasi</option>
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-6 d-grid">
                        <button type="submit" class="btn btn-primary shadow-sm">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tabelRiwayat">
                        <thead>
                            <tr class="bg-secondary text-white">
                                <th class="px-3 py-3">Kode Kompensasi</th>
                                <th class="px-3 py-3">Kode Emisi</th>
                                <th class="px-3 py-3">Kategori</th>
                                <th class="px-3 py-3">Sub Kategori</th>
                                <th class="px-3 py-3 text-end">Kompensasi (ton)</th>
                                <th class="px-3 py-3 text-center">Tanggal</th>
                                <th class="px-3 py-3 text-center">Status</th>
                                <th class="px-3 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatKompensasi as $row)
                            <tr>
                                <td class="px-3 py-2">
                                    <span class="badge bg-light text-dark">{{ $row->kode_kompensasi }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    <span class="badge bg-secondary text-white">{{ $row->kode_emisi_carbon }}</span>
                                </td>
                                <td class="px-3 py-2 fw-medium">{{ $row->kategori_emisi }}</td>
                                <td class="px-3 py-2">{{ $row->sub_kategori }}</td>
                                <td class="px-3 py-2 text-end fw-bold text-success">{{ number_format($row->jumlah_ton, 2) }}</td>
                                <td class="px-3 py-2 text-center">
                                    <span class="small">
                                        <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($row->tanggal_kompensasi)->timezone('Asia/Jakarta')->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    @php
                                        $statusIcon = match($row->status) {
                                            'Terkompensasi' => '<i class="bi bi-check-circle me-1"></i>',
                                            'Belum Terkompensasi' => '<i class="bi bi-clock me-1"></i>',
                                            default => '<i class="bi bi-question-circle me-1"></i>',
                                        };
                                        $statusClass = match($row->status) {
                                            'Terkompensasi' => 'badge bg-success rounded-pill px-3',
                                            'Belum Terkompensasi' => 'badge bg-warning text-dark rounded-pill px-3',
                                            default => 'badge bg-secondary rounded-pill px-3',
                                        };
                                    @endphp
                                    <span class="{{ $statusClass }}">{!! $statusIcon !!}{{ ucfirst($row->status) }}</span>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('manager.kompensasi.show', $row->kode_kompensasi) }}" 
                                        class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($row->status === 'Belum Terkompensasi')
                                            <a href="{{ route('manager.kompensasi.edit', $row->kode_kompensasi) }}" 
                                            class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('manager.kompensasi.destroy', $row->kode_kompensasi) }}" 
                                                method="POST" style="display:inline;" 
                                                onsubmit="return confirm('Hapus data kompensasi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox-fill fs-1 d-block mb-2"></i>
                                    Tidak ada data kompensasi yang ditemukan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection