@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Notifikasi</h6>
                    <div class="d-flex">
                        <button id="markAllAsReadBtn" class="btn btn-sm btn-outline-primary me-2">
                            <i class="bi bi-check-all me-1"></i>Tandai Semua Dibaca
                        </button>
                        <button id="refreshNotificationsBtn" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>Segarkan
                        </button>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-3">
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

                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs mb-4" id="notificationTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">
                                    <i class="bi bi-collection me-1"></i>Semua
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread" type="button" role="tab" aria-controls="unread" aria-selected="false">
                                    <i class="bi bi-envelope me-1"></i>Belum Dibaca
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="emisi-tab" data-bs-toggle="tab" data-bs-target="#emisi" type="button" role="tab" aria-controls="emisi" aria-selected="false">
                                    <i class="bi bi-cloud-plus me-1"></i>Emisi Karbon
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="status-tab" data-bs-toggle="tab" data-bs-target="#status" type="button" role="tab" aria-controls="status" aria-selected="false">
                                    <i class="bi bi-check-circle me-1"></i>Status Emisi
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="kompensasi-tab" data-bs-toggle="tab" data-bs-target="#kompensasi" type="button" role="tab" aria-controls="kompensasi" aria-selected="false">
                                    <i class="bi bi-currency-exchange me-1"></i>Kompensasi Emisi
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="notificationTabsContent">
                            <!-- All Notifications Tab -->
                            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                                <div class="row" id="allNotificationContainer">
                                    <!-- Notifications will be loaded here via JavaScript -->
                                    <div class="col-12">
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Unread Notifications Tab -->
                            <div class="tab-pane fade" id="unread" role="tabpanel" aria-labelledby="unread-tab">
                                <div class="row" id="unreadNotificationContainer">
                                    <!-- Unread notifications will be loaded here via JavaScript -->
                                    <div class="col-12">
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Emisi Karbon Notifications Tab -->
                            <div class="tab-pane fade" id="emisi" role="tabpanel" aria-labelledby="emisi-tab">
                                <div class="row" id="emisiNotificationContainer">
                                    <!-- Emisi Karbon notifications will be loaded here via JavaScript -->
                                    <div class="col-12">
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Emisi Notifications Tab -->
                            <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status-tab">
                                <div class="row" id="statusNotificationContainer">
                                    <!-- Status Emisi notifications will be loaded here via JavaScript -->
                                    <div class="col-12">
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Kompensasi Emisi Notifications Tab -->
                            <div class="tab-pane fade" id="kompensasi" role="tabpanel" aria-labelledby="kompensasi-tab">
                                <div class="row" id="kompensasiNotificationContainer">
                                    <!-- Kompensasi Emisi notifications will be loaded here via JavaScript -->
                                    <div class="col-12">
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .notification-item {
        padding: 15px;
        border-bottom: 1px solid #eee;
        transition: background-color 0.3s;
    }
    
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    
    .notification-item .title {
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .notification-item .date {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .notification-item .content {
        margin-top: 5px;
    }
    
    .notification-item.unread {
        border-left: 4px solid #007bff;
    }
    
    .empty-notification {
        text-align: center;
        padding: 30px;
        color: #6c757d;
    }
</style>

<script>
    // Fungsi untuk memuat notifikasi
    function loadNotifications() {
        fetch('/api/notifikasi')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('notification-container');
                container.innerHTML = '';
                
                if (data.length === 0) {
                    container.innerHTML = '<div class="empty-notification">Tidak ada notifikasi</div>';
                    return;
                }
                
                data.forEach(notification => {
                    const notificationItem = document.createElement('div');
                    notificationItem.className = `notification-item ${notification.dibaca ? '' : 'unread'}`;
                    
                    const date = new Date(notification.tanggal_notifikasi);
                    const formattedDate = date.toLocaleDateString('id-ID', { 
                        day: 'numeric', 
                        month: 'long', 
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    notificationItem.innerHTML = `
                        <div class="title">${notification.kategori_notifikasi}</div>
                        <div class="date">${formattedDate}</div>
                        <div class="content">${notification.deskripsi}</div>
                    `;
                    
                    container.appendChild(notificationItem);
                });
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
                document.getElementById('notification-container').innerHTML = 
                    '<div class="alert alert-danger">Gagal memuat notifikasi</div>';
            });
    }
    
    // Muat notifikasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        loadNotifications();
        
        // Refresh notifikasi setiap 1 menit
        setInterval(loadNotifications, 60000);
    });
</script>
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
                            title = 'Input Emisi Karbon';
                            bgColor = 'bg-info bg-opacity-10';
                        } else if (notif.kategori_notifikasi === 'status_emisi') {
                            notifClass = 'border-warning';
                            icon = '<i class="bi bi-check-circle text-warning"></i>';
                            title = 'Perubahan Status Emisi';
                            bgColor = 'bg-warning bg-opacity-10';
                        } else if (notif.kategori_notifikasi === 'kompensasi_emisi') {
                            notifClass = 'border-success';
                            icon = '<i class="bi bi-currency-exchange text-success"></i>';
                            title = 'Kompensasi Emisi';
                            bgColor = 'bg-success bg-opacity-10';
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