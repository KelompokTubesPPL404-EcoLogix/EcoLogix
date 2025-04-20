@extends('layouts.app')

@section('title', 'Edit Perusahaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Perusahaan</h3>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('perusahaan.update', $perusahaan->kode_perusahaan) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" value="{{ $perusahaan->nama_perusahaan }}" required>
            </div>
            
            <div class="mb-3">
                <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan</label>
                <textarea class="form-control" id="alamat_perusahaan" name="alamat_perusahaan" rows="3" required>{{ $perusahaan->alamat_perusahaan }}</textarea>
            </div>
            
            <div class="mb-3">
                <label for="no_telp_perusahaan" class="form-label">No. Telepon</label>
                <input type="text" class="form-control" id="no_telp_perusahaan" name="no_telp_perusahaan" value="{{ $perusahaan->no_telp_perusahaan }}" required>
            </div>
            
            <div class="mb-3">
                <label for="email_perusahaan" class="form-label">Email</label>
                <input type="email" class="form-control" id="email_perusahaan" name="email_perusahaan" value="{{ $perusahaan->email_perusahaan }}" required>
            </div>
            
            <div class="mb-3">
                <label for="password_perusahaan" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" class="form-control" id="password_perusahaan" name="password_perusahaan" minlength="8">
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('perusahaan.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection