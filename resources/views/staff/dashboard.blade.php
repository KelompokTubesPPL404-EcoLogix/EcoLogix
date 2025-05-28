@extends('layouts.staff')

@section('title', 'Dashboard Staff')

@push('css')
    <style>
        .eco-gradient {
            background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%);
        }
        .eco-card {
            border-left: 4px solid #28a745;
            transition: all 0.3s ease;
        }
        .eco-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15);
        }
        .eco-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 3px solid #28a745;
        }
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-left: 4px solid #28a745;
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
        }
        .stats-icon {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .table-eco {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .table-eco thead {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        .table-eco tbody tr:hover {
            background-color: rgba(40, 167, 69, 0.05);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
        .btn-action {
            padding: 0.25rem 0.5rem;
            margin: 0 0.1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        .btn-action:hover {
            transform: translateY(-1px);
        }
        .carbon-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .welcome-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #28a745;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .welcome-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15);
        }
        .color-dot {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: inline-block;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Enhanced Eco Header Section -->
    <div class="eco-header p-4 rounded-3 mb-4">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-2 text-success fw-bold">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard Staff
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>Pantau dan kelola emisi karbon perusahaan Anda
                </p>
            </div>
            <div class="d-flex align-items-center">
                <div class="carbon-badge me-3">
                    <i class="bi bi-building-check me-1"></i>{{ Auth::user()->perusahaan->nama_perusahaan ?? 'Unknown' }}
                </div>
                <a href="{{ route('staff.emisicarbon.create') }}" class="btn btn-success shadow-sm px-4">
                    <i class="bi bi-plus-circle me-1"></i> Input Emisi Baru
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="welcome-card p-4 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="stats-icon me-3">
                        <i class="bi bi-person-fill fs-4"></i>
                    </div>
                    <div>
                        <h4 class="card-title fw-bold text-success mb-1">Selamat Datang, {{ Auth::user()->nama }}!</h4>
                        <p class="text-muted mb-0">Anda login sebagai Staff untuk perusahaan {{ Auth::user()->perusahaan->nama_perusahaan ?? 'Unknown' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="eco-card card mb-4 shadow-lg rounded-3 overflow-hidden">
                <div class="card-header eco-gradient text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="bi bi-bar-chart-fill me-2"></i>Emisi Karbon Saya
                    </h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-light active period-btn" data-period="1M">1M</button>
                        <button type="button" class="btn btn-outline-light period-btn" data-period="3M">3M</button>
                        <button type="button" class="btn btn-outline-light period-btn" data-period="6M">6M</button>
                        <button type="button" class="btn btn-outline-light period-btn" data-period="1Y">1Y</button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            @if($emisiChartData['comparison'] > 0)
                                <span class="badge bg-danger text-white me-2">
                                    <i class="bi bi-arrow-up-right me-1"></i>+{{ $emisiChartData['comparison'] }}%
                                </span>
                            @elseif($emisiChartData['comparison'] < 0)
                                <span class="badge bg-success text-white me-2">
                                    <i class="bi bi-arrow-down-right me-1"></i>{{ $emisiChartData['comparison'] }}%
                                </span>
                            @else
                                <span class="badge bg-secondary text-white me-2">
                                    <i class="bi bi-dash me-1"></i>0%
                                </span>
                            @endif
                            <span class="text-muted">vs periode sebelumnya</span>
                        </div>
                    </div>
                    <div class="chart-container" style="height: 300px; width: 100%;">
                        <canvas id="emisiChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="eco-card card mb-4 shadow-lg rounded-3 overflow-hidden">
                <div class="card-header eco-gradient text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="bi bi-list-check me-2"></i>Riwayat Input Data
                    </h5>
                    <a href="{{ route('staff.emisicarbon.index') }}" class="btn btn-light btn-sm rounded-pill px-3">
                        <i class="bi bi-eye me-1"></i>Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-eco table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="border-0 fw-bold text-center">
                                        <i class="bi bi-hash me-1"></i>No
                                    </th>
                                    <th class="border-0 fw-bold">
                                        <i class="bi bi-calendar-date me-1"></i>Tanggal
                                    </th>
                                    <th class="border-0 fw-bold">
                                        <i class="bi bi-tags me-1"></i>Kategori
                                    </th>
                                    <th class="border-0 fw-bold">
                                        <i class="bi bi-activity me-1"></i>Nilai Aktivitas
                                    </th>
                                    <th class="border-0 fw-bold">
                                        <i class="bi bi-calculator me-1"></i>Kadar Emisi
                                    </th>
                                    <th class="border-0 fw-bold text-center">
                                        <i class="bi bi-circle-fill me-1"></i>Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestEmissions as $index => $emisi)
                                <tr class="align-middle">
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark fw-bold">{{ $index + 1 }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($emisi->tanggal_emisi)->timezone('Asia/Jakarta')->format('d/m/y') }} WIB</td>
                                    <td>
                                        <div class="fw-bold text-success">{{ $emisi->kategori_emisi_karbon }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-activity text-success me-2"></i>
                                            <span class="fw-bold">{{ $emisi->nilai_aktivitas }} kg</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calculator text-success me-2"></i>
                                            <span class="fw-bold">{{ $emisi->kadar_emisi_karbon }} kg CO₂e</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($emisi->status == 'pending')
                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="bi bi-hourglass-split me-1"></i> Pending
                                            </span>
                                        @elseif($emisi->status == 'approved')
                                            <span class="badge bg-success text-white px-3 py-2">
                                                <i class="bi bi-check-circle-fill me-1"></i> Approved
                                            </span>
                                        @elseif($emisi->status == 'rejected')
                                            <span class="badge bg-danger text-white px-3 py-2">
                                                <i class="bi bi-x-circle-fill me-1"></i> Rejected
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Belum ada data emisi tercatat
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
            <div class="eco-card card mb-4 shadow-lg rounded-3 overflow-hidden">
                <div class="card-header eco-gradient text-white py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="bi bi-grid-1x2-fill me-2"></i>Statistik Saya
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="stats-card p-3 rounded-3 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon me-3">
                                <i class="bi bi-cloud fs-4"></i>
                            </div>
                            <div>
                                <span class="text-muted">Total Emisi Karbon</span>
                                <h3 class="fw-bold text-success mb-0">{{ number_format($dashboardStats['total_emisi'], 2) }} kg CO₂e</h3>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>
                    
                    <div class="stats-card p-3 rounded-3 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon me-3" style="background: linear-gradient(45deg, #0d6efd, #0dcaf0);">
                                <i class="bi bi-input-cursor-text fs-4"></i>
                            </div>
                            <div>
                                <span class="text-muted">Total Input Data</span>
                                <h3 class="fw-bold text-primary mb-0">{{ $dashboardStats['total_input'] }}</h3>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-card p-3 rounded-3 text-center">
                                <div class="stats-icon mx-auto mb-2" style="background: linear-gradient(45deg, #ffc107, #fd7e14);">
                                    <i class="bi bi-hourglass-split fs-4"></i>
                                </div>
                                <h4 class="mb-0">{{ $latestEmissions->where('status', 'pending')->count() }}</h4>
                                <span class="text-muted small">Pending</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card p-3 rounded-3 text-center">
                                <div class="stats-icon mx-auto mb-2">
                                    <i class="bi bi-check-circle fs-4"></i>
                                </div>
                                <h4 class="mb-0">{{ $latestEmissions->where('status', 'approved')->count() }}</h4>
                                <span class="text-muted small">Approved</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card p-3 rounded-3 text-center">
                                <div class="stats-icon mx-auto mb-2" style="background: linear-gradient(45deg, #dc3545, #fd7e14);">
                                    <i class="bi bi-x-circle fs-4"></i>
                                </div>
                                <h4 class="mb-0">{{ $latestEmissions->where('status', 'rejected')->count() }}</h4>
                                <span class="text-muted small">Rejected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="eco-card card mb-4 shadow-lg rounded-3 overflow-hidden">
                <div class="card-header eco-gradient text-white py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="bi bi-pie-chart-fill me-2"></i>Kategori Emisi
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    <div class="mt-4">
                        @foreach($categoryChartData['labels'] as $index => $category)
                        <div class="d-flex justify-content-between align-items-center p-2 mb-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="color-dot me-2" style="background-color: {{ $categoryChartData['colors'][$index] }};"></div>
                                <span class="fw-bold">{{ $category }}</span>
                            </div>
                            <div>
                                <span class="badge bg-success text-white">{{ $categoryChartData['percentages'][$index] }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="eco-card card mb-4 shadow-lg rounded-3 overflow-hidden">
                <div class="card-header eco-gradient text-white py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="bi bi-plus-circle-fill me-2"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body p-4">
                    <a href="{{ route('staff.emisicarbon.create') }}" class="btn btn-success w-100 mb-3 shadow-sm">
                        <i class="bi bi-plus-lg me-2"></i> Input Data Emisi Baru
                    </a>
                    <a href="{{ route('staff.emisicarbon.index') }}" class="btn btn-outline-success w-100">
                        <i class="bi bi-list-check me-2"></i> Lihat Semua Data Emisi
                    </a>
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
                    backgroundColor: 'rgba(40, 167, 69, 0.6)',
                    borderColor: '#28a745',
                    borderWidth: 1,
                    borderRadius: 4,
                    hoverBackgroundColor: 'rgba(40, 167, 69, 0.8)',
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
                        color: '#28a745'
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
