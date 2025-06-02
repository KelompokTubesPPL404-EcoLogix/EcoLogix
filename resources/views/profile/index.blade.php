@extends('layouts.app')

@section('title', 'Profil Saya')

@section('sidebar')
  @include('layouts.' . Auth::user()->role)
@endsection

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-carbon-theme text-white p-4">
            <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i>Profil Pengguna</h4>
          </div>
          <div class="card-body p-4">
            @if (session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            @if ($errors->any())
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <form action="{{ route(Auth::user()->role . '.profile.update') }}" method="POST">
              @csrf
              @method('PUT')

              <div class="mb-3">
                <label for="nama" class="form-label fw-bold">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
              </div>

              <div class="mb-3">
                <label for="no_hp" class="form-label fw-bold">Nomor Telepon</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required>
              </div>

              <div class="mb-3">
                <label for="alamat" class="form-label fw-bold">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
              </div>

              <hr class="my-4">

              <h5 class="mb-3">Ubah Kata Sandi (Opsional)</h5>
              <div class="mb-3">
                <label for="password" class="form-label fw-bold">Kata Sandi Baru</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah kata sandi.</small>
              </div>

              <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Kata Sandi Baru</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-save me-2"></i>Simpan Perubahan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection