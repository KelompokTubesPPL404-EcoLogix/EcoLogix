@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Edit Penyedia Carbon Credit</span>
                    <a href="{{ route('manager.penyedia-carbon-credit.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('manager.penyedia-carbon-credit.update', $penyedia->kode_penyedia) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="kode_penyedia" class="col-md-4 col-form-label text-md-right">Kode Penyedia</label>
                            <div class="col-md-6">
                                <input id="kode_penyedia" type="text" class="form-control" value="{{ $penyedia->kode_penyedia }}" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nama_penyedia" class="col-md-4 col-form-label text-md-right">Nama Penyedia</label>
                            <div class="col-md-6">
                                <input id="nama_penyedia" type="text" class="form-control @error('nama_penyedia') is-invalid @enderror" name="nama_penyedia" value="{{ old('nama_penyedia', $penyedia->nama_penyedia) }}" required>
                                @error('nama_penyedia')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="deskripsi" class="col-md-4 col-form-label text-md-right">Deskripsi</label>
                            <div class="col-md-6">
                                <textarea id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="4" required>{{ old('deskripsi', $penyedia->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="harga_per_ton" class="col-md-4 col-form-label text-md-right">Harga per Ton</label>
                            <div class="col-md-6">
                                <input id="harga_per_ton" type="number" step="0.01" class="form-control @error('harga_per_ton') is-invalid @enderror" name="harga_per_ton" value="{{ old('harga_per_ton', $penyedia->harga_per_ton) }}" required>
                                @error('harga_per_ton')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="mata_uang" class="col-md-4 col-form-label text-md-right">Mata Uang</label>
                            <div class="col-md-6">
                                <select id="mata_uang" class="form-control @error('mata_uang') is-invalid @enderror" name="mata_uang" required>
                                    <option value="IDR" {{ old('mata_uang', $penyedia->mata_uang) == 'IDR' ? 'selected' : '' }}>IDR</option>
                                    <option value="USD" {{ old('mata_uang', $penyedia->mata_uang) == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="EUR" {{ old('mata_uang', $penyedia->mata_uang) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                </select>
                                @error('mata_uang')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="is_active" class="col-md-4 col-form-label text-md-right">Status</label>
                            <div class="col-md-6">
                                <select id="is_active" class="form-control @error('is_active') is-invalid @enderror" name="is_active" required>
                                    <option value="1" {{ old('is_active', $penyedia->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active', $penyedia->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('is_active')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection