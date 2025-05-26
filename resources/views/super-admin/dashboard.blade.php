@extends('layouts.super-admin')

@section('title', 'Dashboard Super Admin')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card bg-gradient-success-light">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success p-3 me-3">
                        <i class="bi bi-shield-check text-white fs-4"></i>
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
                    <canvas id="emisiChart"></canvas>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <h6 class="mb-0">Total Emisi Karbon</h6>
                        <h4 class="text-success mb-0" id="totalEmisi">{{ number_format($emisiChartData['data'] ? array_sum($emisiChartData['data']) : 0, 2) }} kg CO₂e</h4>
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
                    <option value="company">Berdasarkan Perusahaan</option>
                </select>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="distributionChart"></canvas>
                </div>
                <div class="chart-legend mt-3">
                    <div class="d-flex flex-column" id="chart-legend-container">
                        @foreach($companyChartData['labels'] as $index => $company)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="color-dot me-2" style="background-color: {{ $companyChartData['colors'][$index] }};"></div>
                                <span>{{ $company }}</span>
                            </div>
                            <div>
                                <span class="badge bg-light text-dark">{{ $companyChartData['percentages'][$index] }}%</span>
                            </div>
                        </div>
                        @endforeach
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
                            @forelse($latestCompanies as $perusahaan)
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
        
        // Chart data with error handling
        const emisiChartData = @json($emisiChartData);
        const companyChartData = @json($companyChartData);
        
        // Debug chart data
        console.log('Emisi Chart Data:', emisiChartData);
        console.log('Company Chart Data:', companyChartData);
        
        // Validate chart data
        if (!emisiChartData || !emisiChartData.labels || !emisiChartData.data) {
            console.error('Invalid emisi chart data:', emisiChartData);
            return;
        }
        
        if (!companyChartData || !companyChartData.labels || !companyChartData.data) {
            console.error('Invalid company chart data:', companyChartData);
            return;
        }
        
        // Format data to ensure consistent type
        const formattedData = emisiChartData.data.map(value => parseFloat(value) || 0);
        
        // Emisi Chart
        const emisiCanvas = document.getElementById('emisiChart');
        if (!emisiCanvas) {
            console.error('Emisi chart canvas not found');
            return;
        }
        
        const emisiCtx = emisiCanvas.getContext('2d');
        const emisiChart = new Chart(emisiCtx, {
            type: 'line',
            data: {
                labels: emisiChartData.labels,
                datasets: [{
                    label: 'Emisi Karbon (kg CO₂e)',
                    data: formattedData,
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderColor: '#28a745',
                    borderWidth: 2,
                    pointBackgroundColor: '#28a745',
                    pointRadius: 4,
                    pointHoverRadius: 6,
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
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toFixed(1) + ' kg';
                            },
                            font: {
                                size: 11
                            }
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
                                size: 10
                            }
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
                                return `${parseFloat(context.parsed.y).toFixed(2)} kg CO₂e`;
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                elements: {
                    line: {
                        borderJoinStyle: 'round'
                    }
                }
            }
        });
        
        // Distribution Chart (Donut)
        const distributionCanvas = document.getElementById('distributionChart');
        if (!distributionCanvas) {
            console.error('Distribution chart canvas not found');
            return;
        }
        
        const distributionCtx = distributionCanvas.getContext('2d');
        const distributionChart = new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: companyChartData.labels,
                datasets: [{
                    data: companyChartData.data,
                    backgroundColor: companyChartData.colors,
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
        
        // Update approval statistics progress bars
        function updateApprovalProgress() {
            const pendingCount = parseInt(document.getElementById('pending-count').textContent);
            const approvedCount = parseInt(document.getElementById('approved-count').textContent);
            const rejectedCount = parseInt(document.getElementById('rejected-count').textContent);
            
            const totalCount = pendingCount + approvedCount + rejectedCount;
            
            if (totalCount > 0) {
                document.getElementById('pending-progress').style.width = `${(pendingCount / totalCount) * 100}%`;
                document.getElementById('approved-progress').style.width = `${(approvedCount / totalCount) * 100}%`;
                document.getElementById('rejected-progress').style.width = `${(rejectedCount / totalCount) * 100}%`;
            }
        }
        
        updateApprovalProgress();
        
        // Add event listeners for period buttons
        const periodButtons = document.querySelectorAll('.period-btn');
        periodButtons.forEach(button => {
            button.addEventListener('click', function() {
                periodButtons.forEach(btn => btn.classList.remove('btn-success', 'active'));
                periodButtons.forEach(btn => btn.classList.add('btn-outline-success'));
                this.classList.remove('btn-outline-success');
                this.classList.add('btn-success', 'active');
                
                const period = this.getAttribute('data-period');
                window.location.href = `{{ route('superadmin.dashboard') }}?period=${period}`;
            });
        });
    });
</script>
@endsection

@section('styles')
<style>
    /* Dashboard styles */
    .card {
        transition: all 0.3s ease;
        transform: translateY(20px);
        opacity: 0;
    }
    
    .color-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    
    .bg-success-subtle {
        background-color: rgba(40, 167, 69, 0.1);
    }
    
    .bg-primary-subtle {
        background-color: rgba(0, 123, 255, 0.1);
    }
    
    .bg-info-subtle {
        background-color: rgba(23, 162, 184, 0.1);
    }
    
    .bg-secondary-subtle {
        background-color: rgba(108, 117, 125, 0.1);
    }
    
    .bg-gradient-success-light {
        background: linear-gradient(45deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.2) 100%);
    }
</style>
@endsection
