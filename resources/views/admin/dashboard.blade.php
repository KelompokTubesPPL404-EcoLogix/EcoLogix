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
                            <span>+23%</span> vs bulan lalu
                        </div>
                        <div class="period-selector">
                            <button class="btn btn-sm">1M</button>
                            <button class="btn btn-sm">3M</button>
                            <button class="btn btn-sm">6M</button>
                            <button class="btn btn-sm">1Y</button>
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
                    @endphp
                    <h3 class="mb-1">{{ number_format($totalEmisi, 2) }} kg CO₂e</h3>
                    <p class="text-muted">Total Carbon Emission</p>
                    <div class="d-flex mb-3">
                        <span class="badge bg-success me-2">+20%</span>
                        <small class="text-muted">since last month</small>
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
                    <a href="#" class="text-success">View Report</a>
                </div>
                <div class="card-body">
                    <div class="donut-chart-container">
                        <canvas id="emissionTypeChart" height="180"></canvas>
                    </div>
                    <div class="chart-legend mt-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="legend-dot bg-success"></span> product
                                <div>40%</div>
                            </div>
                            <div>
                                <span class="legend-dot bg-primary"></span> shipment
                                <div>32%</div>
                            </div>
                            <div>
                                <span class="legend-dot bg-warning"></span> growth
                                <div>28%</div>
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
        // Ambil token CSRF dari meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Fungsi untuk mengambil data dashboard
        async function fetchDashboardStats() {
            try {
                const response = await fetch('/api/dashboard/stats', {
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
                
                // Update total emisi
                document.querySelector('h3.mb-1').innerHTML = 
                    `${parseFloat(data.total_emisi).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2})} kg CO₂e`;
                
                // Update pending count
                document.querySelectorAll('h3.mb-1')[1].textContent = data.pending_count;
                
            } catch (error) {
                console.error('Error fetching dashboard stats:', error);
            }
        }
        
        // Period buttons functionality
        const periodBtns = document.querySelectorAll('.period-selector button');
        let activePeriod = '1M'; // Default active period
        
        // Set up button event listeners
        periodBtns.forEach((btn, index) => {
            // Tambahkan class active ke button pertama
            if (index === 0) btn.classList.add('btn-success', 'active');
            else btn.classList.add('btn-outline-success');
            
            btn.addEventListener('click', function() {
                const periods = ['1M', '3M', '6M', '1Y'];
                const period = periods[index];
                
                if (period !== activePeriod) {
                    activePeriod = period;
                    
                    // Update UI
                    periodBtns.forEach((b, i) => {
                        if (i === index) {
                            b.classList.remove('btn-outline-success');
                            b.classList.add('btn-success', 'active');
                        } else {
                            b.classList.remove('btn-success', 'active');
                            b.classList.add('btn-outline-success');
                        }
                    });
                    
                    loadChartData(period);
                }
            });
        });
        
        // Fungsi untuk memuat data chart emisi karbon
        async function loadChartData(period = '1M') {
            try {
                // Show loading state
                document.querySelector('.chart-container').style.opacity = '0.5';
                
                // Fetch data from API
                const response = await fetch(`/api/dashboard/emisi-chart?period=${period}`, {
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
                
                // Create or update chart
                if (window.emisiChart) {
                    window.emisiChart.data.labels = data.labels;
                    window.emisiChart.data.datasets[0].data = data.data;
                    window.emisiChart.update();
                } else {
                    const ctx = document.getElementById('emisiChart').getContext('2d');
                    window.emisiChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Emisi Karbon (kg CO2e)',
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
                                            return `${context.parsed.y.toLocaleString('id-ID')} kg CO₂e`;
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
                
                // Update comparison text
                const comparisonValue = data.comparison;
                const comparisonPrefix = comparisonValue >= 0 ? '+' : '';
                const comparisonText = `${comparisonPrefix}${comparisonValue}%`;
                
                let periodText = '';
                switch(period) {
                    case '1M': periodText = 'bulan lalu'; break;
                    case '3M': periodText = 'kuartal lalu'; break;
                    case '6M': periodText = 'semester lalu'; break;
                    case '1Y': periodText = 'tahun lalu'; break;
                }
                
                document.querySelector('.text-success span').textContent = comparisonText;
                document.querySelector('.text-success').innerHTML = 
                    `<span>${comparisonText}</span> vs ${periodText}`;
                
                // Remove loading state
                document.querySelector('.chart-container').style.opacity = '1';
                
            } catch (error) {
                console.error('Error loading chart data:', error);
                document.querySelector('.chart-container').style.opacity = '1';
            }
        }
        
        // Fungsi untuk memuat data kategori emisi
        async function loadCategoryData() {
            try {
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
                
                // Create or update donut chart
                if (window.typeChart) {
                    window.typeChart.data.labels = data.labels;
                    window.typeChart.data.datasets[0].data = data.data;
                    window.typeChart.data.datasets[0].backgroundColor = data.colors;
                    window.typeChart.update();
                } else {
                    const ctx = document.getElementById('emissionTypeChart').getContext('2d');
                    window.typeChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.data,
                                backgroundColor: data.colors
                            }]
                        },
                        options: {
                            cutout: '70%',
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return `${context.label}: ${context.parsed.toLocaleString('id-ID')} kg CO₂e (${data.percentages[context.dataIndex]}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
                
                // Update percentages in legend
                if (data.labels.length > 0) {
                    // Perbarui persentase di legend
                    const legendContainer = document.querySelector('.chart-legend .d-flex');
                    legendContainer.innerHTML = ''; // Clear existing content
                    
                    const maxCategories = Math.min(data.labels.length, 3); // Maksimal 3 kategori yang ditampilkan
                    const colors = ['bg-success', 'bg-primary', 'bg-warning'];
                    
                    for (let i = 0; i < maxCategories; i++) {
                        const legendItem = document.createElement('div');
                        legendItem.innerHTML = `
                            <span class="legend-dot ${colors[i]}"></span> ${data.labels[i].toLowerCase()}
                            <div>${data.percentages[i]}%</div>
                        `;
                        legendContainer.appendChild(legendItem);
                    }
                }
                
            } catch (error) {
                console.error('Error loading category data:', error);
            }
        }
        
        // Panggil fungsi untuk memuat data
        fetchDashboardStats();
        loadChartData(activePeriod);
        loadCategoryData();
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
        margin-right: 5px;
    }
</style>
@endsection