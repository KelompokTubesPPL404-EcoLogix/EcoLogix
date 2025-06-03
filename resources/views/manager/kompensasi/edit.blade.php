@extends('layouts.manager')

@section('title', 'Edit Kompensasi')

@section('content')
<div class="container py-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Edit Kompensasi</h5>
          <a href="{{ route('manager.kompensasi.index') }}" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
          </a>
        </div>
        <div class="card-body">
          <div class="row">
            <!-- Informasi Kompensasi -->
            <div class="col-md-6">
              <h6 class="border-bottom pb-2">Informasi Kompensasi</h6>
              <table class="table table-borderless">
                <tr>
                  <td width="200">Kode Kompensasi</td>
                  <td>: {{ $kompensasi->kode_kompensasi }}</td>
                </tr>
                <tr>
                  <td>Kode Manager</td>
                  <td>: {{ $kompensasi->kode_manager ?? '-' }}</td>
                </tr>
                <tr>
                  <td>Tanggal Kompensasi</td>
                  <td>: {{ \Carbon\Carbon::parse($kompensasi->tanggal_kompensasi)->timezone('Asia/Jakarta')->format('d/m/Y') }} WIB</td>
                </tr>
                <tr>
                  <td>Status</td>
                  <td>:
                    @if($kompensasi->status_kompensasi == 'pending')
                      <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($kompensasi->status_kompensasi == 'approved')
                      <span class="badge bg-success">Approved</span>
                    @elseif($kompensasi->status_kompensasi == 'rejected')
                      <span class="badge bg-danger">Rejected</span>
                    @else
                      <span class="badge bg-secondary">{{ $kompensasi->status_kompensasi }}</span>
                    @endif
                  </td>
                </tr>
                <tr>
                  <td>Created At</td>
                  <td>: {{ $kompensasi->created_at ? \Carbon\Carbon::parse($kompensasi->created_at)->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') . ' WIB' : '-' }}</td>
                </tr>
                <tr>
                  <td>Updated At</td>
                  <td>: {{ $kompensasi->updated_at ? \Carbon\Carbon::parse($kompensasi->updated_at)->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') . ' WIB' : '-' }}</td>
                </tr>
              </table>
            </div>

            <!-- Informasi Emisi & Form Edit -->
            <div class="col-md-6">
              <h6 class="border-bottom pb-2">Informasi Emisi</h6>
              <table class="table table-borderless">
                <tr>
                  <td width="200">Kode Emisi</td>
                  <td>: {{ $kompensasi->kode_emisi_carbon }}</td>
                </tr>
                <tr>
                  <td>Kategori</td>
                  <td>: {{ $kompensasi->kategori_emisi ?? '-' }}</td>
                </tr>
                <tr>
                  <td>Sub Kategori</td>
                  <td>: {{ $kompensasi->sub_kategori ?? '-' }}</td>
                </tr>
                <tr>
                  <td>Kadar Emisi</td>
                  <td>: {{ number_format($kompensasi->kadar_emisi_ton, 2) }} ton CO₂e</td>
                </tr>
              </table>
              <hr>
              <h6 class="border-bottom pb-2">Edit Kompensasi</h6>
              <form action="{{ route('manager.kompensasi.update', $kompensasi->kode_kompensasi) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                  <label for="jumlahKompensasi" class="form-label">Jumlah Kompensasi (ton CO₂e)</label>
                  <input type="number" class="form-control @error('jumlah_kompensasi') is-invalid @enderror" 
                         id="jumlahKompensasi" name="jumlah_kompensasi" step="0.01" min="0.01" 
                         value="{{ old('jumlah_kompensasi', $kompensasi->jumlah_ton) }}" required />
                  @error('jumlah_kompensasi')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <button type="submit" class="btn btn-primary">Update Kompensasi</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection