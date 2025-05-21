@extends('layouts.manager')

@section('title', 'Dashboard Manager')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="card-title fw-bold text-success mb-0">Selamat Datang, {{ Auth::user()->nama }}!</h4>
            <p class="text-muted">Anda login sebagai Manager untuk perusahaan {{ Auth::user()->perusahaan->nama_perusahaan ?? 'Unknown' }}</p>
        </div>
        <div>
            <span class="text-success">Periode: </span>
            <select class="form-select form-select-sm d-inline-block w-auto">
                <option>Januari 2025</option>
                <option>Februari 2025</option>
                <option>Maret 2025</option>
                <option selected>April 2025</option>
            </select>
        </div>
    </div>
    
    <!-- Ringkasan Kartu -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-2">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Emisi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format(\App\Models\EmisiKarbon::where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')->where('status', 'approved')->sum('kadar_emisi_karbon') / 1000, 2) }} Ton CO<sub>2</sub>e</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cloud-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Emisi Dikompensasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format(\App\Models\KompensasiEmisiCarbon::whereHas('emisiCarbon', function($q) { $q->where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')->where('status', 'approved'); })->sum('jumlah_kompensasi') / 1000, 2) }} Ton CO<sub>2</sub>e</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Persentase Kompensasi
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    @php
                                        $totalEmisi = \App\Models\EmisiKarbon::where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')
                                            ->where('status', 'approved')
                                            ->sum('kadar_emisi_karbon');
                                        $totalKompensasi = \App\Models\KompensasiEmisiCarbon::whereHas('emisiCarbon', function($q) { $q->where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%'); })->sum('jumlah_kompensasi');
                                        $persentaseKompensasi = $totalEmisi > 0 ? round(($totalKompensasi / $totalEmisi) * 100) : 0;
                                        
                                        $pendingCount = \App\Models\EmisiKarbon::where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')
                                            ->where('status', 'pending')
                                            ->count();
                                            
                                        $approvedCount = \App\Models\EmisiKarbon::where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')
                                            ->where('status', 'approved')
                                            ->count();
                                            
                                        $rejectedCount = \App\Models\EmisiKarbon::where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')
                                            ->where('status', 'rejected')
                                            ->count();
                                    @endphp
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $persentaseKompensasi }}%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persentaseKompensasi }}%"
                                            aria-valuenow="{{ $persentaseKompensasi }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard-data fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-2">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Carbon Credit Tersedia
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format(\App\Models\PembelianCarbonCredit::whereHas('penyedia', function($q) { $q->where('kode_perusahaan', Auth::user()->kode_perusahaan); })->sum('jumlah_kompensasi'), 0) }} Kredit</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-dollar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <!-- Grafik Emisi -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Emisi Karbon per Bulan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="emisiChart" height="300"></canvas>
                    </div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Ambil token CSRF dari meta tag
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    // Fungsi untuk mengambil data chart emisi karbon
                    async function loadChartData() {
                        try {
                            // Show loading state
                            document.querySelector('.chart-area').style.opacity = '0.5';
                            
                            // Fetch data from API - manager default to yearly view
                            const response = await fetch('/api/dashboard/emisi-chart?period=1Y', {
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                credentials: 'same-origin'
                            });
                            
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            
                            const data = await response.json();
                            
                            // Create chart
                            const ctx = document.getElementById('emisiChart').getContext('2d');
                            window.emisiChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: data.labels,
                                    datasets: [{
                                        label: 'Emisi Karbon (Ton CO2e)',
                                        data: data.data.map(value => value / 1000), // Convert kg to ton
                                        backgroundColor: 'rgba(0, 127, 45, 0.2)',
                                        borderColor: '#007f2d',
                                        borderWidth: 2,
                                        pointBackgroundColor: '#007f2d',
                                        tension: 0.1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    return `${context.parsed.y.toLocaleString('id-ID')} Ton CO₂e`;
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                callback: function(value) {
                                                    return value.toLocaleString('id-ID');
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                            
                            // Remove loading state
                            document.querySelector('.chart-area').style.opacity = '1';
                            
                        } catch (error) {
                            console.error('Error loading chart data:', error);
                            document.querySelector('.chart-area').style.opacity = '1';
                        }
                    }
                    
                    // Fungsi untuk memuat data kategori emisi
                    async function loadCategoryData() {
                        try {
                            // Fetch data from API
                            const response = await fetch('/api/dashboard/emisi-by-category', {
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                credentials: 'same-origin'
                            });
                            
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            
                            const data = await response.json();
                            
                            // Create donut chart
                            const ctx = document.getElementById('sumberEmisiChart').getContext('2d');
                            window.sumberEmisiChart = new Chart(ctx, {
                                type: 'doughnut',
                                data: {
                                    labels: data.labels,
                                    datasets: [{
                                        data: data.data.map(value => value / 1000), // Convert kg to ton
                                        backgroundColor: data.colors
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    cutout: '70%',
                                    plugins: {
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    return `${context.label}: ${(context.parsed).toLocaleString('id-ID')} Ton CO₂e (${data.percentages[context.dataIndex]}%)`;
                                                }
                                            }
                                        }
                                    }
                                });
                            
                        } catch (error) {
                            console.error('Error loading category data:', error);
                        }
                    }
                    
                    // Load chart data
                    loadChartData();
                    loadCategoryData();
                });
                </script>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Sumber Emisi</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="sumberEmisiChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Ringkasan Tim</h5>
                    
                    <div class="mt-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 d-flex flex-column text-center bg-light">
                                    <span class="text-muted small">Total Admin</span>
                                    <span class="fs-4 fw-bold text-primary">{{ \App\Models\User::where('role', 'admin')->where('kode_perusahaan', Auth::user()->kode_perusahaan)->count() }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 d-flex flex-column text-center bg-light">
                                    <span class="text-muted small">Total Staff</span>
                                    <span class="fs-4 fw-bold text-info">{{ \App\Models\User::where('role', 'staff')->where('kode_perusahaan', Auth::user()->kode_perusahaan)->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('manager.admin.create') }}" class="btn btn-success">Tambah Admin</a>
                        <a href="{{ route('manager.staff.create') }}" class="btn btn-outline-success">Tambah Staff</a>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Status Emisi Carbon</h5>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="progress flex-grow-1 mx-2" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 70%;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="badge bg-success">70%</span>
                    </div>
                    <small class="text-muted d-block mt-2">Target pengurangan emisi untuk tahun ini</small>
                    
                    <a href="#" class="btn btn-outline-success btn-sm w-100 mt-3">Lihat Detail</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Aktivitas Terbaru</h5>
                    <div class="list-group list-group-flush mt-3">
                        <div class="list-group-item border-0 d-flex justify-content-between align-items-center px-0 py-2">
                            <div>
                                <p class="mb-0">Admin baru ditambahkan</p>
                                <small class="text-muted">Andi Wijaya</small>
                            </div>
                            <small class="text-muted">Hari ini</small>
                        </div>
                        <div class="list-group-item border-0 d-flex justify-content-between align-items-center px-0 py-2">
                            <div>
                                <p class="mb-0">Persetujuan kompensasi</p>
                                <small class="text-muted">Departemen Operasional</small>
                            </div>
                            <small class="text-muted">Kemarin</small>
                        </div>
                        <div class="list-group-item border-0 d-flex justify-content-between align-items-center px-0 py-2">
                            <div>
                                <p class="mb-0">Laporan tahunan diupload</p>
                                <small class="text-muted">Tim Admin</small>
                            </div>
                            <small class="text-muted">3 hari lalu</small>
                        </div>
                    </div>
                    
                    <div class="d-grid mt-3">
                        <button class="btn btn-outline-success btn-sm">Lihat Semua Aktivitas</button>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title fw-bold mb-0">Kompensasi Emisi</h5>
                        <a href="{{ route('manager.kompensasi.index') }}" class="text-success">Lihat Semua</a>
                    </div>
                    
                    <div class="border rounded-3 p-3 d-flex justify-content-between align-items-center bg-light mb-3">
                        <div>
                            <span class="text-muted small">Total Pengajuan</span>
                            <h4 class="fw-bold mb-0">12</h4>
                        </div>
                        <span class="badge bg-primary">5 Menunggu</span>
                    </div>
                    
                    <a href="#" class="btn btn-success w-100">Ajukan Kompensasi Baru</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Tim Manajemen</h5>
                    <div class="table-responsive mt-3">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Posisi</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $teamMembers = \App\Models\User::whereIn('role', ['admin', 'staff'])
                                        ->where('kode_perusahaan', Auth::user()->kode_perusahaan)
                                        ->latest()
                                        ->take(5)
                                        ->get();
                                @endphp
                                
                                @forelse($teamMembers as $member)
                                <tr>
                                    <td>{{ $member->nama }}</td>
                                    <td><span class="badge bg-{{ $member->role == 'admin' ? 'primary' : 'info' }}">{{ ucfirst($member->role) }}</span></td>
                                    <td>{{ $member->email }}</td>
                                    <td>
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">
                                        <p class="text-muted mb-0">Belum ada anggota tim terdaftar</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default font family
    Chart.defaults.font.family = "'Poppins', 'Helvetica', 'sans-serif'";
    
    let emisiChart = null;
    let sumberEmisiChart = null;

    async function loadChartData() {
        try {
            const response = await fetch('/api/dashboard/emisi-chart?period=1Y', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            
            if (emisiChart) emisiChart.destroy();
            
            const ctx = document.getElementById('emisiChart').getContext('2d');
            emisiChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Total Emisi (Ton CO₂e)',
                        data: data.data.map(value => value / 1000), // Convert to tons
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.parsed.y.toFixed(2)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error loading chart data:', error);
        }
    }

    async function loadCategoryData() {
        try {
            const response = await fetch('/api/dashboard/emisi-by-category', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            
            if (sumberEmisiChart) sumberEmisiChart.destroy();
            
            const ctx = document.getElementById('sumberEmisiChart').getContext('2d');
            sumberEmisiChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data.map(value => value / 1000), // Convert to tons
                        backgroundColor: data.colors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const percentage = data.percentages[context.dataIndex];
                                    return `${label}: ${value.toFixed(2)} Ton CO₂e (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        } catch (error) {
            console.error('Error loading category data:', error);
        }
    }

    // Initial load
    loadChartData();
    loadCategoryData();

    // Handle period changes
    const periodSelector = document.querySelector('.form-select');
    if (periodSelector) {
        periodSelector.addEventListener('change', loadChartData);
    }
});
</script>
@endsection

@section('styles')
<style>
    .border-left-primary {
        border-left: 4px solid #4e73df;
    }
    .border-left-success {
        border-left: 4px solid #28a745;
    }
    .border-left-info {
        border-left: 4px solid #36b9cc;
    }
    .border-left-warning {
        border-left: 4px solid #f6c23e;
    }
</style>
@endsection