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
      position: relative;
      overflow-y: auto;
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

    .sidebar-collapsed .flex-grow-1 {
      width: 100%;
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
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm px-3">
    <button class="btn btn-outline-success me-2 d-none d-md-block" id="toggleSidebar">
      <i id="sidebarToggleIcon" class="bi bi-list"></i>
    </button>

    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="{{ asset('ECOLOGIX.png') }}" alt="Logo" height="30" class="me-2" />
      <span class="fw-bold text-success"></span>
    </a>

    <div class="ms-auto d-flex align-items-center gap-3">
      <!-- Notifikasi -->
      <div class="dropdown position-relative">
        <button class="btn btn-light position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-bell-fill"></i>
          <span class="notification-badge">2</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="width: 300px;">
          <li class="dropdown-header fw-bold">Notifikasi</li>
          <li>
            <a class="dropdown-item d-flex justify-content-between small text-wrap">
              <span>Pengajuan kompensasi baru masuk.</span>
              <span class="text-muted">5 menit lalu</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item d-flex justify-content-between small text-wrap">
              <span>Data faktor emisi diperbarui.</span>
              <span class="text-muted">1 jam lalu</span>
            </a>
          </li>
        </ul>
      </div>

      <!-- Profile Dropdown -->
      <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle"></i> {{ Auth::user()->nama ?? 'User Name' }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li>
            <form action="{{ route('logout') }}" method="POST" class="dropdown-item p-0">
              @csrf
              <button type="submit" class="dropdown-item text-danger">Logout</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3" id="sidebar">
      @yield('sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
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