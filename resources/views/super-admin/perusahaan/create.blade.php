<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Perusahaan - Sistem Manajemen Carbon Credit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Sistem Manajemen Carbon Credit</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('superadmin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('superadmin.perusahaan.index') }}">Perusahaan</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-light">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Tambah Perusahaan Baru</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('superadmin.perusahaan.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                                <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat_perusahaan" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat_perusahaan" name="alamat_perusahaan" rows="3" required>{{ old('alamat_perusahaan') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="no_telp_perusahaan" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="no_telp_perusahaan" name="no_telp_perusahaan" value="{{ old('no_telp_perusahaan') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email_perusahaan" class="form-label">Email Perusahaan</label>
                                <input type="email" class="form-control" id="email_perusahaan" name="email_perusahaan" value="{{ old('email_perusahaan') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_perusahaan" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password_perusahaan" name="password_perusahaan" required>
                                <div class="form-text">Password akan digunakan untuk akses perusahaan.</div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Simpan Perusahaan</button>
                                <a href="{{ route('superadmin.perusahaan.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 