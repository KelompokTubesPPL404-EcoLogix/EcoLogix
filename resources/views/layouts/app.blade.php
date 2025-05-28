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
      background-color: #f8f9fa;
    }

    /* Carbon Theme Gradients */
    .bg-gradient-success-light {
      background: linear-gradient(120deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.2) 100%);
    }
    
    .bg-carbon-theme {
      background: linear-gradient(135deg, #104117 0%, #007f2d 100%);
    }
    
    .sidebar {
      background: linear-gradient(180deg, #104117 0%, #007f2d 100%);
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
      padding-top: 70px; /* Space for navbar */
    }
    
    /* Carbon theme overlay pattern */
    .sidebar::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      opacity: 0.15;
      z-index: 0;
    }

    .sidebar .nav-link {
      color: white;
      font-weight: 500;
      position: relative;
      z-index: 1;
      transition: all 0.3s ease;
      margin-bottom: 5px;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: rgba(255, 255, 255, 0.15);
      border-radius: 8px;
      transform: translateX(5px);
    }

    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .chart-container {
      background: white;
      padding: 1rem;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .sidebar-collapsed #sidebar {
      margin-left: -250px;
    }

    .sidebar-collapsed .main-content {
      margin-left: 0 !important;
    }
    
    .main-content {
      margin-left: 250px;
      transition: margin-left 0.3s ease;
    }
    
    /* Navbar adjustments */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1001;
      background-color: white !important;
    }
    
    body {
      padding-top: 70px; /* Space for fixed navbar */
    }

    .notification-badge {
      position: absolute;
      top: 0;
      right: 0;
      font-size: 0.7rem;
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 2px 6px;
    }
  </style>
  @yield('styles')
</head>
<body>
  <!-- Enhanced Navbar with EcoLogix Theme -->
  <nav class="navbar navbar-expand-lg navbar-dark px-3" style="background: linear-gradient(135deg, #104117 0%, #007f2d 100%); box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
    <button class="btn btn-outline-light me-2 d-none d-md-block" id="toggleSidebar">
      <i id="sidebarToggleIcon" class="bi bi-list"></i>
    </button>

    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="{{ asset('ECOLOGIXputihround.png') }}" alt="EcoLogix" height="36" class="me-2" />
      <span class="fw-bold text-white d-none d-md-inline-block"></span>
    </a>
    
    <div class="navbar-text text-white ms-4 d-none d-lg-flex align-items-center">
      <div class="vr bg-light opacity-25 me-3" style="height: 24px;"></div>
      <span class="fs-6">{{ Auth::user()->perusahaan->nama_perusahaan ?? 'EcoLogix Platform' }}</span>
    </div>

    <div class="ms-auto d-flex align-items-center gap-3">
      <!-- Status Badge -->
      <div class="d-none d-md-block">
        <span class="badge bg-light text-success d-flex align-items-center gap-1">
          <i class="bi bi-check-circle-fill"></i>
          <span class="small">Online</span>
        </span>
      </div>
      
      <!-- Notifikasi -->
      <div class="dropdown position-relative">
        <button class="btn btn-dark position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: rgba(0,0,0,0.2); border: none;">
          <i class="bi bi-bell-fill text-white"></i>
          <span class="notification-badge">2</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown" style="width: 320px; border-radius: 8px; border: none; margin-top: 10px;">
          <li class="dropdown-header fw-bold d-flex justify-content-between align-items-center p-3">
            <span>Notifikasi</span>
            <span class="badge bg-success rounded-pill">2 Baru</span>
          </li>
          <li><hr class="dropdown-divider my-0"></li>
          <li>
            <a class="dropdown-item p-3 d-flex align-items-start gap-2" href="#">
              <div class="flex-shrink-0">
                <div class="rounded-circle bg-success bg-opacity-10 p-2">
                  <i class="bi bi-file-earmark-plus text-success"></i>
                </div>
              </div>
              <div>
                <p class="mb-0 fw-medium">Pengajuan emisi baru</p>
                <p class="text-muted small mb-0">Data emisi dari Departemen Produksi telah diajukan</p>
                <p class="text-muted small mb-0">5 menit lalu</p>
              </div>
            </a>
          </li>
          <li><hr class="dropdown-divider my-0"></li>
          <li>
            <a class="dropdown-item p-3 d-flex align-items-start gap-2" href="#">
              <div class="flex-shrink-0">
                <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                  <i class="bi bi-arrow-repeat text-primary"></i>
                </div>
              </div>
              <div>
                <p class="mb-0 fw-medium">Faktor emisi diperbarui</p>
                <p class="text-muted small mb-0">Data faktor emisi transportasi telah diperbarui</p>
                <p class="text-muted small mb-0">1 jam lalu</p>
              </div>
            </a>
          </li>
          <li><hr class="dropdown-divider my-0"></li>
          <li>
            <a class="dropdown-item text-center p-2" href="#">
              <span class="text-success fw-medium small">Lihat Semua Notifikasi</span>
            </a>
          </li>
        </ul>
      </div>

      <!-- Profile Dropdown -->
      <div class="dropdown">
        <button class="btn dropdown-toggle d-flex align-items-center gap-2" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: rgba(0,0,0,0.2); border: none;">
          <div class="rounded-circle bg-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
            <i class="bi bi-person-fill text-success"></i>
          </div>
          <span class="text-white d-none d-md-inline">{{ Auth::user()->nama ?? 'User' }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuButton" style="border-radius: 8px; border: none; margin-top: 10px;">
          <li class="dropdown-header fw-bold text-center pb-0">
            <div class="d-flex flex-column align-items-center">
              <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                <i class="bi bi-person-fill fs-4 text-success"></i>
              </div>
              <p class="mb-0 fw-bold">{{ Auth::user()->nama ?? 'User' }}</p>
              <p class="text-muted small mb-2">{{ Auth::user()->email ?? 'user@example.com' }}</p>
              <span class="badge bg-success mb-2">{{ ucfirst(str_replace('_', ' ', Auth::user()->role ?? 'user')) }}</span>
            </div>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="#"><i class="bi bi-person-gear me-2 text-success"></i> Profil Saya</a></li>
          <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2 text-success"></i> Pengaturan</a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form action="{{ route('logout') }}" method="POST">
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