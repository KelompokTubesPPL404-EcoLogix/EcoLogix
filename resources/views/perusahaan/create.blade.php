@extends('layouts.app')

@section('title', 'Tambah Perusahaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Perusahaan Baru</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('perusahaan.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                <input type="text" class="form-control @error('nama_perusahaan') is-invalid @enderror" id="nama_perusahaan" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}" required>
                @error('nama_perusahaan')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan</label>
                <textarea class="form-control @error('alamat_perusahaan') is-invalid @enderror" id="alamat_perusahaan" name="alamat_perusahaan" rows="3" required>{{ old('alamat_perusahaan') }}</textarea>
                @error('alamat_perusahaan')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="no_telp_perusahaan" class="form-label">No. Telepon</label>
                <input type="text" class="form-control @error('no_telp_perusahaan') is-invalid @enderror" id="no_telp_perusahaan" name="no_telp_perusahaan" value="{{ old('no_telp_perusahaan') }}" required>
                @error('no_telp_perusahaan')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="email_perusahaan" class="form-label">Email Perusahaan</label>
                <input type="email" class="form-control @error('email_perusahaan') is-invalid @enderror" id="email_perusahaan" name="email_perusahaan" value="{{ old('email_perusahaan') }}" required>
                @error('email_perusahaan')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password_perusahaan" class="form-label">Password</label>
                <input type="password" class="form-control @error('password_perusahaan') is-invalid @enderror" id="password_perusahaan" name="password_perusahaan" required>
                @error('password_perusahaan')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="kode_super_admin" class="form-label">Kode Super Admin</label>
                <select class="form-select @error('kode_super_admin') is-invalid @enderror" id="kode_super_admin" name="kode_super_admin" required>
                    <option value="">Pilih Super Admin</option>
                    @foreach($superAdmins as $admin)
                    <option value="{{ $admin->kode_super_admin }}" {{ old('kode_super_admin') == $admin->kode_super_admin ? 'selected' : '' }}>{{ $admin->nama_super_admin }}</option>
                    @endforeach
                </select>
                @error('kode_super_admin')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('perusahaan.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection