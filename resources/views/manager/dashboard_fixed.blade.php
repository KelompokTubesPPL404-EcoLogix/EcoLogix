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
            <select class="form-select form-select-sm d-inline-block w-auto" id="month-selector">
                @php
                    $current_month = now()->month;
                    $current_year = now()->year;
                    for ($i = 0; $i < 12; $i++) {
                        $date = now()->subMonths($i);
                        $monthName = $date->format('F Y');
                        $selected = ($i == 0) ? 'selected' : '';
                        echo "<option value=\"{$date->format('Y-m')}\" {$selected}>{$monthName}</option>";
                    }
                @endphp
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-emisi">
                                {{ number_format(\App\Models\EmisiKarbon::where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')->where('status', 'approved')->sum('kadar_emisi_karbon') / 1000, 2) }} Ton CO<sub>2</sub>e
                            </div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="compensated-emisi">
                                {{ number_format(\App\Models\KompensasiEmisiCarbon::whereHas('emisiCarbon', function($q) { $q->where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')->where('status', 'approved'); })->sum('jumlah_kompensasi') / 1000, 2) }} Ton CO<sub>2</sub>e
                            </div>
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
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="compensation-percentage">{{ $persentaseKompensasi }}%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-success" id="compensation-progress" role="progressbar" style="width: {{ $persentaseKompensasi }}%"
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="available-credits">
                                {{ number_format(\App\Models\PembelianCarbonCredit::whereHas('penyedia', function($q) { $q->where('kode_perusahaan', Auth::user()->kode_perusahaan); })->sum('jumlah_kompensasi'), 0) }} Kredit
                            </div>
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
                    <div class="period-selector">
                        <button class="btn btn-sm btn-success active" data-period="1M">1M</button>
                        <button class="btn btn-sm btn-outline-success" data-period="3M">3M</button>
                        <button class="btn btn-sm btn-outline-success" data-period="6M">6M</button>
                        <button class="btn btn-sm btn-outline-success" data-period="1Y">1Y</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="emisiChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Sumber Emisi</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="sumberEmisiChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small chart-legend">
                        <div class="d-flex flex-column">
                            <!-- Dynamic legend items will be inserted here by JavaScript -->
                        </div>
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
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="text-center mb-4">
                                <div class="text-success display-4">
                                    {{ \App\Models\User::where('role', 'admin')->where('kode_perusahaan', Auth::user()->kode_perusahaan)->count() }}
                                </div>
                                <div class="text-muted">Admin</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center mb-4">
                                <div class="text-success display-4">
                                    {{ \App\Models\User::where('role', 'staff')->where('kode_perusahaan', Auth::user()->kode_perusahaan)->count() }}
                                </div>
                                <div class="text-muted">Staff</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-2">
                        <a href="{{ route('manager.admin.index') }}" class="btn btn-sm btn-outline-success me-2">Kelola Admin</a>
                        <a href="{{ route('manager.staff.index') }}" class="btn btn-sm btn-outline-success">Kelola Staff</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Aktivitas Terbaru</h5>
                    <div class="list-group list-group-flush mt-3">
                        @php
                            $recentEmisi = \App\Models\EmisiKarbon::where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')
                                ->latest()
                                ->take(4)
                                ->get();
                        @endphp
                        @forelse($recentEmisi as $emisi)
                            <div class="list-group-item px-0">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $emisi->kategori_emisi_karbon }} - {{ $emisi->sub_kategori }}</h6>
                                    <small class="text-muted">{{ $emisi->tanggal_emisi->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-muted small">{{ $emisi->kadar_emisi_karbon }} kg CO₂e - oleh {{ $emisi->staff->nama ?? 'Staff' }}</p>
                                <small class="text-{{ $emisi->status == 'approved' ? 'success' : ($emisi->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ $emisi->status == 'approved' ? 'Approved' : ($emisi->status == 'rejected' ? 'Rejected' : 'Pending') }}
                                </small>
                            </div>
                        @empty
                            <div class="list-group-item px-0">
                                <p class="mb-1 text-center text-muted">Belum ada aktivitas</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Status Approval Emisi Karbon</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                <div class="display-5 text-warning">{{ $pendingCount }}</div>
                                <div class="text-muted">Pending</div>
                                <div class="progress mt-2" style="height: 5px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pendingCount + $approvedCount + $rejectedCount > 0 ? ($pendingCount / ($pendingCount + $approvedCount + $rejectedCount) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                <div class="display-5 text-success">{{ $approvedCount }}</div>
                                <div class="text-muted">Approved</div>
                                <div class="progress mt-2" style="height: 5px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pendingCount + $approvedCount + $rejectedCount > 0 ? ($approvedCount / ($pendingCount + $approvedCount + $rejectedCount) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                <div class="display-5 text-danger">{{ $rejectedCount }}</div>
                                <div class="text-muted">Rejected</div>
                                <div class="progress mt-2" style="height: 5px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $pendingCount + $approvedCount + $rejectedCount > 0 ? ($rejectedCount / ($pendingCount + $approvedCount + $rejectedCount) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
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
        // Chart instances
        let emissionChart = null;
        let categoryChart = null;
        
        // Default period
        let currentPeriod = '1M';
        
        // Fetch emission data by period
        function fetchEmissionData(period) {
            fetch(`{{ route('api.emisi.chart') }}?period=${period}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateEmissionChart(data);
            })
            .catch(error => {
                console.error('Error fetching emission data:', error);
            });
        }
        
        // Update emission chart with new data
        function updateEmissionChart(data) {
            if (emissionChart) {
                emissionChart.destroy();
            }
            
            const ctx = document.getElementById('emisiChart').getContext('2d');
            emissionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Emisi Karbon (kg CO₂e)',
                        data: data.data,
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: '#28a745',
                        borderWidth: 1,
                        borderRadius: 4,
                        barThickness: 'flex',
                        maxBarThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            callbacks: {
                                label: function(context) {
                                    return `${context.parsed.y.toFixed(2)} kg CO₂e`;
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Fetch category data for donut chart
        function fetchCategoryData() {
            fetch('{{ route("api.emisi.category") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateCategoryChart(data);
                updateCategoryLegends(data);
            })
            .catch(error => {
                console.error('Error fetching category data:', error);
            });
        }
        
        // Update category donut chart
        function updateCategoryChart(data) {
            if (categoryChart) {
                categoryChart.destroy();
            }
            
            const ctx = document.getElementById('sumberEmisiChart').getContext('2d');
            categoryChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: data.colors,
                        borderColor: 'white',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${context.label}: ${value.toFixed(2)} kg CO₂e (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Update category legends
        function updateCategoryLegends(data) {
            const legendContainer = document.querySelector('.chart-legend .d-flex');
            if (!legendContainer) return;
            
            legendContainer.innerHTML = '';
            
            data.labels.forEach((label, index) => {
                const percentage = data.percentages[index];
                const color = data.colors[index];
                
                const legendItem = document.createElement('div');
                legendItem.className = 'd-flex align-items-center mb-2';
                legendItem.innerHTML = `
                    <span class="legend-dot" style="background-color: ${color}"></span> ${label}
                    <div class="ms-auto">${percentage}%</div>
                `;
                
                legendContainer.appendChild(legendItem);
            });
        }
        
        // Fetch dashboard stats
        function fetchDashboardStats() {
            fetch('{{ route("api.dashboard.stats") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateDashboardStats(data);
            })
            .catch(error => {
                console.error('Error fetching dashboard stats:', error);
            });
        }
        
        // Update dashboard stats
        function updateDashboardStats(data) {
            // Update total emission
            document.getElementById('total-emisi').textContent = `${(data.total_emisi / 1000).toFixed(2)} Ton CO₂e`;
            
            // Update compensated emission
            document.getElementById('compensated-emisi').textContent = `${(data.compensated_emission / 1000).toFixed(2)} Ton CO₂e`;
            
            // Update compensation percentage
            const percentage = data.total_emisi > 0 ? Math.round((data.compensated_emission / data.total_emisi) * 100) : 0;
            document.getElementById('compensation-percentage').textContent = `${percentage}%`;
            
            // Update progress bar
            const progressBar = document.getElementById('compensation-progress');
            progressBar.style.width = `${percentage}%`;
            progressBar.setAttribute('aria-valuenow', percentage);
        }
        
        // Initialize dashboard
        fetchEmissionData(currentPeriod);
        fetchCategoryData();
        fetchDashboardStats();
        
        // Add event listeners for period buttons
        const periodButtons = document.querySelectorAll('.period-selector button');
        periodButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update UI
                periodButtons.forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-success');
                });
                this.classList.add('active');
                this.classList.remove('btn-outline-success');
                this.classList.add('btn-success');
                
                // Get new period and fetch data
                currentPeriod = this.dataset.period;
                fetchEmissionData(currentPeriod);
            });
        });
        
        // Add event listener for month selector
        document.getElementById('month-selector').addEventListener('change', function() {
            fetchDashboardStats();
        });
    });
</script>
@endsection

@section('styles')
<style>
    .legend-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 8px;
    }
    .period-selector .btn {
        border-radius: 20px;
        padding: 0.25rem 0.75rem;
    }
    .card-body {
        padding: 1.25rem;
    }
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .chart-area {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .chart-pie {
        position: relative;
        height: 250px;
        width: 100%;
    }
    .progress-sm {
        height: 0.5rem;
    }
</style>
@endsection
