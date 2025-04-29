@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Faktor Emisi</h5>
                    <a href="{{ route('faktor-emisi.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('faktor-emisi.update', $faktorEmisi->kode_faktor) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="kode_faktor" class="form-label">Kode Faktor</label>
                            <input type="text" class="form-control" id="kode_faktor" value="{{ $faktorEmisi->kode_faktor }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="kategori_emisi_karbon" class="form-label">Kategori Emisi Karbon</label>
                            <input type="text" class="form-control @error('kategori_emisi_karbon') is-invalid @enderror" id="kategori_emisi_karbon" name="kategori_emisi_karbon" value="{{ old('kategori_emisi_karbon', $faktorEmisi->kategori_emisi_karbon) }}" required>
                            @error('kategori_emisi_karbon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="sub_kategori" class="form-label">Sub Kategori</label>
                            <input type="text" class="form-control @error('sub_kategori') is-invalid @enderror" id="sub_kategori" name="sub_kategori" value="{{ old('sub_kategori', $faktorEmisi->sub_kategori) }}" required>
                            @error('sub_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="nilai_faktor" class="form-label">Nilai Faktor</label>
                            <input type="number" step="0.01" class="form-control @error('nilai_faktor') is-invalid @enderror" id="nilai_faktor" name="nilai_faktor" value="{{ old('nilai_faktor', $faktorEmisi->nilai_faktor) }}" required>
                            @error('nilai_faktor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan" value="{{ old('satuan', $faktorEmisi->satuan) }}" required>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection