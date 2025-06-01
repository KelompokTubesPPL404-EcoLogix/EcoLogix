@extends('layouts.staff')

@section('title', 'Dashboard Staff')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card bg-gradient-success-light">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center justify-content-center me-3"
                            style="background-color: #019D20; width: 56px; height: 56px; border-radius: 50%;">
                            <i class="bi bi-person-fill text-white fs-4"></i>
                        </div>
                        <div>
                            <h4 class="card-title fw-bold text-custom-green mb-1">Selamat Datang, {{ Auth::user()->nama }}!</h4>
                            <p class="text-muted mb-0">Anda login sebagai Staff untuk perusahaan {{ Auth::user()->perusahaan->nama_perusahaan ?? 'Unknown' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold">
                        <i class="bi bi-bar-chart-fill text-custom-green me-2"></i>Emisi Karbon Saya
                    </h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-success active period-btn" data-period="1M">1M</button>
                        <button type="button" class="btn btn-outline-success period-btn" data-period="3M">3M</button>
                        <button type="button" class="btn btn-outline-success period-btn" data-period="6M">6M</button>
                        <button type="button" class="btn btn-outline-success period-btn" data-period="1Y">1Y</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="text-custom-green">
                            <span id="comparison-text">{{ $emisiChartData['comparison'] > 0 ? '+' : '' }}{{ $emisiChartData['comparison'] }}%</span> vs periode sebelumnya
                        </div>
                    </div>
                    <div class="chart-container" style="height: 300px; width: 100%;">
                        <canvas id="emisiChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold">
                        <i class="bi bi-list-check text-custom-green me-2"></i>Riwayat Input Data
                    </h5>
                    <a href="{{ route('staff.emisicarbon.index') }}" class="btn btn-sm btn-outline-success rounded-pill">
                        <i class="bi bi-eye me-1"></i>Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Nilai Aktivitas</th>
                                    <th>Kadar Emisi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestEmissions as $index => $emisi)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($emisi->tanggal_emisi)->format('d/m/y') }}</td>
                                    <td>{{ $emisi->kategori_emisi_karbon }}</td>
                                    <td>{{ $emisi->nilai_aktivitas }} kg</td>
                                    <td>{{ $emisi->kadar_emisi_karbon }} kg CO₂e</td>
                                    <td>
                                        @if($emisi->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($emisi->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($emisi->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
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
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0">
                        {{-- Pastikan menggunakan kelas warna hijau kustom Anda --}}
                        <i class="bi bi-grid-1x2-fill text-custom-green me-2"></i>Statistik Saya
                    </h5>
                    <a href="{{ route('staff.emisicarbon.create') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Input Data Emisi Baru
                    </a>
                </div>
                <div class="card-body">
                    <div class="stats-item mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Total Emisi Karbon</span>
                            <i class="bi bi-cloud text-custom-green"></i>
                        </div>
                        <h3 class="fw-bold text-custom-green">{{ number_format($dashboardStats['total_emisi'], 2) }} kg CO₂e</h3>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>
                    
                    <div class="stats-item mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Total Input Data</span>
                            <i class="bi bi-input-cursor-text text-primary"></i>
                        </div>
                        <h3 class="fw-bold text-primary">{{ $dashboardStats['total_input'] }}</h3>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-warning bg-opacity-10 p-3" style="width: 64px; height: 64px;">
                                    <i class="bi bi-hourglass-split text-warning fs-4"></i>
                                </div>
                                <h4 class="mt-2 mb-0">{{ $latestEmissions->where('status', 'pending')->count() }}</h4>
                                <span class="text-muted small">Pending</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-success bg-opacity-10 p-3" style="width: 64px; height: 64px;">
                                    <i class="bi bi-check-circle text-custom-green fs-4"></i>
                                </div>
                                <h4 class="mt-2 mb-0">{{ $latestEmissions->where('status', 'approved')->count() }}</h4>
                                <span class="text-muted small">Approved</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-danger bg-opacity-10 p-3" style="width: 64px; height: 64px;">
                                    <i class="bi bi-x-circle text-danger fs-4"></i>
                                </div>
                                <h4 class="mt-2 mb-0">{{ $latestEmissions->where('status', 'rejected')->count() }}</h4>
                                <span class="text-muted small">Rejected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title fw-bold">
                        <i class="bi bi-pie-chart-fill text-custom-green me-2"></i>Kategori Emisi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    <div class="mt-3">
                        @foreach($categoryChartData['labels'] as $index => $category)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="color-dot me-2" style="background-color: {{ $categoryChartData['colors'][$index] }};"></div>
                                <span>{{ $category }}</span>
                            </div>
                            <div>
                                <span class="badge bg-light text-dark">{{ $categoryChartData['percentages'][$index] }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animasi card saat load
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.transform = 'translateY(0)';
                card.style.opacity = '1';
            }, 100 * index);
        });
        
        // Chart data with validation
        const emisiChartData = @json($emisiChartData);
        const categoryChartData = @json($categoryChartData);
        
        // Debug chart data
        console.log('Emisi Chart Data:', emisiChartData);
        console.log('Category Chart Data:', categoryChartData);
        
        // Validate chart data
        if (!emisiChartData || !emisiChartData.labels || !emisiChartData.data) {
            console.error('Invalid emisi chart data:', emisiChartData);
            return;
        }
        
        // Check if we have valid data first
        if (!emisiChartData.data || emisiChartData.data.length === 0) {
            console.log('No chart data available');
            // Create empty message in chart area
            const emisiChartContainer = document.querySelector('.chart-container');
            if (emisiChartContainer) {
                const noDataMessage = document.createElement('div');
                noDataMessage.className = 'text-center py-5';
                noDataMessage.innerHTML = '<i class="bi bi-exclamation-circle text-muted fs-1"></i><p class="mt-3 text-muted">Belum ada data emisi karbon. Silahkan input data untuk melihat visualisasi.</p>';
                emisiChartContainer.appendChild(noDataMessage);
            }
            return;
        }
        
        // Format data to ensure consistent type
        const formattedData = emisiChartData.data.map(value => parseFloat(value) || 0);
        
        // Enhanced formatted data - check if we have any non-zero values
        const hasData = formattedData.some(value => value > 0);
        if (!hasData) {
            console.log('All zero data values');
        }
        
        // Emisi Chart
        const emisiCanvas = document.getElementById('emisiChart');
        if (!emisiCanvas) {
            console.error('Emisi chart canvas not found');
            return;
        }
        
        const emisiCtx = emisiCanvas.getContext('2d');
        const emisiChart = new Chart(emisiCtx, {
            type: 'bar', // Changed to bar for better monthly visualization
            data: {
                labels: emisiChartData.labels,
                datasets: [{
                    label: 'Emisi Karbon Bulanan (kg CO₂e)',
                    data: formattedData,
                    backgroundColor: 'rgba(1, 157, 32, 0.6)',
                    borderColor: '#019D20',
                    borderWidth: 1,
                    borderRadius: 4,
                    hoverBackgroundColor: 'rgba(1, 157, 32, 0.8)',
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
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('id-ID') + ' kg';
                            },
                            font: {
                                size: 11
                            }
                        },
                        title: {
                            display: true,
                            text: 'Total Emisi (kg CO₂e)',
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            padding: {top: 10, bottom: 10}
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            font: {
                                size: 10,
                                weight: 'bold'
                            }
                        },
                        title: {
                            display: true,
                            text: 'Bulan',
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            padding: {top: 10, bottom: 0}
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 13
                        },
                        bodyFont: {
                            size: 12
                        },
                        padding: 10,
                        cornerRadius: 4,
                        callbacks: {
                            label: function(context) {
                                return `${parseFloat(context.parsed.y).toLocaleString('id-ID')} kg CO₂e`;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Visualisasi Emisi Karbon Bulanan',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {top: 10, bottom: 30},
                        color: '#019D20'
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
        
        // Category Chart (Donut)
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryChartData.labels,
                datasets: [{
                    data: categoryChartData.data,
                    backgroundColor: categoryChartData.colors,
                    borderWidth: 0
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
        
        // Period buttons event listeners
        const periodButtons = document.querySelectorAll('.period-btn');
        periodButtons.forEach(button => {
            button.addEventListener('click', function() {
                periodButtons.forEach(btn => btn.classList.remove('btn-success', 'active'));
                periodButtons.forEach(btn => btn.classList.add('btn-outline-success'));
                this.classList.remove('btn-outline-success');
                this.classList.add('btn-success', 'active');
                
                const period = this.getAttribute('data-period');
                window.location.href = `{{ route('staff.dashboard') }}?period=${period}`;
            });
        });
    });
</script>
@endsection

@section('styles')
<style>
    .card {
        transition: all 0.3s ease;
        transform: translateY(20px);
        opacity: 0;
    }
    
    .stats-item {
        position: relative;
    }
    
    .color-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    
    .chart-container {
        position: relative;
    }
</style>
@endsection
