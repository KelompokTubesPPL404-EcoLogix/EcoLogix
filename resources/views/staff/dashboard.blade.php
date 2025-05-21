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
                        <span id="comparison-text">{{ \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->whereMonth('tanggal_emisi', now()->month)->count() > 0 ? (\App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->whereMonth('tanggal_emisi', now()->month)->sum('kadar_emisi_karbon') - \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->whereMonth('tanggal_emisi', now()->subMonth()->month)->sum('kadar_emisi_karbon')) > 0 ? '+' : '' : '0' }}{{ \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->whereMonth('tanggal_emisi', now()->subMonth()->month)->sum('kadar_emisi_karbon') > 0 ? number_format((\App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->whereMonth('tanggal_emisi', now()->month)->sum('kadar_emisi_karbon') - \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->whereMonth('tanggal_emisi', now()->subMonth()->month)->sum('kadar_emisi_karbon')) / \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->whereMonth('tanggal_emisi', now()->subMonth()->month)->sum('kadar_emisi_karbon') * 100, 0) : '0' }}% vs bulan lalu</span>
                    </div>
                    <canvas id="emissionChart"></canvas>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Ambil token CSRF dari meta tag
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        // Fungsi untuk mengambil data chart emisi karbon
                        async function loadChartData(period = '1M') {
                            try {
                                // Show loading state
                                document.getElementById('emissionChart').style.opacity = '0.5';
                                
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
                                if (window.emissionChart) {
                                    window.emissionChart.data.labels = data.labels;
                                    window.emissionChart.data.datasets[0].data = data.data;
                                    window.emissionChart.update();
                                } else {
                                    const ctx = document.getElementById('emissionChart').getContext('2d');
                                    window.emissionChart = new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels: data.labels,
                                            datasets: [{
                                                label: 'Emisi Karbon (kg CO2e)',
                                                data: data.data,
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
                                        }
                                    });
                                }
                                
                                // Remove loading state
                                document.getElementById('emissionChart').style.opacity = '1';
                                
                            } catch (error) {
                                console.error('Error loading chart data:', error);
                                document.getElementById('emissionChart').style.opacity = '1';
                            }
                        }
                        
                        // Period selector event listeners
                        const periodButtons = document.querySelectorAll('.period-btn');
                        let activePeriod = '1M';
                        
                        periodButtons.forEach(button => {
                            button.addEventListener('click', function() {
                                // Remove active class from all buttons
                                periodButtons.forEach(btn => btn.classList.remove('active'));
                                
                                // Add active class to clicked button
                                this.classList.add('active');
                                
                                // Get period from data attribute
                                activePeriod = this.getAttribute('data-period');
                                
                                // Load chart data for selected period
                                loadChartData(activePeriod);
                            });
                        });
                        
                        // Load chart data for default period
                        loadChartData('1M');
                    });
                    </script>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="d-flex flex-column gap-4">
            <!-- Input Button Card -->
            <div class="card mb-0">
                <div class="card-body">
                    <a href="{{ route('staff.emisicarbon.create') }}" class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-plus-circle"></i>
                        Input Emisi Carbon
                    </a>
                </div>
            </div>
            
            <!-- Total Carbon Emission Card -->
            <div class="card mb-0">
                <div class="card-body">
                    <p class="fs-3 fw-bold mb-0">{{ number_format(\App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->where('status', 'approved')->sum('kadar_emisi_karbon'), 2) }} <span class="fs-6">ton CO₂e</span></p>
                    <p class="text-muted small">Total Carbon Emission</p>
                    <p class="text-success small d-flex align-items-center">
                        <i class="bi bi-graph-up me-1"></i>
                        <span id="monthly-change">{{ \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->where('status', 'approved')->whereMonth('tanggal_emisi', now()->subMonth()->month)->sum('kadar_emisi_karbon') > 0 ? number_format((\App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->where('status', 'approved')->whereMonth('tanggal_emisi', now()->month)->sum('kadar_emisi_karbon') - \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->where('status', 'approved')->whereMonth('tanggal_emisi', now()->subMonth()->month)->sum('kadar_emisi_karbon')) / \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->where('status', 'approved')->whereMonth('tanggal_emisi', now()->subMonth()->month)->sum('kadar_emisi_karbon') * 100, 2) : '0' }}% sejak bulan lalu</span>
                    </p>
                </div>
            </div>
            
            <!-- Input Total Card -->
            <div class="card mb-0">
                <div class="card-body">
                    <p class="fs-4 fw-semibold mb-0">{{ \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->count() }}</p>
                    <p class="text-muted small">Total Input</p>
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title fw-bold mb-0">Riwayat Input Emisi</h5>
                    <a href="{{ route('staff.emisicarbon.index') }}" class="text-success">Lihat Semua</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kategori Emisi</th>
                                <th>Sub Kategori</th>
                                <th>Nilai Aktivitas</th>
                                <th>Tingkat Emisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $emisiData = \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)
                                    ->latest()
                                    ->take(6)
                                    ->get();
                            @endphp
                            
                            @forelse($emisiData as $index => $emisi)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $emisi->tanggal_emisi ? \Carbon\Carbon::parse($emisi->tanggal_emisi)->format('d/m/y') : $emisi->created_at->format('d/m/y') }}</td>
                                <td>{{ $emisi->kategori_emisi_karbon }}</td>
                                <td>{{ $emisi->sub_kategori }}</td>
                                <td>{{ $emisi->nilai_aktivitas }} {{ $emisi->faktorEmisi->satuan ?? 'unit' }}</td>
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

    <!-- Emission Type Chart -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <h5 class="card-title fw-bold mb-0">Tipe Emisi</h5>
                    <p class="text-muted small">{{ now()->format('F Y') }}</p>
                </div>
                
                <div class="d-flex justify-content-center py-2" style="height: 200px;">
                    <canvas id="donutChart"></canvas>
                </div>
                
                <div class="mt-3 small">
                    <div class="d-flex align-items-center mb-1">
                        <span class="d-inline-block bg-success rounded-circle me-2" style="width: 12px; height: 12px;"></span>
                        <span>Produk - <span id="product-percentage">{{ \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->where('kategori_emisi_karbon', 'Produk')->count() > 0 ? number_format(\App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->where('kategori_emisi_karbon', 'Produk')->count() / \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->count() * 100, 0) : '0' }}%</span></span>
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <span class="d-inline-block bg-info rounded-circle me-2" style="width: 12px; height: 12px;"></span>
                        <span>Energi - <span id="energy-percentage">{{ \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->where('kategori_emisi_karbon', 'Energi')->count() > 0 ? number_format(\App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->where('kategori_emisi_karbon', 'Energi')->count() / \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->count() * 100, 0) : '0' }}%</span></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="d-inline-block bg-primary rounded-circle me-2" style="width: 12px; height: 12px;"></span>
                        <span>Transportasi - <span id="transport-percentage">{{ \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->where('kategori_emisi_karbon', 'Transportasi')->count() > 0 ? number_format(\App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->where('kategori_emisi_karbon', 'Transportasi')->count() / \App\Models\EmisiKarbon::where('kode_staff', Auth::user()->kode_user)->count() * 100, 0) : '0' }}%</span></span>
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
                document.querySelector('.fs-3.fw-bold').innerHTML = 
                    `${parseFloat(data.total_emisi).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2})} <span class="fs-6">ton CO₂e</span>`;
                
                // Update total input
                document.querySelector('.fs-4.fw-semibold').textContent = data.total_input;
                
            } catch (error) {
                console.error('Error fetching dashboard stats:', error);
            }
        }
        
        // Initialize Emission Chart
        const emissionChart = new Chart(document.getElementById('emissionChart'), {
            type: 'line',
            data: {
                labels: [], // Empty initially, will be populated
                datasets: [{
                    label: 'Carbon Emission',
                    data: [], // Empty initially
                    backgroundColor: 'rgba(25, 135, 84, 0.2)',
                    borderColor: '#198754',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: false,
                        grid: {
                            display: true,
                            drawBorder: false
                        },
                        ticks: {
                            display: false
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

        // Period buttons functionality
        const periodBtns = document.querySelectorAll('.period-btn');
        let activePeriod = '1M'; // Default active period
        
        // Initialize with default period
        loadChartData(activePeriod);
        
        // Set up button event listeners
        periodBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const period = this.getAttribute('data-period');
                if (period !== activePeriod) {
                    setActivePeriod(period);
                    loadChartData(period);
                }
            });
        });

        // Set active period and update UI
        function setActivePeriod(period) {
            activePeriod = period;
            periodBtns.forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('data-period') === period) {
                    btn.classList.add('active');
                }
            });
        }

        // Load chart data for selected period
        async function loadChartData(period) {
            try {
                // Show loading state
                document.getElementById('emissionChart').style.opacity = '0.5';
                
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
                
                // Update chart
                emissionChart.data.labels = data.labels;
                emissionChart.data.datasets[0].data = data.data;
                emissionChart.update();
                
                // Update comparison text
                const comparisonValue = data.comparison;
                const comparisonPrefix = comparisonValue >= 0 ? '+' : '';
                const comparisonText = `${comparisonPrefix}${comparisonValue}% vs `;
                
                let periodText = '';
                switch(period) {
                    case '1M': periodText = 'bulan lalu'; break;
                    case '3M': periodText = 'kuartal lalu'; break;
                    case '6M': periodText = 'semester lalu'; break;
                    case '1Y': periodText = 'tahun lalu'; break;
                }
                
                document.getElementById('comparison-text').textContent = comparisonText + periodText;
                document.getElementById('monthly-change').textContent = `${comparisonPrefix}${comparisonValue}% sejak ${periodText}`;
                
            } catch (error) {
                console.error('Error loading chart data:', error);
            } finally {
                document.getElementById('emissionChart').style.opacity = '1';
            }
        }

        // Initialize Donut Chart
        const donutChart = new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: [],
                datasets: [{
                    label: 'Tipe Emisi',
                    data: [],
                    backgroundColor: [],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: false 
                    }
                },
                cutout: '75%'
            }
        });
        
        // Load category data
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
                
                // Update donut chart
                donutChart.data.labels = data.labels;
                donutChart.data.datasets[0].data = data.data;
                donutChart.data.datasets[0].backgroundColor = data.colors;
                donutChart.update();
                
                // Update percentages in legend
                if (data.labels.length > 0) {
                    // Perbarui persentase di legend
                    const legendElements = document.querySelectorAll('.d-flex.align-items-center span:last-child');
                    const maxCategories = Math.min(data.labels.length, 3); // Maksimal 3 kategori yang ditampilkan
                    
                    for (let i = 0; i < maxCategories; i++) {
                        if (legendElements[i]) {
                            legendElements[i].textContent = `${data.labels[i]} - ${data.percentages[i]}%`;
                        }
                    }
                }
                
            } catch (error) {
                console.error('Error loading category data:', error);
            }
        }
        
        // Panggil fungsi untuk memuat data
        fetchDashboardStats();
        loadCategoryData();
    });
</script>
@endsection