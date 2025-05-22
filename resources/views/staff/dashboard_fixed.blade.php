@extends('layouts.staff')

@section('title', 'Dashboard Staff')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title fw-bold text-success">Selamat Datang, {{ Auth::user()->nama }}!</h4>
                <p class="text-muted">Anda login sebagai Staff untuk perusahaan {{ Auth::user()->perusahaan->nama_perusahaan ?? 'Unknown' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-bold mb-0">Emisi Carbon</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-success period-btn active" data-period="1M">1M</button>
                        <button class="btn btn-outline-success period-btn" data-period="3M">3M</button>
                        <button class="btn btn-outline-success period-btn" data-period="6M">6M</button>
                        <button class="btn btn-outline-success period-btn" data-period="1Y">1Y</button>
                    </div>
                </div>
                <div class="position-relative" style="height: 300px;">
                    <div class="position-absolute top-0 start-0 bg-light-success text-success rounded-pill px-2 py-1 small fw-medium">
                        <span id="comparison-text">Loading...</span> vs periode sebelumnya
                    </div>
                    <canvas id="emissionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="d-flex flex-column gap-4">
            <!-- Input Button Card -->
            <div class="card bg-success text-white">
                <div class="card-body d-flex flex-column align-items-center text-center py-4">
                    <div class="icon-circle bg-white text-success mb-3">
                        <i class="bi bi-plus-lg"></i>
                    </div>
                    <h5 class="card-title mb-3">Input Emisi Carbon</h5>
                    <p class="card-text small">Tambahkan data emisi karbon baru untuk perusahaan Anda.</p>
                    <a href="{{ route('staff.emisicarbon.create') }}" class="btn btn-light btn-sm">Input Now</a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="card">
                <div class="card-body">
                    @php
                        $totalEmisi = \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)
                            ->sum('kadar_emisi_karbon');
                            
                        $totalInputs = \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)
                            ->count();
                            
                        $pendingCount = \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)
                            ->where('status', 'pending')
                            ->count();
                            
                        $approvedCount = \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)
                            ->where('status', 'approved')
                            ->count();
                            
                        $rejectedCount = \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)
                            ->where('status', 'rejected')
                            ->count();
                    @endphp
                    <h5 class="card-title fw-bold mb-3">Statistik</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Emisi</span>
                        <span class="fw-bold">{{ number_format($totalEmisi, 2) }} kg CO₂e</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Input</span>
                        <span class="fw-bold">{{ $totalInputs }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Status</span>
                        <div>
                            <span class="badge bg-warning me-1">{{ $pendingCount }}</span>
                            <span class="badge bg-success me-1">{{ $approvedCount }}</span>
                            <span class="badge bg-danger">{{ $rejectedCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel dan Chart Donut -->
<div class="row mt-4">
    <!-- Input Emission History Table -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-3">Riwayat Input Emisi</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Sub Kategori</th>
                                <th>Emisi (kg CO₂e)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $latestEmisi = \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($latestEmisi as $emisi)
                                <tr>
                                    <td>{{ $emisi->tanggal_emisi->format('d/m/Y') }}</td>
                                    <td>{{ $emisi->kategori_emisi_karbon }}</td>
                                    <td>{{ $emisi->sub_kategori }}</td>
                                    <td>{{ number_format($emisi->kadar_emisi_karbon, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $emisi->status == 'approved' ? 'success' : ($emisi->status == 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($emisi->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">Belum ada data emisi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('staff.emisicarbon.index') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <h5 class="card-title fw-bold mb-0">Tipe Emisi</h5>
                    <p class="text-muted small">Distribusi emisi berdasarkan kategori</p>
                </div>
                <div class="chart-container" style="height: 250px;">
                    <canvas id="emissionTypeChart"></canvas>
                </div>
                <div class="chart-legend mt-3">
                    <div class="d-flex flex-column">
                        <!-- Dynamic legend items will be inserted here by JavaScript -->
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
                
                // Update comparison text
                document.getElementById('comparison-text').textContent = 
                    `${data.comparison > 0 ? '+' : ''}${data.comparison}%`;
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
            
            const ctx = document.getElementById('emissionChart').getContext('2d');
            emissionChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Emisi Karbon (kg CO₂e)',
                        data: data.data,
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        borderColor: '#28a745',
                        borderWidth: 2,
                        pointBackgroundColor: '#28a745',
                        tension: 0.3,
                        fill: true
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
            
            const ctx = document.getElementById('emissionTypeChart').getContext('2d');
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
        
        // Initialize dashboard
        fetchEmissionData(currentPeriod);
        fetchCategoryData();
        
        // Add event listeners for period buttons
        const periodButtons = document.querySelectorAll('.period-btn');
        periodButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update UI
                periodButtons.forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                
                // Get new period and fetch data
                currentPeriod = this.dataset.period;
                fetchEmissionData(currentPeriod);
            });
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
    .bg-light-success {
        background-color: rgba(40, 167, 69, 0.1);
    }
    .icon-circle {
        width: 3rem;
        height: 3rem;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .icon-circle i {
        font-size: 1.25rem;
    }
</style>
@endsection
