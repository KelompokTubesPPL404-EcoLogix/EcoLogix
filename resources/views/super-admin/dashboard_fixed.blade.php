@extends('layouts.super-admin')

@section('title', 'Dashboard Super Admin')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card bg-gradient-success-light">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success p-3 me-3">
                        <i class="bi bi-person-check-fill text-white fs-4"></i>
                    </div>
                    <div>
                        <h4 class="card-title fw-bold text-success mb-1">Selamat Datang, {{ Auth::user()->nama }}!</h4>
                        <p class="text-muted mb-0">Anda login sebagai Super Admin dengan akses untuk mengelola semua perusahaan dalam sistem.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="card-title fw-bold"><i class="bi bi-bar-chart-fill text-success me-2"></i>Ringkasan Sistem</h5>
            </div>
            <div class="card-body">
                <div class="mt-2">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border-0 bg-success-subtle rounded-4 p-3 h-100">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-success p-2 me-3">
                                        <i class="bi bi-building text-white"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block">Total Perusahaan</span>
                                        <span class="fs-4 fw-bold text-success" id="total-perusahaan">{{ \App\Models\Perusahaan::count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-primary-subtle rounded-4 p-3 h-100">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary p-2 me-3">
                                        <i class="bi bi-person-badge text-white"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block">Total Manager</span>
                                        <span class="fs-4 fw-bold text-primary" id="total-manager">{{ \App\Models\User::where('role', 'manager')->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <div class="card border-0 bg-info-subtle rounded-4 p-3 h-100">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-info p-2 me-3">
                                        <i class="bi bi-person-gear text-white"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block">Total Admin</span>
                                        <span class="fs-4 fw-bold text-info" id="total-admin">{{ \App\Models\User::where('role', 'admin')->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-secondary-subtle rounded-4 p-3 h-100">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-secondary p-2 me-3">
                                        <i class="bi bi-people text-white"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block">Total Staff</span>
                                        <span class="fs-4 fw-bold text-secondary" id="total-staff">{{ \App\Models\User::where('role', 'staff')->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid mt-4">
                    <a href="{{ route('superadmin.perusahaan.create') }}" class="btn btn-success rounded-pill shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Perusahaan Baru
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Visualisasi Emisi Karbon -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold"><i class="bi bi-cloud-fill text-success me-2"></i>Total Emisi Karbon</h5>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-success active period-btn" data-period="1M">1M</button>
                    <button type="button" class="btn btn-outline-success period-btn" data-period="3M">3M</button>
                    <button type="button" class="btn btn-outline-success period-btn" data-period="6M">6M</button>
                    <button type="button" class="btn btn-outline-success period-btn" data-period="1Y">1Y</button>
                    <button type="button" class="btn btn-outline-success period-btn" data-period="ALL">ALL</button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="emisiChart" width="400" height="250"></canvas>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <h6 class="mb-0">Total Emisi Karbon</h6>
                        <h4 class="text-success mb-0" id="totalEmisi">Loading...</h4>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success" id="total-approved">{{ \App\Models\EmisiKarbon::where('status', 'approved')->count() }}</span>
                        <span class="text-muted">Data Terverifikasi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold"><i class="bi bi-pie-chart-fill text-success me-2"></i>Distribusi Emisi Karbon</h5>
                <select class="form-select form-select-sm w-auto" id="chart-type-selector">
                    <option value="category">Berdasarkan Kategori</option>
                    <option value="company">Berdasarkan Perusahaan</option>
                </select>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="distributionChart" width="400" height="250"></canvas>
                </div>
                <div class="chart-legend mt-3">
                    <div class="d-flex flex-column" id="chart-legend-container">
                        <!-- Dynamic legend items will be inserted here by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="card-title fw-bold"><i class="bi bi-activity text-success me-2"></i>Statistik Persetujuan Emisi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center mb-4">
                            <div class="display-5 text-warning" id="pending-count">{{ \App\Models\EmisiKarbon::where('status', 'pending')->count() }}</div>
                            <div class="text-muted">Pending</div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-warning" id="pending-progress" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center mb-4">
                            <div class="display-5 text-success" id="approved-count">{{ \App\Models\EmisiKarbon::where('status', 'approved')->count() }}</div>
                            <div class="text-muted">Approved</div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-success" id="approved-progress" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center mb-4">
                            <div class="display-5 text-danger" id="rejected-count">{{ \App\Models\EmisiKarbon::where('status', 'rejected')->count() }}</div>
                            <div class="text-muted">Rejected</div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-danger" id="rejected-progress" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold mb-0"><i class="bi bi-building-fill text-success me-2"></i>Perusahaan Terdaftar Terbaru</h5>
                <a href="{{ route('superadmin.perusahaan.index') }}" class="btn btn-sm btn-outline-success rounded-pill">
                    <i class="bi bi-grid me-1"></i>Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive mt-2">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Nama Perusahaan</th>
                                <th class="border-0">Email</th>
                                <th class="border-0">Tanggal Daftar</th>
                                <th class="border-0">Total Emisi</th>
                                <th class="border-0 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Perusahaan::latest()->take(5)->get() as $perusahaan)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success-subtle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px">
                                            <i class="bi bi-building text-success"></i>
                                        </div>
                                        <span class="fw-medium">{{ $perusahaan->nama_perusahaan }}</span>
                                    </div>
                                </td>
                                <td>{{ $perusahaan->email_perusahaan }}</td>
                                <td><span class="badge bg-light text-dark">{{ $perusahaan->created_at->format('d M Y') }}</span></td>
                                <td>
                                    @php
                                        $totalEmisi = \App\Models\EmisiKarbon::where('kode_perusahaan', $perusahaan->kode_perusahaan)
                                            ->where('status', 'approved')
                                            ->sum('kadar_emisi_karbon');
                                    @endphp
                                    <span class="badge bg-success">{{ number_format($totalEmisi, 2) }} kg CO₂e</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('superadmin.perusahaan.show', $perusahaan->kode_perusahaan) }}" class="btn btn-sm btn-outline-success rounded-circle">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">Belum ada perusahaan terdaftar</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
        // Card animations
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.transition = 'all 0.3s ease';
                card.style.transform = 'translateY(0)';
                card.style.opacity = '1';
            }, 100 * index);
        });
        
        // Chart instances
        let emissionChart = null;
        let distributionChart = null;
        
        // Default period and chart type
        let currentPeriod = '1M';
        let chartType = 'category';
        
        // Fetch emission data for time series chart
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
                
                // Update total emission text
                const totalEmisi = data.data.reduce((total, value) => total + value, 0);
                document.getElementById('totalEmisi').textContent = `${totalEmisi.toLocaleString('id-ID')} kg CO₂e`;
            })
            .catch(error => {
                console.error('Error fetching emission data:', error);
                document.getElementById('totalEmisi').textContent = 'Error loading data';
            });
        }
        
        // Update emission time series chart
        function updateEmissionChart(data) {
            if (emissionChart) {
                emissionChart.destroy();
            }
            
            const ctx = document.getElementById('emisiChart').getContext('2d');
            emissionChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Emisi Karbon (kg CO₂e)',
                        data: data.data,
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderColor: '#28a745',
                        borderWidth: 2,
                        pointBackgroundColor: '#28a745',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${parseFloat(context.parsed.y).toLocaleString('id-ID')} kg CO₂e`;
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
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
        
        // Fetch distribution data (by category or company)
        function fetchDistributionData(type) {
            const endpoint = type === 'category' ? '{{ route("api.emisi.category") }}' : '/api/emisi/by-company';
            
            fetch(endpoint, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateDistributionChart(data, type);
                updateDistributionLegend(data);
            })
            .catch(error => {
                console.error(`Error fetching ${type} distribution data:`, error);
            });
        }
        
        // Update distribution chart (donut chart)
        function updateDistributionChart(data, type) {
            if (distributionChart) {
                distributionChart.destroy();
            }
            
            const ctx = document.getElementById('distributionChart').getContext('2d');
            distributionChart = new Chart(ctx, {
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
                    cutout: '65%',
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
                                    return `${context.label}: ${value.toLocaleString('id-ID')} kg CO₂e (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Update distribution legend
        function updateDistributionLegend(data) {
            const legendContainer = document.getElementById('chart-legend-container');
            if (!legendContainer) return;
            
            legendContainer.innerHTML = '';
            
            data.labels.forEach((label, index) => {
                const percentage = data.percentages ? data.percentages[index] : Math.round((data.data[index] / data.data.reduce((a, b) => a + b, 0)) * 100);
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
        
        // Fetch and display approval statistics
        function fetchApprovalStats() {
            fetch('{{ route("api.dashboard.stats") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateApprovalStats(data);
            })
            .catch(error => {
                console.error('Error fetching approval stats:', error);
            });
        }
        
        // Update approval statistics
        function updateApprovalStats(data) {
            // Get elements
            const pendingCount = document.getElementById('pending-count');
            const approvedCount = document.getElementById('approved-count');
            const rejectedCount = document.getElementById('rejected-count');
            
            const pendingProgress = document.getElementById('pending-progress');
            const approvedProgress = document.getElementById('approved-progress');
            const rejectedProgress = document.getElementById('rejected-progress');
            
            // Update counts
            pendingCount.textContent = data.pending_count;
            approvedCount.textContent = data.approved_count;
            rejectedCount.textContent = data.rejected_count;
            
            // Calculate total
            const total = data.pending_count + data.approved_count + data.rejected_count;
            
            // Update progress bars
            if (total > 0) {
                const pendingPercentage = (data.pending_count / total) * 100;
                const approvedPercentage = (data.approved_count / total) * 100;
                const rejectedPercentage = (data.rejected_count / total) * 100;
                
                pendingProgress.style.width = `${pendingPercentage}%`;
                approvedProgress.style.width = `${approvedPercentage}%`;
                rejectedProgress.style.width = `${rejectedPercentage}%`;
            }
        }
        
        // Initialize dashboard with data
        fetchEmissionData(currentPeriod);
        fetchDistributionData(chartType);
        fetchApprovalStats();
        
        // Add event listeners for period buttons
        const periodButtons = document.querySelectorAll('.period-btn');
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
        
        // Add event listener for chart type selector
        document.getElementById('chart-type-selector').addEventListener('change', function() {
            chartType = this.value;
            fetchDistributionData(chartType);
        });
    });
</script>

<style>
    /* Dashboard styles */
    .card {
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        transform: translateY(20px);
        opacity: 0;
    }
    
    .card-header {
        border-bottom: none;
    }
    
    .bg-gradient-success-light {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.05) 100%);
    }
    
    .bg-success-subtle {
        background-color: rgba(40, 167, 69, 0.1);
    }
    
    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .bg-info-subtle {
        background-color: rgba(13, 202, 240, 0.1);
    }
    
    .bg-secondary-subtle {
        background-color: rgba(108, 117, 125, 0.1);
    }
    
    .rounded-4 {
        border-radius: 0.75rem;
    }
    
    .legend-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 8px;
    }
    
    .btn-group .btn {
        border-radius: 20px;
        margin: 0 2px;
    }
    
    .chart-container {
        position: relative;
    }
</style>
@endsection
