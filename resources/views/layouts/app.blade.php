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
    .dropdown-item.unread {
      background-color: rgba(40, 167, 69, 0.05);
      border-left: 3px solid #28a745;
    }
    
    /* Notification dropdown scrollable area */
    .notification-scrollable {
      max-height: 350px;
      overflow-y: auto;
      scrollbar-width: thin;
    }
    
    .notification-scrollable::-webkit-scrollbar {
      width: 6px;
    }
    
    .notification-scrollable::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }
    
    .notification-scrollable::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 10px;
    }
    
    .notification-scrollable::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }
    
    /* Notification item styles */
    .notification-item {
      transition: all 0.2s ease;
      border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .notification-item:hover {
      background-color: rgba(40, 167, 69, 0.03);
    }
    
    .notification-item .notification-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      border-radius: 50%;
    }
    
    .notification-item .notification-time {
      font-size: 0.7rem;
      color: #6c757d;
    }
    
    .notification-actions {
      display: flex;
      justify-content: space-between;
      padding: 8px 16px;
      background-color: #f8f9fa;
      border-top: 1px solid rgba(0,0,0,0.05);
    }
    
    /* Carbon Theme Gradients */
    .bg-gradient-success-light {
      background: linear-gradient(120deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.2) 100%);
    }
    
    .bg-carbon-theme {
      background: linear-gradient(135deg, #104117 0%, #007f2d 100%);
    }
    
    /* Update these CSS rules in the <style> section */
    .sidebar {
        background: linear-gradient(#007f2d 100%);
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
  <!-- Enhanced Navbar with EcoLogix Theme -->
  <nav class="navbar navbar-expand-lg navbar-dark px-3" style="background: linear-gradient(#ffffff 100%); box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
    <button class="btn btn-outline-black me-2 d-none d-md-block" id="toggleSidebar">
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
          <span class="notification-badge" id="notificationCount">0</span>
        </button>
        <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown" style="width: 350px; border-radius: 8px; border: none; margin-top: 10px; padding: 0;">
          <!-- Header -->
          <div class="dropdown-header fw-bold d-flex justify-content-between align-items-center p-3">
            <span>Notifikasi</span>
            <span class="badge bg-success rounded-pill" id="newNotificationCount">0 Baru</span>
          </div>
          
          <!-- Tabs -->
          <ul class="nav nav-tabs nav-fill" id="notificationTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active small py-2" id="recent-tab" data-bs-toggle="tab" data-bs-target="#recent" type="button" role="tab" aria-controls="recent" aria-selected="true">Terbaru</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link small py-2" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="false">Semua</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link small py-2" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread" type="button" role="tab" aria-controls="unread" aria-selected="false">Belum Dibaca</button>
            </li>
          </ul>
          
          <!-- Tab Content -->
          <div class="tab-content" id="notificationTabContent">
            <!-- Recent Notifications -->
            <div class="tab-pane fade show active notification-scrollable" id="recent" role="tabpanel" aria-labelledby="recent-tab">
              <div id="recentNotificationList">
                <!-- Recent notifications will be loaded here -->
              </div>
            </div>
            
            <!-- All Notifications -->
            <div class="tab-pane fade notification-scrollable" id="all" role="tabpanel" aria-labelledby="all-tab">
              <div id="allNotificationList">
                <!-- All notifications will be loaded here -->
              </div>
            </div>
            
            <!-- Unread Notifications -->
            <div class="tab-pane fade notification-scrollable" id="unread" role="tabpanel" aria-labelledby="unread-tab">
              <div id="unreadNotificationList">
                <!-- Unread notifications will be loaded here -->
              </div>
            </div>
          </div>
          
          <!-- Footer Actions -->
          <div class="notification-actions">
            <button class="btn btn-sm btn-outline-secondary" id="markAllAsRead">
              <i class="bi bi-check-all me-1"></i>Tandai Semua Dibaca
            </button>
            <a class="btn btn-sm btn-success" href="#" id="refreshNotifications">
              <i class="bi bi-arrow-clockwise me-1"></i>Segarkan
            </a>
          </div>
        </div>
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
          <li><a class="dropdown-item" href="{{ route(Auth::user()->role . '.profile.index') }}"><i class="bi bi-person-gear me-2 text-success"></i> Profil Saya</a></li>
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
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
      @yield('sidebar')
    </div>

    <!-- Main Content -->
    <div class="main-content flex-grow-1 p-4" id="mainContent">
      @yield('content')
    </div>
  </div>

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
            const notificationCount = document.getElementById('notificationCount');
            const newNotificationCount = document.getElementById('newNotificationCount');
            const recentNotificationList = document.getElementById('recentNotificationList');
            const allNotificationList = document.getElementById('allNotificationList');
            const unreadNotificationList = document.getElementById('unreadNotificationList');
            
            // Hitung notifikasi yang belum dibaca
            const unreadNotifications = data.filter(notif => !notif.dibaca);
            const unreadCount = unreadNotifications.length;
            
            // Update badge count
            notificationCount.textContent = unreadCount > 0 ? unreadCount : '0';
            newNotificationCount.textContent = unreadCount > 0 ? unreadCount + ' Baru' : '0 Baru';
            
            // Kosongkan list notifikasi
            recentNotificationList.innerHTML = '';
            allNotificationList.innerHTML = '';
            unreadNotificationList.innerHTML = '';
            
            // Tampilkan maksimal 5 notifikasi terbaru untuk tab Recent
            const recentNotifications = data.slice(0, 5);
            
            // Fungsi untuk membuat HTML notifikasi
            function createNotificationHTML(notif) {
              // Tentukan ikon berdasarkan tipe notifikasi
              let iconClass = 'bi-bell';
              let bgColorClass = 'bg-success';
              let textColorClass = 'text-success';
              
              if (notif.kategori_notifikasi === 'emisi_karbon') {
                iconClass = 'bi-cloud-plus';
                bgColorClass = 'bg-info';
                textColorClass = 'text-info';
              } else if (notif.kategori_notifikasi === 'status_emisi') {
                iconClass = 'bi-check-circle';
                bgColorClass = 'bg-warning';
                textColorClass = 'text-warning';
              } else if (notif.kategori_notifikasi === 'kompensasi_emisi') {
                iconClass = 'bi-currency-exchange';
                bgColorClass = 'bg-primary';
                textColorClass = 'text-primary';
              }
              
              // Format tanggal
              const date = new Date(notif.created_at || notif.tanggal_notifikasi);
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
              
              return `
                <div class="notification-item p-3 ${!notif.dibaca ? 'unread' : ''}" data-id="${notif.id}">
                  <div class="d-flex align-items-start gap-2">
                    <div class="notification-icon ${bgColorClass} bg-opacity-10">
                      <i class="bi ${iconClass} ${textColorClass}"></i>
                    </div>
                    <div class="flex-grow-1">
                      <div class="d-flex justify-content-between align-items-start">
                        <h6 class="mb-0 fw-medium">${title}</h6>
                        <div class="dropdown">
                          <button class="btn btn-sm text-muted p-0" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item mark-as-read" data-id="${notif.id}"><i class="bi bi-check-circle me-2"></i>Tandai Dibaca</button></li>
                          </ul>
                        </div>
                      </div>
                      <p class="text-muted small mb-1">${notif.isi || notif.deskripsi}</p>
                      <div class="d-flex justify-content-between align-items-center">
                        <span class="notification-time"><i class="bi bi-clock me-1"></i>${timeAgo}</span>
                        ${!notif.dibaca ? '<span class="badge bg-success bg-opacity-10 text-success small">Baru</span>' : ''}
                      </div>
                    </div>
                  </div>
                </div>
              `;
            }
            
            // Fungsi untuk menampilkan pesan kosong
            function createEmptyMessage(message) {
              return `
                <div class="p-4 text-center">
                  <i class="bi bi-bell-slash fs-3 text-muted mb-2"></i>
                  <p class="text-muted mb-0">${message}</p>
                </div>
              `;
            }
            
            // Tampilkan notifikasi terbaru
            if (recentNotifications.length === 0) {
              recentNotificationList.innerHTML = createEmptyMessage('Tidak ada notifikasi terbaru');
            } else {
              recentNotifications.forEach(notif => {
                recentNotificationList.innerHTML += createNotificationHTML(notif);
              });
            }
            
            // Tampilkan semua notifikasi
            if (data.length === 0) {
              allNotificationList.innerHTML = createEmptyMessage('Tidak ada notifikasi');
            } else {
              data.forEach(notif => {
                allNotificationList.innerHTML += createNotificationHTML(notif);
              });
            }
            
            // Tampilkan notifikasi yang belum dibaca
            if (unreadNotifications.length === 0) {
              unreadNotificationList.innerHTML = createEmptyMessage('Tidak ada notifikasi yang belum dibaca');
            } else {
              unreadNotifications.forEach(notif => {
                unreadNotificationList.innerHTML += createNotificationHTML(notif);
              });
            }
            
            // Tambahkan event listener untuk tombol "Tandai Dibaca"
            document.querySelectorAll('.mark-as-read').forEach(button => {
              button.addEventListener('click', function(e) {
                e.preventDefault();
                const notificationId = this.getAttribute('data-id');
                markOneAsRead(notificationId);
              });
            });
          })
          .catch(error => {
            console.error('Error loading notifications:', error);
          });
      }
      
      // Fungsi untuk menandai satu notifikasi sebagai dibaca
      function markOneAsRead(notificationId) {
        fetch('{{ route("notifikasi.markOneAsRead") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ id: notificationId })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Reload notifikasi setelah ditandai sebagai dibaca
            loadNotifications();
          }
        })
        .catch(error => {
          console.error('Error marking notification as read:', error);
        });
      }
      
      // Fungsi untuk menandai semua notifikasi sebagai dibaca
      function markAllAsRead() {
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
          console.error('Error marking all notifications as read:', error);
        });
      }
      
      // Muat notifikasi saat halaman dimuat
      loadNotifications();
      
      // Muat notifikasi setiap 30 detik
      setInterval(loadNotifications, 30000);
      
      // Event listener untuk tombol "Tandai Semua Dibaca"
      document.getElementById('markAllAsRead').addEventListener('click', function() {
        markAllAsRead();
      });
      
      // Event listener untuk tombol "Segarkan"
      document.getElementById('refreshNotifications').addEventListener('click', function(e) {
        e.preventDefault();
        loadNotifications();
      });
    });
  </script>
</body>
</html>