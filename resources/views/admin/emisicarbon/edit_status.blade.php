@extends('layouts.admin')

@section('title', 'Review Data Emisi Karbon')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Review Data Emisi Karbon</h1>
        <a href="{{ route('admin.emisicarbon.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Detail Emisi Karbon</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 30%;">Kode Emisi</th>
                                <td>{{ $emisiCarbon->kode_emisi_carbon }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Emisi</th>
                                <td>{{ \Carbon\Carbon::parse($emisiCarbon->tanggal_emisi)->timezone('Asia/Jakarta')->format('d/m/Y') }} WIB</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>{{ $emisiCarbon->kategori_emisi_karbon }}</td>
                            </tr>
                            <tr>
                                <th>Sub Kategori</th>
                                <td>{{ $emisiCarbon->sub_kategori }}</td>
                            </tr>
                            <tr>
                                <th>Nilai Aktivitas</th>
                                <td>{{ $emisiCarbon->nilai_aktivitas }} {{ $emisiCarbon->faktorEmisi->satuan ?? 'Satuan' }}</td>
                            </tr>
                            <tr>
                                <th>Faktor Emisi</th>
                                <td>{{ $emisiCarbon->faktor_emisi }}</td>
                            </tr>
                            <tr>
                                <th>Hasil Konversi</th>
                                <td>{{ number_format($emisiCarbon->nilai_aktivitas * $emisiCarbon->faktor_emisi, 2) }} kg CO₂e</td>
                            </tr>
                            <tr>
                                <th>Staff</th>
                                <td>{{ $emisiCarbon->staff->nama ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>{{ $emisiCarbon->deskripsi }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($emisiCarbon->status == 'approved')
                                    <span class="badge bg-success text-white">Disetujui</span>
                                    @elseif($emisiCarbon->status == 'rejected')
                                    <span class="badge bg-danger text-white">Ditolak</span>
                                    @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Persetujuan</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.emisicarbon.updateStatus', $emisiCarbon->kode_emisi_carbon) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-4">
                            <label class="form-label d-block">Update Status</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status_approved" value="approved" {{ $emisiCarbon->status == 'approved' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_approved">Setujui</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status_rejected" value="rejected" {{ $emisiCarbon->status == 'rejected' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_rejected">Tolak</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status_pending" value="pending" {{ $emisiCarbon->status == 'pending' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_pending">Pending</label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Perbarui Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
