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
    /* Global styles */
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }
    
    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background-color: #dc3545;
      color: white;
      border-radius: 50%;
      width: 18px;
      height: 18px;
      font-size: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    /* Notifikasi yang belum dibaca */
    .dropdown-item.fw-bold {
      background-color: rgba(40, 167, 69, 0.05);
      border-left: 3px solid #28a745;
    }
    
    /* Carbon Theme Gradients */
    .bg-gradient-success-light {
      background: linear-gradient(120deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.2) 100%);
    }
    
    .bg-carbon-theme {
      background: linear-gradient(135deg, #104117 0%, #007f2d 100%);
    }
    
    .sidebar {
        background: linear-gradient( #007f2d 100%);
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
        padding-top: 70px;
        }

        .sidebar.collapsed {
            margin-left: -250px;
        }

        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            width: calc(100% - 250px);
        }

        .main-content.expanded {
            margin-left: 0;
            width: 100%;
        }
        .sidebar-toggle {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: rgba(255, 254, 254, 0.1);
        border: none;
        transition: all 0.3s ease;
        }

    .sidebar-toggle:hover {
        background: rgba(0, 0, 0, 0.2);
        transform: scale(1.05);
    }

    .sidebar-toggle i {
        font-size: 24px !important;
        color: #104117;
    }

        /* Add these styles in your <style> section */
    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
    }

    .sidebar .nav-link:hover {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar .nav-link.active {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.2);
    }

    .sidebar .sidebar-heading {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .sidebar .badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }

    .sidebar-section {
        margin-bottom: 1.5rem;
    }

    .sidebar-section .nav-item {
        margin-bottom: 0.25rem;
    }

    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
        margin-right: 0.5rem;
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
  </style>
  @yield('styles')
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
      
      <div class="sidebar-menu flex-grow-1 p-3 overflow-auto">
        <!-- Overview Section -->
        <div class="sidebar-section mb-4">
          <div class="sidebar-heading small text-uppercase text-white-50 px-2 py-2 mb-2">
            <i class="bi bi-speedometer2 me-2"></i>Overview
          </div>
          <ul class="nav flex-column gap-1">
            <li class="nav-item">
              <a href="{{ route('superadmin.dashboard') }}" class="nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-3"></i>
                <span>Dashboard</span>
              </a>
            </li>
          </ul>
        </div>

        <!-- Management Section -->
        <div class="sidebar-section mb-4">
          <div class="sidebar-heading small text-uppercase text-white-50 px-2 py-2 mb-2">
            <i class="bi bi-gear me-2"></i>Manajemen
          </div>
          <ul class="nav flex-column gap-1">
            <li class="nav-item">
              <a class="nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('superadmin.perusahaan*') ? 'active' : '' }}" href="{{ route('superadmin.perusahaan.index') }}">
                <i class="bi bi-building me-3"></i>
                <span>Perusahaan</span>
                <span class="ms-auto badge bg-success rounded-pill">{{ \App\Models\Perusahaan::count() }}</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('superadmin.manager*') ? 'active' : '' }}" href="{{ route('superadmin.manager.index') }}">
                <i class="bi bi-person-badge me-3"></i>
                <span>Manajer</span>
                <span class="ms-auto badge bg-info rounded-pill">{{ \App\Models\User::where('role', 'manager')->count() }}</span>
              </a>
            </li>
            
          </ul>
        </div>

        <!-- Analytics Section -->
        <div class="sidebar-section mb-4">
          <div class="sidebar-heading small text-uppercase text-white-50 px-2 py-2 mb-2">
            <i class="bi bi-graph-up me-2"></i>Analisis
          </div>
          <ul class="nav flex-column gap-1">
            <li class="nav-item">
              <a class="nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('superadmin.leaderboard') ? 'active' : '' }}" href="{{ route('superadmin.leaderboard') }}">
                <i class="bi bi-trophy me-3"></i>
                <span>Leaderboard Perusahaan</span>
              </a>
            </li>
            
          </ul>
        </div>

        
      </div>

      
      </div>
    </div>
  <!-- Enhanced Navbar with EcoLogix Theme -->
  <nav class="navbar navbar-expand-lg navbar-dark px-3" style="background: linear-gradient( #ffffff 100%); box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
  <button class="btn btn-outline-black me-2 d-none d-md-block sidebar-toggle" id="toggleSidebar">
      <i id="sidebarToggleIcon" class="bi bi-list fs-4"></i>
  </button>

    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="{{ asset('ECOLOGIXputihround.png') }}" alt="EcoLogix" height="36" class="me-2" />
      <span class="fw-bold text-white d-none d-md-inline-block"></span>
    </a>
    
    {{-- <div class="navbar-text text-white ms-4 d-none d-lg-flex align-items-center">
      <div class="vr bg-light opacity-25 me-3" style="height: 24px;"></div>
      <span class="fs-6">{{ Auth::user()->perusahaan->nama_perusahaan ?? 'EcoLogix Platform' }}</span>
    </div> --}}

    <div class="ms-auto d-flex align-items-center gap-3">
      <!-- Status Badge -->
      <div class="d-none d-md-block">
        <span class="badge bg-light text-success d-flex align-items-center gap-1">
          <i class="bi bi-check-circle-fill"></i>
          <span class="small">Online</span>
        </span>
      </div>
      
      

      <!-- User Dropdown -->
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

  <div class="d-flex">
    <!-- Main Content -->
    <div class="main-content flex-grow-1 p-4" id="mainContent">
      @yield('content')
    </div>
  </div>

  @yield('scripts')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
 // Replace the existing sidebar toggle JavaScript code
    document.addEventListener('DOMContentLoaded', function() {
        const toggleSidebarBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const sidebarToggleIcon = document.getElementById('sidebarToggleIcon');

        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Toggle icon
            if (sidebar.classList.contains('collapsed')) {
                sidebarToggleIcon.classList.replace('bi-x-lg', 'bi-list');
                localStorage.setItem('sidebarState', 'collapsed');
            } else {
                sidebarToggleIcon.classList.replace('bi-list', 'bi-x-lg');
                localStorage.setItem('sidebarState', 'expanded');
            }
        }

        // Check saved state on page load
        const savedState = localStorage.getItem('sidebarState');
        if (savedState === 'collapsed') {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
            sidebarToggleIcon.classList.replace('bi-list', 'bi-x-lg');
        }

        // Add click event listener
        toggleSidebarBtn.addEventListener('click', toggleSidebar);

        // Handle responsive behavior
        function handleResize() {
            if (window.innerWidth < 768) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            } else if (savedState !== 'collapsed') {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
            }
        }

        // Add resize event listener
        window.addEventListener('resize', handleResize);
        
        // Initial check for screen size
        handleResize();
    });
  </script>
  @yield('scripts')
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Fungsi untuk memuat notifikasi
      function loadNotifications() {
        fetch('{{ route("notifikasi.get") }}')
          .then(response => response.json())
          .then(data => {
            const notificationList = document.getElementById('notificationList');
            const notificationCount = document.getElementById('notificationCount');
            const newNotificationCount = document.getElementById('newNotificationCount');
            
            // Hitung notifikasi yang belum dibaca
            const unreadCount = data.filter(notif => !notif.dibaca).length;
            
            // Update badge count
            notificationCount.textContent = unreadCount > 0 ? unreadCount : '0';
            newNotificationCount.textContent = unreadCount > 0 ? unreadCount + ' Baru' : '0 Baru';
            
            // Kosongkan list notifikasi
            notificationList.innerHTML = '';
            
            // Tampilkan maksimal 5 notifikasi terbaru
            const recentNotifications = data.slice(0, 5);
            
            if (recentNotifications.length === 0) {
              const emptyItem = document.createElement('li');
              emptyItem.innerHTML = `
                <div class="dropdown-item p-3 text-center">
                  <p class="text-muted mb-0">Tidak ada notifikasi</p>
                </div>
              `;
              notificationList.appendChild(emptyItem);
            } else {
              recentNotifications.forEach(notif => {
                const notifItem = document.createElement('li');
                
                // Tentukan ikon berdasarkan tipe notifikasi
                let iconClass = 'bi-bell';
                let bgColorClass = 'bg-success';
                
                if (notif.kategori_notifikasi === 'emisi_karbon') {
                  iconClass = 'bi-cloud-plus';
                  bgColorClass = 'bg-info';
                } else if (notif.kategori_notifikasi === 'status_emisi') {
                  iconClass = 'bi-check-circle';
                  bgColorClass = 'bg-warning';
                } else if (notif.kategori_notifikasi === 'kompensasi_emisi') {
                  iconClass = 'bi-currency-exchange';
                  bgColorClass = 'bg-primary';
                }
                
                // Format tanggal
                const date = new Date(notif.created_at);
                const now = new Date();
                const diffTime = Math.abs(now - date);
                const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                const diffHours = Math.floor(diffTime / (1000 * 60 * 60));
                const diffMinutes = Math.floor(diffTime / (1000 * 60));
                
                let timeAgo;
                if (diffDays > 0) {
                  timeAgo = diffDays + ' hari lalu';
                } else if (diffHours > 0) {
                  timeAgo = diffHours + ' jam lalu';
                } else if (diffMinutes > 0) {
                  timeAgo = diffMinutes + ' menit lalu';
                } else {
                  timeAgo = 'Baru saja';
                }
                
                // Tentukan judul berdasarkan kategori notifikasi
                let title = notif.judul || '';
                if (!title) {
                  if (notif.kategori_notifikasi === 'emisi_karbon') {
                    title = 'Input Emisi Karbon Baru';
                  } else if (notif.kategori_notifikasi === 'status_emisi') {
                    title = 'Perubahan Status Emisi';
                  } else if (notif.kategori_notifikasi === 'kompensasi_emisi') {
                    title = 'Kompensasi Emisi Baru';
                  }
                }
                
                notifItem.innerHTML = `
                  <a class="dropdown-item p-3 d-flex align-items-start gap-2 ${!notif.dibaca ? 'fw-bold bg-light' : ''}" href="#">
                    <div class="flex-shrink-0">
                      <div class="rounded-circle ${bgColorClass} bg-opacity-10 p-2">
                        <i class="bi ${iconClass} text-${bgColorClass.replace('bg-', '')}"></i>
                      </div>
                    </div>
                    <div>
                      <p class="mb-0 fw-medium">${title}</p>
                      <p class="text-muted small mb-0">${notif.isi || notif.deskripsi}</p>
                      <p class="text-muted small mb-0">${timeAgo}</p>
                    </div>
                  </a>
                `;
                
                notificationList.appendChild(notifItem);
                
                // Tambahkan divider setelah setiap notifikasi kecuali yang terakhir
                if (recentNotifications.indexOf(notif) < recentNotifications.length - 1) {
                  const divider = document.createElement('li');
                  divider.innerHTML = '<hr class="dropdown-divider my-0">';
                  notificationList.appendChild(divider);
                }
              });
            }
          })
          .catch(error => {
            console.error('Error loading notifications:', error);
          });
      }
      
      // Muat notifikasi saat halaman dimuat
      loadNotifications();
      
      // Muat notifikasi setiap 30 detik
      setInterval(loadNotifications, 30000);
      
      // Tambahkan event listener untuk menandai notifikasi sebagai dibaca saat dropdown dibuka
      const notificationDropdown = document.getElementById('notificationDropdown');
      notificationDropdown.addEventListener('click', function() {
        // Tandai semua notifikasi sebagai dibaca setelah delay singkat
        setTimeout(() => {
          fetch('{{ route("notifikasi.markAsRead") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Reload notifikasi setelah ditandai sebagai dibaca
              loadNotifications();
            }
          })
          .catch(error => {
            console.error('Error marking notifications as read:', error);
          });
        }, 1000); // Delay 1 detik
      });
    });
  </script>
</body>
</html>