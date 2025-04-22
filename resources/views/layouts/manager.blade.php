<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Dashboard') - EcoLogix</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }

    .sidebar {
      background-color: #007f2d;
      min-height: 100vh;
      color: white;
      transition: all 0.3s ease;
      width: 250px;
    }

    .sidebar .nav-link {
      color: white;
      font-weight: 500;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: #006622;
      border-radius: 8px;
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

    <a class="navbar-brand d-flex align-items-center" href="{{ route('manager.dashboard') }}">
      <img src="{{ asset('img/ECOLOGIX.png') }}" alt="Logo" height="30" class="me-2" />
      <span class="fw-bold text-success">EcoLogix</span>
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
          <i class="bi bi-person-circle"></i> Refka Maulana Sidik
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
          <li><a class="dropdown-item" href="{{ route('manager.profile') }}">Profile</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3" id="sidebar">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a href="{{ route('manager.dashboard') }}" class="nav-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door me-2"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('manager.faktor-emisi.*') ? 'active' : '' }}" href="{{ route('manager.faktor-emisi.index') }}">
            <i class="bi bi-calculator me-2"></i> Faktor Emisi
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('manager.kompensasi.*') ? 'active' : '' }}" href="{{ route('manager.kompensasi.index') }}">
            <i class="bi bi-arrow-left-right me-2"></i> Kompensasi Emisi
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('manager.carbon_credit.*') ? 'active' : '' }}" href="{{ route('manager.carbon_credit.index') }}">
            <i class="bi bi-currency-exchange me-2"></i> Lihat pembelian
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('manager.penyedia.*') ? 'active' : '' }}" href="{{ route('manager.penyedia.index') }}">
            <i class="bi bi-building me-2"></i> Kelola penyedia
          </a>
        </li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
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