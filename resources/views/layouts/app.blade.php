<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') - EcoLogix</title>
  
  <!-- Ensure charts render properly -->
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #ffffff; /* Latar belakang putih */
    color: #000000; /* Warna teks default hitam */
    padding-top: 56px; /* Sesuaikan dengan tinggi navbar Anda, misal 56px atau 60px */
  }

  /* Gradient Hijau Tema (digunakan di tempat lain, bukan navbar utama) */
  .bg-gradient-success-light {
    background: linear-gradient(120deg, rgba(1, 157, 32, 0.08) 0%, rgba(1, 157, 32, 0.15) 100%);
  }
  
  .bg-carbon-theme { 
    background: linear-gradient(135deg, #019D20 0%, #007f2d 100%);
  }
  
  /* Sidebar Styling */
  .sidebar {
    background: linear-gradient(180deg, #019D20 0%, #008f1c 100%);
    min-height: 100vh;
    color: white;
    transition: all 0.3s ease;
    width: 250px;
    box-shadow: 2px 0px 10px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    overflow-y: auto;
    padding-top: 56px; /* Sesuaikan dengan tinggi navbar Anda */
  }
  
  .sidebar::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.1;
    z-index: 0;
  }

  .sidebar .nav-link {
    color: white;
    font-weight: 500;
    position: relative;
    z-index: 1;
    transition: all 0.3s ease;
    margin-bottom: 5px;
    padding: 0.75rem 1rem; /* Padding link sidebar */
  }

  .sidebar .nav-link:hover,
  .sidebar .nav-link.active {
    background-color: rgba(255, 255, 255, 0.15);
    border-radius: 8px;
    transform: translateX(5px);
  }

  /* Card Styling */
  .card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.07); 
    background-color: #ffffff; 
  }

  .chart-container {
    background: white;
    padding: 1rem;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.07);
  }

  /* Sidebar Collapsed State */
  .sidebar-collapsed #sidebar {
    margin-left: -250px;
  }

  .sidebar-collapsed .main-content {
    margin-left: 0 !important;
  }
  
  .main-content {
    margin-left: 250px;
    transition: margin-left 0.3s ease;
    padding: 1.5rem; /* Padding konten utama */
  }
  
  /* Navbar Base (sebelum kustomisasi navbar putih) */
  .navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1001;
    box-shadow: 0 1px 2px rgba(0,0,0,0.07); /* Shadow tipis untuk navbar */
    min-height: 56px; /* Tinggi navbar minimum */
    padding-top: 0.5rem; /* Padding atas navbar */
    padding-bottom: 0.5rem; /* Padding bawah navbar */
  }
  
  /* Styling untuk Navbar Putih Kustom */
  .navbar-custom-white {
    background-color: #ffffff !important;
    border-bottom: 1px solid #dee2e6; /* Garis bawah tipis untuk navbar putih */
  }

  .navbar-custom-white .navbar-brand img {
    /* Style khusus untuk logo jika diperlukan */
  }
  .navbar-custom-white .navbar-brand-text { /* Jika ada teks di sebelah logo */
    color: #000000 !important;
  }

  .navbar-custom-white .company-name {
    color: #000000 !important; 
    font-weight: 400; 
    font-size: 0.875rem; 
    line-height: 1.2; /* Sesuaikan line-height agar align dengan logo */
  }

  .navbar-custom-white .active-status {
    font-size: 0.8rem; 
    color: #000000; 
    display: flex;
    align-items: center;
  }

  .navbar-custom-white .active-status-dot {
    color: #28a745; 
    font-size: 0.45rem; 
    margin-right: 0.25rem; 
  }
  .navbar-custom-white .active-status-text {
   font-weight: 400; 
   font-size: 0.8rem;
  }

  /* Tombol Ikon pada Navbar (Bell, Profile) */
  .navbar-custom-white .btn-icon {
    background-color: transparent !important;
    border: none !important;
    color: #000000 !important; 
    padding: 0.25rem 0.35rem; /* Padding tombol ikon disesuaikan */
    line-height: 1; 
    display: flex; 
    align-items: center;
  }
  .navbar-custom-white .btn-icon:hover,
  .navbar-custom-white .btn-icon:focus {
    color: #495057 !important; /* Warna ikon saat hover sedikit lebih gelap */
    box-shadow: none !important;
  }

  .navbar-custom-white .btn-icon .bi-bell-fill { 
      font-size: 1.05rem; /* Ukuran ikon bell disesuaikan */
  }
  
  /* Tombol Toggle Sidebar */
  .navbar-custom-white #sidebarToggleIcon {
    color: #000000 !important; 
    font-size: 1.25rem; /* Ukuran ikon toggle sidebar disesuaikan */
  }
  
  /* Foto Profil di Navbar */
  .navbar-custom-white .profile-icon-navbar {
  font-size: 1.2rem; /* Sesuaikan ukuran ikon pengguna di navbar */
  color: #000000 !important; /* Pastikan ikon berwarna hitam */
  vertical-align: middle;
}

.navbar-user-name {
  color: #000000 !important;
  font-size: 0.875rem;
  font-weight: 400;
  margin-left: 0.4rem;
  vertical-align: middle; /* Agar align dengan ikon */
}

/* Pastikan tombol profil (yang berisi ikon dan nama) align dengan baik */
#dropdownMenuButton.btn.btn-icon.dropdown-toggle {
    padding-right: 0.35rem; /* Sesuaikan padding jika nama pengguna ditambahkan */
}
  .navbar-user-name {
    color: #000000 !important;
    font-size: 0.875rem; 
    font-weight: 400; 
    margin-left: 0.4rem; 
  }
  
  /* Sembunyikan panah dropdown default Bootstrap untuk tombol profil gambar */
  #dropdownMenuButton.dropdown-toggle::after {
      display: none !important;
  }

  /* Badge Notifikasi */
  .notification-badge {
    position: absolute;
    top: 0px;      
    right: 0px;     
    font-size: 0.6rem; 
    background-color: red; 
    color: white;
    border-radius: 50%;
    padding: 1px 4px;  
    line-height: 1;    
    border: 1px solid white; 
    min-width: 14px; /* Agar badge tidak terlalu kecil jika angkanya 1 digit */
    text-align: center;
  }
  
  /* Dropdown Menu Styling */
  .dropdown-menu {
      box-shadow: 0 .15rem .45rem rgba(0,0,0,.1); 
      border-radius: .25rem; 
      border: 1px solid #e0e0e0; 
      margin-top: 0.65rem !important; /* Jarak dari tombol ke menu dropdown */
      padding-top: 0.25rem;
      padding-bottom: 0.25rem;
  }
  .dropdown-menu .dropdown-item {
      color: #212529; 
      padding: .35rem 1rem; 
      font-size: 0.9rem;
  }
  .dropdown-menu .dropdown-item:hover,
  .dropdown-menu .dropdown-item:focus {
      background-color: #f1f3f5; 
      color: #1e2125;
  }
  .dropdown-menu .dropdown-header { 
      color: #6c757d !important; 
      padding: .35rem 1rem;
      font-size: 0.8rem;
  }
  .dropdown-menu .text-muted {
      color: #6c757d !important;
  }

  /* Foto profil di dalam menu dropdown */
  .profile-picture-dropdown {
    width: 48px; 
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 0.5rem;
    border: 2px solid #e9ecef;
  }

  /* Warna Teks dan Ikon Kustom Hijau (digunakan di dropdown dan tempat lain) */
  .text-custom-green { 
    color: #019D20 !important;
  }
  .dropdown-menu .bi-person-gear,
  .dropdown-menu .bi-gear {
      color: #019D20 !important; 
  }
  .dropdown-menu .bi-box-arrow-right {
      color: #dc3545 !important; 
  }
  .dropdown-menu .text-danger { 
      color: #dc3545 !important; 
  }
  .dropdown-menu .text-success { /* Untuk badge "Baru" dll. */
      color: #019D20 !important;
  }
  .badge.bg-success { /* Untuk badge di dropdown notifikasi */
    background-color: #019D20 !important;
    color: white !important;
  }

  /* Tombol Sukses Kustom (digunakan di halaman konten) */
  .btn-success {
    background-color: #019D20;
    border-color: #019D20;
    color: white; 
  }
  .btn-success:hover {
    background-color: #007f2d; 
    border-color: #007f2d;
    color: white;
  }
  .btn-outline-success {
    color: #019D20;
    border-color: #019D20;
  }
  .btn-outline-success:hover {
    background-color: #019D20;
    color: white;
  }

  /* Progress Bar Sukses Kustom */
  .progress-bar.bg-success {
    background-color: #019D20 !important;
  }

</style>
  @yield('styles')
</head>
<body>
  <!-- Enhanced Navbar with EcoLogix Theme -->
<nav class="navbar navbar-expand-lg px-3 navbar-custom-white">
    <div class="d-flex align-items-center">
        <button class="btn me-2 d-none d-md-block" id="toggleSidebar" style="border:none; padding-left: 0; line-height: 1;">
            <i id="sidebarToggleIcon" class="bi bi-list"></i>
        </button>
        <a class="navbar-brand d-flex align-items-center me-3" href="{{ route('staff.dashboard') }}"> <img src="{{ asset('ECOLOGIX.png') }}" alt="EcoLogix" height="28" class="me-2">
        </a>
        <span class="company-name">{{ Auth::user()->perusahaan->nama_perusahaan ?? 'Nama Perusahaan' }}</span>
    </div>

    <div class="ms-auto d-flex align-items-center" style="gap: 0.9rem;"> <span class="d-flex align-items-center active-status">
            <i class="bi bi-circle-fill me-1 active-status-dot"></i>
            <span class="active-status-text">Aktif</span>
        </span>

        <div class="dropdown position-relative">
            <button class="btn btn-icon" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell-fill"></i>
                <span class="notification-badge" @if(!isset($new_notifications) || $new_notifications == 0) style="display: none;" @endif>{{ $new_notifications ?? 0 }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown" style="width: 320px; margin-top: 10px !important;">
                <li class="dropdown-header fw-bold d-flex justify-content-between align-items-center p-3">
                    <span>Notifikasi</span>
                    <span class="badge bg-success rounded-pill">2 Baru</span> </li>
                <li><hr class="dropdown-divider my-0"></li>
                <li>
                    <a class="dropdown-item p-3 d-flex align-items-start gap-2" href="#">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle p-2" style="background-color: rgba(1, 157, 32, 0.1);">
                                <i class="bi bi-file-earmark-plus" style="color: #019D20;"></i>
                            </div>
                        </div>
                        <div>
                            <p class="mb-0 fw-medium">Pengajuan emisi baru</p>
                            <p class="text-muted small mb-0">Data emisi telah diajukan</p>
                            <p class="text-muted small mb-0">5 menit lalu</p>
                        </div>
                    </a>
                </li>
                <li><hr class="dropdown-divider my-0"></li>
                <li>
                    <a class="dropdown-item text-center p-2" href="#">
                        <span class="text-custom-green fw-medium small">Lihat Semua Notifikasi</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="dropdown">
            <button class="btn btn-icon dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="border:none; background:none; line-height: 1;">
                <i class="bi bi-person-fill profile-icon-navbar"></i> <span class="ms-2 d-none d-md-inline navbar-user-name">{{ Auth::user()->nama ?? 'User' }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuButton" style="margin-top: 10px !important;">
                <li class="dropdown-header fw-bold text-center pb-0">
                    <div class="d-flex flex-column align-items-center">
                        {{-- Ikon pengguna di header dropdown --}}
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; background-color: #f0f0f0;">
                          <i class="bi bi-person-fill fs-4" style="color: #000000;"></i>
                        </div>
                        <p class="mb-0 fw-bold" style="color: #000000;">{{ Auth::user()->nama ?? 'User' }}</p>
                        <p class="text-muted small mb-2">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                        <span class="badge bg-success mb-2">{{ ucfirst(str_replace('_', ' ', Auth::user()->role ?? 'user')) }}</span>
                    </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-person-gear me-2 text-custom-green"></i> Profil Saya</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2 text-custom-green"></i> Pengaturan</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    @yield('sidebar')
  </div>

  <!-- Main Content -->
  <div class="main-content p-4">
      @if(session('success'))
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
      @endif

      @if(session('error'))
      <div class="alert alert-danger">
          {{ session('error') }}
      </div>
      @endif

      @yield('content')
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  @yield('scripts')
  <script>
    document.getElementById('toggleSidebar').addEventListener('click', function () {
      document.body.classList.toggle('sidebar-collapsed');
    });
  </script>
</body>
</html>