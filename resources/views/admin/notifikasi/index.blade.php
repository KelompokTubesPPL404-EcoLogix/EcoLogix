@extends('layouts.app')

@section('title', 'Notifikasi')

@push('css')
<style>
    .notification-card {
        border-left: 4px solid #28a745;
        transition: all 0.3s ease;
    }
    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15);
    }
    .notification-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 3px solid #28a745;
    }
    .notification-emisi {
        border-left-color: #17a2b8;
    }
    .notification-status {
        border-left-color: #ffc107;
    }
    .notification-kompensasi {
        border-left-color: #6f42c1;
    }
    .notification-date {
        font-size: 0.8rem;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Notifikasi</h1>
        <div>
          <button id="markAllAsReadBtn" class="btn btn-outline-success me-2">
            <i class="bi bi-check-all me-1"></i>Tandai Semua Dibaca
          </button>
          <button id="refreshNotificationsBtn" class="btn btn-primary">
            <i class="bi bi-arrow-clockwise me-1"></i>Segarkan
          </button>
        </div>
      </div>
      
      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif
      
      @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif
      
      <!-- Filter Tabs -->
      <ul class="nav nav-tabs mb-4" id="notificationTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">Semua</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread" type="button" role="tab" aria-controls="unread" aria-selected="false">Belum Dibaca</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="emisi-tab" data-bs-toggle="tab" data-bs-target="#emisi" type="button" role="tab" aria-controls="emisi" aria-selected="false">Emisi Karbon</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="status-tab" data-bs-toggle="tab" data-bs-target="#status" type="button" role="tab" aria-controls="status" aria-selected="false">Status Emisi</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="kompensasi-tab" data-bs-toggle="tab" data-bs-target="#kompensasi" type="button" role="tab" aria-controls="kompensasi" aria-selected="false">Kompensasi Emisi</button>
        </li>
      </ul>
      
      <!-- Tab Content -->
      <div class="tab-content" id="notificationTabContent">
        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
          <div id="allNotificationContainer" class="row">
            <!-- Semua notifikasi akan dimuat di sini -->
          </div>
        </div>
        <div class="tab-pane fade" id="unread" role="tabpanel" aria-labelledby="unread-tab">
          <div id="unreadNotificationContainer" class="row">
            <!-- Notifikasi belum dibaca akan dimuat di sini -->
          </div>
        </div>
        <div class="tab-pane fade" id="emisi" role="tabpanel" aria-labelledby="emisi-tab">
          <div id="emisiNotificationContainer" class="row">
            <!-- Notifikasi emisi karbon akan dimuat di sini -->
          </div>
        </div>
        <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status-tab">
          <div id="statusNotificationContainer" class="row">
            <!-- Notifikasi status emisi akan dimuat di sini -->
          </div>
        </div>
        <div class="tab-pane fade" id="kompensasi" role="tabpanel" aria-labelledby="kompensasi-tab">
          <div id="kompensasiNotificationContainer" class="row">
            <!-- Notifikasi kompensasi emisi akan dimuat di sini -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk memuat notifikasi
        function loadNotifications() {
            fetch('/api/notifikasi')
                .then(response => response.json())
                .then(data => {
                    // Dapatkan semua container
                    const allContainer = document.getElementById('allNotificationContainer');
                    const unreadContainer = document.getElementById('unreadNotificationContainer');
                    const emisiContainer = document.getElementById('emisiNotificationContainer');
                    const statusContainer = document.getElementById('statusNotificationContainer');
                    const kompensasiContainer = document.getElementById('kompensasiNotificationContainer');
                    
                    // Kosongkan semua container
                    allContainer.innerHTML = '';
                    unreadContainer.innerHTML = '';
                    emisiContainer.innerHTML = '';
                    statusContainer.innerHTML = '';
                    kompensasiContainer.innerHTML = '';
                    
                    // Filter notifikasi berdasarkan kategori dan status dibaca
                    const unreadNotifications = data.filter(notif => !notif.dibaca);
                    const emisiNotifications = data.filter(notif => notif.kategori_notifikasi === 'emisi_karbon');
                    const statusNotifications = data.filter(notif => notif.kategori_notifikasi === 'status_emisi');
                    const kompensasiNotifications = data.filter(notif => notif.kategori_notifikasi === 'kompensasi_emisi');
                    
                    // Fungsi untuk membuat HTML notifikasi
                    function createNotificationHTML(notif) {
                        // Tentukan kelas dan ikon berdasarkan kategori notifikasi
                        let notifClass = '';
                        let icon = '';
                        let title = '';
                        let bgColor = '';
                        
                        if (notif.kategori_notifikasi === 'emisi_karbon') {
                            notifClass = 'border-info';
                            icon = '<i class="bi bi-cloud-plus text-info"></i>';
                            title = 'Input Emisi Karbon Baru';
                            bgColor = 'bg-info bg-opacity-10';
                        } else if (notif.kategori_notifikasi === 'status_emisi') {
                            notifClass = 'border-warning';
                            icon = '<i class="bi bi-check-circle text-warning"></i>';
                            title = 'Perubahan Status Emisi';
                            bgColor = 'bg-warning bg-opacity-10';
                        } else if (notif.kategori_notifikasi === 'kompensasi_emisi') {
                            notifClass = 'border-primary';
                            icon = '<i class="bi bi-currency-exchange text-primary"></i>';
                            title = 'Kompensasi Emisi Baru';
                            bgColor = 'bg-primary bg-opacity-10';
                        }
                        
                        // Format tanggal
                        const date = new Date(notif.tanggal_notifikasi || notif.created_at);
                        const formattedDate = new Intl.DateTimeFormat('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        }).format(date);
                        
                        // Format waktu yang lalu
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
                        
                        return `
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-sm ${!notif.dibaca ? 'unread' : ''} ${notifClass}" style="border-left-width: 4px;">
                                    <div class="card-header ${bgColor} d-flex justify-content-between align-items-center py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                ${icon}
                                            </div>
                                            <h6 class="mb-0 fw-bold">${title}</h6>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><button class="dropdown-item mark-as-read" data-id="${notif.kode_notifikasi}"><i class="bi bi-check-circle me-2"></i>Tandai Dibaca</button></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">${notif.deskripsi || notif.isi}</p>
                                    </div>
                                    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>${formattedDate}</small>
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i>${timeAgo}</small>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    
                    // Fungsi untuk menampilkan pesan kosong
                    function createEmptyMessage(message) {
                        return `
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>${message}
                                </div>
                            </div>
                        `;
                    }
                    
                    // Tampilkan semua notifikasi
                    if (data.length === 0) {
                        allContainer.innerHTML = createEmptyMessage('Tidak ada notifikasi saat ini.');
                    } else {
                        data.forEach(notif => {
                            allContainer.innerHTML += createNotificationHTML(notif);
                        });
                    }
                    
                    // Tampilkan notifikasi yang belum dibaca
                    if (unreadNotifications.length === 0) {
                        unreadContainer.innerHTML = createEmptyMessage('Tidak ada notifikasi yang belum dibaca.');
                    } else {
                        unreadNotifications.forEach(notif => {
                            unreadContainer.innerHTML += createNotificationHTML(notif);
                        });
                    }
                    
                    // Tampilkan notifikasi emisi karbon
                    if (emisiNotifications.length === 0) {
                        emisiContainer.innerHTML = createEmptyMessage('Tidak ada notifikasi emisi karbon.');
                    } else {
                        emisiNotifications.forEach(notif => {
                            emisiContainer.innerHTML += createNotificationHTML(notif);
                        });
                    }
                    
                    // Tampilkan notifikasi status emisi
                    if (statusNotifications.length === 0) {
                        statusContainer.innerHTML = createEmptyMessage('Tidak ada notifikasi status emisi.');
                    } else {
                        statusNotifications.forEach(notif => {
                            statusContainer.innerHTML += createNotificationHTML(notif);
                        });
                    }
                    
                    // Tampilkan notifikasi kompensasi emisi
                    if (kompensasiNotifications.length === 0) {
                        kompensasiContainer.innerHTML = createEmptyMessage('Tidak ada notifikasi kompensasi emisi.');
                    } else {
                        kompensasiNotifications.forEach(notif => {
                            kompensasiContainer.innerHTML += createNotificationHTML(notif);
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
                    const containers = [
                        'allNotificationContainer',
                        'unreadNotificationContainer',
                        'emisiNotificationContainer',
                        'statusNotificationContainer',
                        'kompensasiNotificationContainer'
                    ];
                    
                    containers.forEach(containerId => {
                        document.getElementById(containerId).innerHTML = `
                            <div class="col-12">
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Terjadi kesalahan saat memuat notifikasi.
                                </div>
                            </div>
                        `;
                    });
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
        
        // Muat notifikasi setiap 1 menit
        setInterval(loadNotifications, 60000);
        
        // Event listener untuk tombol "Tandai Semua Dibaca"
        document.getElementById('markAllAsReadBtn').addEventListener('click', function() {
            markAllAsRead();
        });
        
        // Event listener untuk tombol "Segarkan"
        document.getElementById('refreshNotificationsBtn').addEventListener('click', function() {
            loadNotifications();
        });
    });
</script>
@endpush