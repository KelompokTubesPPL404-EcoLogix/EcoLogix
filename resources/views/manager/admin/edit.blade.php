@extends('layouts.manager')

@section('title', 'Edit Admin')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@yield('title'): {{ $admin->nama }}</h1>
        <a href="{{ route('manager.admin.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar Admin
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Terdapat masalah dengan input Anda.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Admin</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('manager.admin.update', $admin->kode_user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group row">
                    <label for="kode_user" class="col-sm-3 col-form-label">Kode Admin</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="kode_user" name="kode_user" value="{{ $admin->kode_user }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nama" class="col-sm-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $admin->nama) }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-3 col-form-label">Alamat Email <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $admin->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="no_hp" class="col-sm-3 col-form-label">Nomor HP <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp', $admin->no_hp) }}" required>
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-sm-3 col-form-label">Password Baru</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password_confirmation" class="col-sm-3 col-form-label">Konfirmasi Password Baru</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('manager.admin.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection