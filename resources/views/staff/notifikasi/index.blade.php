@extends('layouts.staff')

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
    .notification-date {
        font-size: 0.8rem;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-bell me-2"></i>Notifikasi
        </h1>
        <a href="{{ route('staff.dashboard') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
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

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">Daftar Notifikasi</h6>
        </div>
        <div class="card-body">
            <div id="notifikasi-container">
                @forelse($notifikasi as $notif)
                <div class="card mb-3 notification-card {{ $notif->kategori_notifikasi == 'emisi_karbon' ? 'notification-emisi' : 'notification-status' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">
                                @if($notif->kategori_notifikasi == 'emisi_karbon')
                                <i class="bi bi-cloud-plus text-info me-2"></i>Input Emisi Karbon
                                @else
                                <i class="bi bi-check-circle text-warning me-2"></i>Perubahan Status Emisi
                                @endif
                            </h5>
                            <span class="notification-date">
                                <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($notif->tanggal_notifikasi)->format('d M Y') }}
                            </span>
                        </div>
                        <p class="card-text">{{ $notif->deskripsi }}</p>
                    </div>
                </div>
                @empty
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>Tidak ada notifikasi saat ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // Auto refresh notifikasi setiap 1 menit
    setInterval(function() {
        fetch('/api/notifikasi')
            .then(response => response.json())
            .then(data => {
                // Update UI dengan notifikasi terbaru
                if (data.notifikasi && data.notifikasi.length > 0) {
                    updateNotifikasiUI(data.notifikasi);
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }, 60000); // 60000 ms = 1 menit
    
    function updateNotifikasiUI(notifikasi) {
        const container = document.getElementById('notifikasi-container');
        let html = '';
        
        notifikasi.forEach(notif => {
            const kategoriClass = notif.kategori_notifikasi === 'emisi_karbon' ? 'notification-emisi' : 'notification-status';
            
            let icon = '';
            let title = '';
            
            if (notif.kategori_notifikasi === 'emisi_karbon') {
                icon = '<i class="bi bi-cloud-plus text-info me-2"></i>';
                title = 'Input Emisi Karbon';
            } else {
                icon = '<i class="bi bi-check-circle text-warning me-2"></i>';
                title = 'Perubahan Status Emisi';
            }
            
            const date = new Date(notif.tanggal_notifikasi).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
            
            html += `
            <div class="card mb-3 notification-card ${kategoriClass}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0">
                            ${icon}${title}
                        </h5>
                        <span class="notification-date">
                            <i class="bi bi-calendar3 me-1"></i>${date}
                        </span>
                    </div>
                    <p class="card-text">${notif.deskripsi}</p>
                </div>
            </div>
            `;
        });
        
        if (html === '') {
            html = `
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>Tidak ada notifikasi saat ini.
            </div>
            `;
        }
        
        container.innerHTML = html;
    }
</script>
@endpush