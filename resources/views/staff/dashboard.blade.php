@extends('layouts.staff')

@section('title', 'Dashboard Staff')

@section('styles')
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
        .period-btn.active {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
            border-color: #28a745;
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
        .period-btn {
            background-color: white;
            color: #28a745 !important;
            border: 1px solid #28a745 !important;
            font-weight: 500;
            padding: 5px 15px;
        }

        .period-btn.active {
            background-color: #28a745 !important;
            color: white !important;
            border-color: #28a745 !important;
        }

        .period-btn:hover {
            background-color: #28a745 !important;
            color: white !important;
        }

        .btn-group .period-btn:first-child {
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
        }

        .btn-group .period-btn:last-child {
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
        }
        
    </style>
@endpush
@endsection

@section('content')
<div class="container-fluid">
    

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
                <div class="card-header eco-gradient text-success py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="bi bi-graph-up me-2"></i>Emisi Karbon Saya (Line Chart)
                    </h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn period-btn active" data-period="1M">1M</button>
                        <button type="button" class="btn period-btn" data-period="3M">3M</button>
                        <button type="button" class="btn period-btn" data-period="6M">6M</button>
                        <button type="button" class="btn period-btn" data-period="1Y">1Y</button>
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
                    <div class="chart-container" style="height: 350px;">
                        <canvas id="emisiChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="eco-card card mb-4 shadow-lg rounded-3 overflow-hidden">
                <div class="card-header eco-gradient text-success py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="bi bi-list-check me-2"></i>Riwayat Input Data
                    </h5>
                    <a href="{{ route('staff.emisicarbon.index') }}" class="btn btn-success btn-sm rounded-pill px-3">
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
                <div class="card-header eco-gradient text-success py-3">
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
                <div class="card-header eco-gradient text-success py-3">
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
        
        const emisiChartData = @json($emisiChartData);
        const categoryChartData = @json($categoryChartData);
        
        console.log('Emisi Chart Data:', emisiChartData);
        console.log('Category Chart Data:', categoryChartData);
        
        if (!emisiChartData || !emisiChartData.labels || !emisiChartData.data) {
            console.error('Invalid emisi chart data:', emisiChartData);
            const emisiChartContainer = document.getElementById('emisiChart').parentElement;
            if (emisiChartContainer) {
                emisiChartContainer.innerHTML = '<div class="text-center py-5"><i class="bi bi-exclamation-circle text-muted fs-1"></i><p class="mt-3 text-muted">Data emisi tidak valid.</p></div>';
            }
            return;
        }
        
        const formattedData = emisiChartData.data.map(value => parseFloat(value) || 0);
        const hasActualData = formattedData.some(value => value > 0);

        // Emisi Chart (Visualisasi Baru - Kombinasi Bar & Area Chart)
        const emisiCanvas = document.getElementById('emisiChart');
        if (!emisiCanvas) {
            console.error('Emisi chart canvas not found');
        } else {
            const emisiCtx = emisiCanvas.getContext('2d');
            
            // Debug data untuk troubleshooting
            console.log('Checking emisiChartData:', emisiChartData);
            console.log('Data values:', emisiChartData.data);
            console.log('Sum of data:', emisiChartData.data.reduce((a, b) => a + parseFloat(b || 0), 0));
            
            // Perbaikan kondisi pengecekan data
            const hasData = emisiChartData && 
                           emisiChartData.labels && 
                           emisiChartData.data && 
                           emisiChartData.data.length > 0 && 
                           !emisiChartData.labels.includes('No Data') &&
                           emisiChartData.data.some(value => parseFloat(value) > 0);
            
            if (!hasData) {
                console.log('No emission data available or all values are zero');
                const emisiChartContainer = emisiCanvas.parentElement;
                if (emisiChartContainer) {
                    emisiChartContainer.innerHTML = '<div class="text-center py-5"><i class="bi bi-exclamation-circle text-muted fs-1"></i><p class="mt-3 text-muted">Belum ada data emisi karbon. Silahkan input data untuk melihat visualisasi.</p></div>';
                }
            } else {
                // Generate target data (simulasi target pengurangan emisi 5% dari baseline)
                const baselineValue = Math.max(...emisiChartData.data.map(d => parseFloat(d || 0)));
                const targetData = emisiChartData.data.map(() => baselineValue * 0.95);

                // Generate moving average untuk trendline
                const movingAverage = [];
                const window = 3; // window size untuk moving average

                for (let i = 0; i < emisiChartData.data.length; i++) {
                    if (i < window - 1) {
                        movingAverage.push(null); // Awal data tidak punya cukup titik
                    } else {
                        let sum = 0;
                        for (let j = 0; j < window; j++) {
                            sum += parseFloat(emisiChartData.data[i - j] || 0);
                        }
                        movingAverage.push(sum / window);
                    }
                }

                // Hitung total emisi - pastikan nilai numerik
                const totalEmission = emisiChartData.data.reduce((a, b) => a + parseFloat(b || 0), 0);
                
                // Visualisasi baru - Kombinasi Bar & Area Chart dengan trendline
                const emisiChart = new Chart(emisiCtx, {
                    type: 'bar',
                    data: {
                        labels: emisiChartData.labels,
                        datasets: [
                            {
                                label: 'Emisi Karbon (kg CO₂e)',
                                data: emisiChartData.data.map(d => parseFloat(d || 0)),
                                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                                borderColor: '#28a745',
                                borderWidth: 1,
                                borderRadius: 4,
                                barPercentage: 0.6,
                                order: 2
                            },
                            {
                                label: 'Trendline (Moving Average)',
                                data: movingAverage,
                                type: 'line',
                                borderColor: '#fd7e14',
                                borderWidth: 2,
                                pointBackgroundColor: '#fd7e14',
                                pointRadius: 3,
                                pointHoverRadius: 5,
                                fill: false,
                                tension: 0.4,
                                order: 1
                            },
                            {
                                label: 'Target Pengurangan',
                                data: targetData,
                                type: 'line',
                                borderColor: 'rgba(220, 53, 69, 0.7)',
                                borderWidth: 2,
                                borderDash: [5, 5],
                                pointRadius: 0,
                                fill: false,
                                order: 0
                            }
                        ]
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
                                    },
                                    color: '#666' // Set tick color to dark grey
                                },
                                title: {
                                    display: true,
                                    text: 'Total Emisi (kg CO₂e)',
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    },
                                    padding: {top: 10, bottom: 10},
                                    color: '#333' // Set title color to dark grey
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
                                    },
                                    color: '#666' // Set tick color to dark grey
                                },
                                title: {
                                    display: true,
                                    text: 'Bulan',
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    },
                                    padding: {top: 10, bottom: 0},
                                    color: '#333' // Set title color to dark grey
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15,
                                    font: {
                                        size: 11
                                    }
                                }
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
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += parseFloat(context.parsed.y).toLocaleString('id-ID') + ' kg CO₂e';
                                        }
                                        return label;
                                    },
                                    afterBody: function(tooltipItems) {
                                        if (tooltipItems[0].datasetIndex === 0) { // Only for the main dataset
                                            const currentValue = tooltipItems[0].parsed.y;
                                            const targetValue = targetData[tooltipItems[0].dataIndex];
                                            const difference = currentValue - targetValue;
                                            const percentage = (difference / targetValue) * 100;
                                            
                                            return ['', 
                                                `Selisih dari target: ${difference.toLocaleString('id-ID')} kg CO₂e`,
                                                `${percentage > 0 ? '+' : ''}${percentage.toFixed(1)}% dari target`
                                            ];
                                        }
                                        return '';
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
                                padding: {top: 10, bottom: 20},
                                color: '#333'
                            },
                            annotation: {
                                annotations: {
                                    totalEmissionLine: {
                                        type: 'line',
                                        yMin: totalEmission / emisiChartData.data.length,
                                        yMax: totalEmission / emisiChartData.data.length,
                                        borderColor: 'rgba(75, 192, 192, 0.5)',
                                        borderWidth: 2,
                                        borderDash: [2, 2],
                                        label: {
                                            display: true,
                                            content: 'Rata-rata: ' + (totalEmission / emisiChartData.data.length).toLocaleString('id-ID') + ' kg',
                                            position: 'end',
                                            backgroundColor: 'rgba(75, 192, 192, 0.8)',
                                            font: {
                                                size: 10
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        animation: {
                            duration: 1000,
                            easing: 'easeOutQuart'
                        }
                    }
                });
                
                // Tambahkan informasi ringkasan di bawah chart
                const chartContainer = emisiCanvas.parentElement;
                const summaryDiv = document.createElement('div');
                summaryDiv.className = 'mt-3 p-3 bg-light rounded';
                
                chartContainer.appendChild(summaryDiv);
            }
        }

        // Category Chart (Donut)
        const categoryCanvas = document.getElementById('categoryChart');
        if (!categoryCanvas) {
            console.error('Category chart canvas not found');
            return;
        }
        const categoryCtx = categoryCanvas.getContext('2d');
        
        if (!categoryChartData || !categoryChartData.labels || !categoryChartData.data || categoryChartData.data.every(d => d === 0 || d === 1 && categoryChartData.labels[0] === 'No Data')) {
             console.log('No category data available or only placeholder data.');
            const categoryChartContainer = categoryCanvas.parentElement;
            if (categoryChartContainer) {
                categoryChartContainer.innerHTML = '<div class="text-center py-5"><i class="bi bi-pie-chart text-muted fs-1"></i><p class="mt-3 text-muted">Tidak ada data kategori emisi untuk ditampilkan.</p></div>';
            }
        } else {
            const categoryChart = new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: categoryChartData.labels,
                    datasets: [{
                        data: categoryChartData.data,
                        backgroundColor: categoryChartData.colors,
                        borderWidth: 2, // Added border for separation
                        borderColor: '#fff' // White border
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                           display: false // Switched off as details are below
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${context.label}: ${parseFloat(value).toLocaleString('id-ID')} kg CO₂e (${percentage}%)`;
                                }
                            }
                        },
                        title: {
                                display: true,
                                text: 'Distribusi Emisi per Kategori',
                                font: { size: 14, weight: 'bold' },
                                padding: { top: 5, bottom: 15 },
                                color: '#333' // Set title color to dark grey
                            }
                    }
                }
            });
        }
        
        // Period buttons event listeners
        const periodButtons = document.querySelectorAll('.period-btn');
        periodButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active button styling
                periodButtons.forEach(btn => {
                    btn.classList.remove('btn-success', 'active'); // Remove active and btn-success from previously active button
            btn.classList.add('btn-outline-success');
                });
                this.classList.remove('btn-outline-success');
            this.classList.add('btn-success', 'active'); // Add active and btn-success to the clicked button
                
                const period = this.getAttribute('data-period');
                // Redirect to update data based on period
                window.location.href = `{{ route('staff.dashboard') }}?period=${period}`;
            });
        });

        // Set active period button based on URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const currentPeriod = urlParams.get('period') || '1M'; // Default to 1M if no period in URL
        periodButtons.forEach(button => {
            if (button.getAttribute('data-period') === currentPeriod) {
                button.classList.remove('btn-outline-success');
            button.classList.add('btn-success', 'active');
            } else {
                button.classList.remove('btn-success', 'active');
            button.classList.add('btn-outline-success');
            }
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

    /* Styling for period buttons */
    .period-btn.active {
        background-color: #ffffff !important; /* White background for active */
        color: #28a745 !important; /* Green text for active */
        border-color: #ffffff !important; 
    }
    .period-btn {
        color: #f8f9fa; /* Light text for inactive */
        border-color: #f8f9fa; /* Light border for inactive */
    }
    .period-btn:hover {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
    }

</style>
@endsection