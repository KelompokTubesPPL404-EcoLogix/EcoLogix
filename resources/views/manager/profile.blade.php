@extends('layouts.manager')

@section('title', 'Profil Manager')

@section('styles')
<style>
    .form-label {
        font-size: 1rem;
        font-weight: 600;
        color: #495057;
    }

    .btn-success {
        background: linear-gradient(90deg, #28a745, #218838);
        border: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-success:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.5);
    }

    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        transition: all 0.2s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }

    .card {
        border-radius: 15px;
        overflow: hidden;
    }

    .bg-gradient-success {
        background: linear-gradient(90deg, #28a745, #218838);
    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white">
                    <h4 class="mb-0 text-center fw-semibold">Profil Manager</h4>
                </div>
                <div class="card-body p-4">
                    <!-- Success alert placeholder -->
                    <div id="success-alert" class="alert alert-success d-none"></div>

                    <form id="profile-form">
                        <div class="mb-3 text-center">
                            <img id="profile-photo" src="{{ asset('images/default-profile.png') }}" alt="Foto Profil" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Profil</label>
                            <input type="file" class="form-control" id="foto" name="foto">
                        </div>

                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode Manager</label>
                            <input type="text" class="form-control" id="kode" value="MNGR001" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="John Doe">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="john@example.com">
                        </div>

                        <div class="mb-3">
                            <label for="no_telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="08123456789">
                        </div>

                        <hr>
                        <h6>Ubah Password</h6>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-success px-5">Simpan Perubahan</button>
                            <a href="{{ route('manager.dashboard') }}" class="btn btn-outline-secondary px-5">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('foto').addEventListener('change', function (e) {
        const [file] = e.target.files;
        if (file) {
            document.getElementById('profile-photo').src = URL.createObjectURL(file);
        }
    });

    document.getElementById('profile-form').addEventListener('submit', function (e) {
        e.preventDefault();
        document.getElementById('success-alert').textContent = 'Perubahan profil berhasil disimpan.';
        document.getElementById('success-alert').classList.remove('d-none');
    });
</script>
@endsection