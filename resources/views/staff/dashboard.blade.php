<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Staff - Sistem Manajemen Carbon Credit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <div class="container">
            <a class="navbar-brand" href="#">Sistem Manajemen Carbon Credit</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('staff.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('emisicarbon.index') }}">Emisi Carbon</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('faktor-emisi.index') }}">Faktor Emisi</a>
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
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0">Dashboard Staff</h4>
                    </div>
                    <div class="card-body">
                        <h5>Selamat Datang, {{ Auth::user()->nama }}!</h5>
                        <p>Anda login sebagai Staff untuk perusahaan {{ Auth::user()->perusahaan->nama_perusahaan ?? 'Unknown' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Data Emisi Carbon</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total Entri Emisi</span>
                            <span class="badge bg-primary">{{ \App\Models\EmisiCarbon::where('kode_perusahaan', Auth::user()->kode_perusahaan)->count() }}</span>
                        </div>
                        <hr>
                        <div class="d-grid gap-2">
                            <a href="{{ route('emisicarbon.create') }}" class="btn btn-primary">Input Emisi Carbon</a>
                            <a href="{{ route('emisicarbon.index') }}" class="btn btn-secondary">Lihat Data Emisi</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Faktor Emisi</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Jumlah Faktor Emisi</span>
                            <span class="badge bg-info">{{ \App\Models\FaktorEmisi::count() }}</span>
                        </div>
                        <hr>
                        <div class="d-grid">
                            <a href="{{ route('faktor-emisi.index') }}" class="btn btn-info">Lihat Faktor Emisi</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 