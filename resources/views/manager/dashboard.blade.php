@extends('layouts.manager')

@section('title', 'Dashboard Manager')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card bg-gradient-success-light">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success p-3 me-3">
                            <i class="bi bi-briefcase-fill text-white fs-4"></i>
                        </div>
                        <div>
                            <h4 class="card-title fw-bold text-success mb-1">Selamat Datang, {{ Auth::user()->nama }}!</h4>
                            <p class="text-muted mb-0">Anda login sebagai Manager untuk perusahaan {{ Auth::user()->perusahaan->nama_perusahaan ?? 'Unknown' }}</p>
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
                        <i class="bi bi-bar-chart-fill text-success me-2"></i>Total Emisi Karbon
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
                        <div class="text-success">
                            <span id="comparison-text">{{ $emisiChartData['comparison'] > 0 ? '+' : '' }}{{ $emisiChartData['comparison'] }}%</span> vs periode sebelumnya
                        </div>
                    </div>
                    <div class="chart-container" style="height: 350px;">
                        <canvas id="emisiChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold">
                        <i class="bi bi-list-check text-success me-2"></i>Data Emisi Terbaru
                    </h5>
                    <a href="#" class="btn btn-sm btn-outline-success rounded-pill">
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
                                    <th>Staff</th>
                                    <th>Kategori</th>
                                    <th>Nilai Aktivitas</th>
                                    <th>Kadar Emisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestEmissions as $index => $emisi)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($emisi->tanggal_emisi)->timezone('Asia/Jakarta')->format('d/m/y') }} WIB</td>
                                    <td>{{ $emisi->staff->nama ?? 'Unknown' }}</td>
                                    <td>{{ $emisi->kategori_emisi_karbon }}</td>
                                    <td>{{ $emisi->nilai_aktivitas }} kg</td>
                                    <td>
                                        <span class="badge bg-success">{{ $emisi->kadar_emisi_karbon }} kg CO₂e</span>
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
                <div class="card-header bg-white border-0">
                    <h5 class="card-title fw-bold">
                        <i class="bi bi-grid-1x2-fill text-success me-2"></i>Statistik Dashboard
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stats-item mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Total Emisi Karbon</span>
                            <i class="bi bi-cloud text-success"></i>
                        </div>
                        <h3 class="fw-bold text-success">{{ number_format($dashboardStats['total_emisi'], 2) }} kg CO₂e</h3>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>
                    
                    <div class="stats-item mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Emisi Terkompensasi</span>
                            <i class="bi bi-arrow-left-right text-info"></i>
                        </div>
                        {{-- <h3 class="fw-bold text-info">{{ number_format($dashboardStats['compensated_emission'], 2) }} kg CO₂e</h3> --}}
                        <div class="progress" style="height: 5px;">
                            {{-- <div class="progress-bar bg-info" style="width: {{ $dashboardStats['total_emisi'] > 0 ? ($dashboardStats['compensated_emission'] / $dashboardStats['total_emisi']) * 100 : 0 }}%"></div> --}}
                        </div>
                    </div>

                    <div class="stats-item mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Tim Saya</span>
                            <i class="bi bi-people text-primary"></i>
                        </div>
                        <div class="row text-center mt-3">
                            <div class="col-6">
                                <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-primary bg-opacity-10 p-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-person-badge text-primary fs-5"></i>
                                </div>
                                <h5 class="mt-2 mb-0">{{ $dashboardStats['team_stats']['admin_count'] ?? 0 }}</h5>
                                <span class="text-muted small">Admin</span>
                            </div>
                            <div class="col-6">
                                <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-success bg-opacity-10 p-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-person text-success fs-5"></i>
                                </div>
                                <h5 class="mt-2 mb-0">{{ $dashboardStats['team_stats']['staff_count'] ?? 0 }}</h5>
                                <span class="text-muted small">Staff</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title fw-bold">
                        <i class="bi bi-pie-chart-fill text-success me-2"></i>Kategori Emisi
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
        
        // Chart data with error handling
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
        
        if (!categoryChartData || !categoryChartData.labels || !categoryChartData.data) {
            console.error('Invalid category chart data:', categoryChartData);
            return;
        }
        
        // Format data to ensure consistent type
        const formattedData = emisiChartData.data.map(value => parseFloat(value) || 0);
        
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
                window.location.href = `{{ route('manager.dashboard') }}?period=${period}`;
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
