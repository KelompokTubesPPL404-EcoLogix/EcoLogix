@extends('layouts.app')

@section('title', 'Dashboard EcoLogix')

@section('sidebar')
<ul class="nav flex-column">
  <li class="nav-item">
    <a href="#" class="nav-link active">
      <i class="bi bi-house-door me-2"></i> Dashboard
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="bi bi-calculator me-2"></i> Emisi Carbon
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="bi bi-bar-chart me-2"></i> Laporan
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="bi bi-gear me-2"></i> Pengaturan
    </a>
  </li>
</ul>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12 mb-4">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title fw-bold text-success">Dashboard EcoLogix</h4>
        <p class="text-muted">Sistem Manajemen Emisi Karbon</p>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="card-title fw-bold">Emisi Carbon</h5>
          <div class="btn-group">
            <button class="btn btn-sm btn-outline-success active" data-period="1M">1M</button>
            <button class="btn btn-sm btn-outline-success" data-period="3M">3M</button>
            <button class="btn btn-sm btn-outline-success" data-period="6M">6M</button>
            <button class="btn btn-sm btn-outline-success" data-period="1Y">1Y</button>
          </div>
        </div>
        <div class="position-relative" style="height: 300px;">
          <div class="position-absolute top-0 start-0 badge bg-success">
            +23% vs bulan lalu
          </div>
          <canvas id="emissionChart"></canvas>
        </div>
      </div>
    </div>
    
    <div class="card mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="card-title fw-bold">Riwayat Input Emisi</h5>
          <a href="{{ url('/history') }}" class="text-success">Lihat Semua</a>
        </div>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kategori Emisi</th>
                <th>Sub Kategori</th>
                <th>Skor Aktivitas</th>
                <th>Level Emisi</th>
              </tr>
            </thead>
            <tbody>
              @for($i = 1; $i <= 6; $i++)
              <tr>
                <td>{{ $i }}</td>
                <td>01/01/25</td>
                <td>Sampah</td>
                <td>Limbah</td>
                <td>0.05 kg</td>
                <td>0.05 kg Co₂e</td>
              </tr>
              @endfor
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card mb-4">
      <div class="card-body">
        <button id="openEmisiModal" class="btn btn-success w-100">
          <i class="bi bi-plus-circle me-2"></i> Input Emisi Carbon
        </button>
      </div>
    </div>
    
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title fw-bold">Total Emisi Carbon</h5>
        <p class="fs-2 fw-bold mt-3">49.65 <span class="fs-6">ton Co₂e</span></p>
        <p class="text-muted small">Total Emisi Carbon</p>
        <p class="text-success small d-flex align-items-center">
          <i class="bi bi-graph-up me-1"></i>
          1.20% sejak bulan lalu
        </p>
      </div>
    </div>
    
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title fw-bold">Jumlah Input</h5>
        <p class="fs-3 fw-bold mt-3">6</p>
        <p class="text-muted small">Total input data</p>
      </div>
    </div>
    
    <div class="card mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h5 class="card-title fw-bold">Tipe Emisi</h5>
          <small class="text-muted">Januari 2025</small>
        </div>
        <div class="d-flex justify-content-center py-3" style="height: 200px;">
          <canvas id="donutChart"></canvas>
        </div>
        <div class="mt-3 small">
          <div class="d-flex align-items-center mb-2">
            <span class="d-inline-block rounded-circle me-2" style="width:10px; height:10px; background-color:#14532d;"></span> 
            <span>Produk - 40%</span>
          </div>
          <div class="d-flex align-items-center mb-2">
            <span class="d-inline-block rounded-circle me-2" style="width:10px; height:10px; background-color:#16a34a;"></span> 
            <span>Degrowth - 32%</span>
          </div>
          <div class="d-flex align-items-center">
            <span class="d-inline-block rounded-circle me-2" style="width:10px; height:10px; background-color:#4ade80;"></span> 
            <span>Growth - 28%</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Include Modal -->
@include('modals.input-emisi')

<!-- ChartJS script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize chart
        const emissionChart = new Chart(document.getElementById('emissionChart'), {
            type: 'line',
            data: {
                labels: [], // Empty initially, will be populated
                datasets: [{
                    label: 'Carbon Emission',
                    data: [], // Empty initially
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
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
                btn.classList.toggle('active', btn.getAttribute('data-period') === period);
            });
        }

        // Load chart data for selected period
        async function loadChartData(period) {
            try {
                // Show loading state (optional)
                document.getElementById('emissionChart').style.opacity = '0.5';
                
                // In a real app, you would fetch from your API:
                // const response = await fetch(`/api/emission-data?period=${period}`);
                // const data = await response.json();
                
                // Simulated API response - replace with actual fetch in production
                const data = await simulateFetchData(period);
                
                // Update chart
                emissionChart.data.labels = data.labels;
                emissionChart.data.datasets[0].data = data.values;
                emissionChart.update();
                
                // Update comparison text
                updateComparisonText(data.comparison);
                
            } catch (error) {
                console.error('Error loading chart data:', error);
                // Show error to user (optional)
            } finally {
                document.getElementById('emissionChart').style.opacity = '1';
            }
        }

        // Simulate API fetch - REMOVE IN PRODUCTION
        function simulateFetchData(period) {
            return new Promise(resolve => {
                setTimeout(() => {
                    const data = {
                        '1M': {
                            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                            values: [12, 10, 11, 12],
                            comparison: '+23% vs last month'
                        },
                        '3M': {
                            labels: ['Month 1', 'Month 2', 'Month 3'],
                            values: [10, 12, 14],
                            comparison: '+15% vs last quarter'
                        },
                        '6M': {
                            labels: ['Q1', 'Q2'],
                            values: [8, 10],
                            comparison: '+12% vs last half year'
                        },
                        '1Y': {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                            values: [5, 8, 10, 12, 15, 18],
                            comparison: '+20% vs last year'
                        }
                    };
                    resolve(data[period]);
                }, 300); // Simulate network delay
            });
        }

        // Update the comparison text
        function updateComparisonText(text) {
            const comparisonElement = document.querySelector('.absolute.top-0.left-10');
            if (comparisonElement) {
                comparisonElement.textContent = text;
            }
        }

        // Initialize Donut Chart
        const donutChart = new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: ['product', 'degrowth', 'growth'],
                datasets: [{
                    label: 'Emission Type',
                    data: [40, 32, 28],
                    backgroundColor: [
                        '#065f46', // green-800
                        '#22C55E', // green-500
                        '#86efac'  // green-300
                    ],
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
    });
</script>
@endsection