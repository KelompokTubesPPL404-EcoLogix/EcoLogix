@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Notifikasi</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div id="notification-container">
                        <!-- Notifikasi akan dimuat di sini -->
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