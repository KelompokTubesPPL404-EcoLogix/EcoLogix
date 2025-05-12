<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Manager - Sistem Manajemen Carbon Credit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="#">Sistem Manajemen Carbon Credit</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('manager.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('manager.admin.index') }}">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('manager.staff.index') }}">Staff</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('manager.kompensasi.index') }}">Kompensasi Emisi</a>
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
            <div class="col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Dashboard Manager</h4>
                    </div>
                    <div class="card-body">
                        <h5>Selamat Datang, {{ Auth::user()->nama }}!</h5>
                        <p>Anda login sebagai Manager untuk perusahaan {{ Auth::user()->perusahaan->nama_perusahaan ?? 'Unknown' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Ringkasan Tim</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total Admin</span>
                            <span class="badge bg-primary">{{ \App\Models\User::where('role', 'admin')->where('kode_perusahaan', Auth::user()->kode_perusahaan)->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Total Staff</span>
                            <span class="badge bg-info">{{ \App\Models\User::where('role', 'staff')->where('kode_perusahaan', Auth::user()->kode_perusahaan)->count() }}</span>
                        </div>
                        <hr>
                        <div class="d-grid gap-2">
                            <a href="{{ route('manager.admin.create') }}" class="btn btn-primary">Tambah Admin</a>
                            <a href="{{ route('manager.staff.create') }}" class="btn btn-info">Tambah Staff</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <p>Di sini akan menampilkan aktivitas terbaru dalam sistem.</p>
                        <!-- Tampilkan aktivitas terbaru di sini jika ada fitur untuk itu -->
                        <div class="text-center py-3">
                            <p class="text-muted">Belum ada aktivitas terbaru</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 