@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title fw-bold text-success">Selamat Datang, {{ Auth::user()->nama }}!</h4>
                    <p class="text-muted">Anda login sebagai Admin untuk perusahaan {{ Auth::user()->perusahaan->nama_perusahaan ?? 'Unknown' }}</p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Emission Carbon</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="text-success">
                            <span id="comparison-text">Loading...</span> vs periode sebelumnya
                        </div>
                        <div class="period-selector">
                            <button class="btn btn-sm btn-success active" data-period="1M">1M</button>
                            <button class="btn btn-sm btn-outline-success" data-period="3M">3M</button>
                            <button class="btn btn-sm btn-outline-success" data-period="6M">6M</button>
                            <button class="btn btn-sm btn-outline-success" data-period="1Y">1Y</button>
                        </div>
                    </div>
                    <div class="chart-container" style="height: 300px; width: 100%;">
                        <canvas id="emisiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Approved Carbon Emission</h5>
                    <a href="#" class="text-success">See All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>no</th>
                                    <th>date</th>
                                    <th>emission category</th>
                                    <th>sub category</th>
                                    <th>activity score</th>
                                    <th>emission levels</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $emisiCarbons = \App\Models\EmisiKarbon::where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')
                                        ->where('status', 'approved')
                                        ->latest()
                                        ->take(6)
                                        ->get();
                                    
                                    $pendingCount = \App\Models\EmisiKarbon::where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')
                                        ->where('status', 'pending')
                                        ->count();
                                @endphp
                                @forelse($emisiCarbons as $index => $emisi)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($emisi->tanggal_emisi)->format('d/m/y') }}</td>
                                    <td>{{ $emisi->kategori_emisi_karbon }}</td>
                                    <td>{{ $emisi->sub_kategori }}</td>
                                    <td>{{ $emisi->nilai_aktivitas }} kg</td>
                                    <td>{{ $emisi->kadar_emisi_karbon }} kg CO₂e</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">
                                        <p class="text-muted mb-0">Belum ada data emisi tercatat</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    @php
                        $totalEmisi = \App\Models\EmisiKarbon::where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')
                            ->where('status', 'approved')
                            ->sum('kadar_emisi_karbon');
                            
                        $currentMonthEmisi = \App\Models\EmisiKarbon::where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')
                            ->where('status', 'approved')
                            ->whereMonth('tanggal_emisi', now()->month)
                            ->sum('kadar_emisi_karbon');
                            
                        $lastMonthEmisi = \App\Models\EmisiKarbon::where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')
                            ->where('status', 'approved')
                            ->whereMonth('tanggal_emisi', now()->subMonth()->month)
                            ->sum('kadar_emisi_karbon');
                            
                        $percentChange = 0;
                        if ($lastMonthEmisi > 0) {
                            $percentChange = round((($currentMonthEmisi - $lastMonthEmisi) / $lastMonthEmisi) * 100, 0);
                        }
                    @endphp
                    <h3 class="mb-1">{{ number_format($totalEmisi, 2) }} kg CO₂e</h3>
                    <p class="text-muted">Total Carbon Emission</p>
                    <div class="d-flex mb-3">
                        <span class="badge {{ $percentChange >= 0 ? 'bg-danger' : 'bg-success' }} me-2">{{ $percentChange >= 0 ? '+' : '' }}{{ $percentChange }}%</span>
                        <small class="text-muted">sejak bulan lalu</small>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="mb-1">{{ $pendingCount }}</h3>
                    <p class="text-muted">Pending Approved</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Emission Type</h5>
                    <p class="text-muted small mb-0">{{ now()->format('F Y') }}</p>
                </div>
                <div class="card-body">
                    <div class="donut-chart-container" style="height: 200px;">
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
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Charts instances
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
            
            const ctx = document.getElementById('emisiChart').getContext('2d');
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
</style>
@endsection
