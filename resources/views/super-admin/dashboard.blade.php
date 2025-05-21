@extends('layouts.super-admin')

@section('title', 'Dashboard Super Admin')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card bg-gradient-success-light">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success p-3 me-3">
                        <i class="bi bi-person-check-fill text-white fs-4"></i>
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
                                        <span class="fs-4 fw-bold text-success">{{ \App\Models\Perusahaan::count() }}</span>
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
                                        <span class="fs-4 fw-bold text-primary">{{ \App\Models\User::where('role', 'manager')->count() }}</span>
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
                                        <span class="fs-4 fw-bold text-info">{{ \App\Models\User::where('role', 'admin')->count() }}</span>
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
                                        <span class="fs-4 fw-bold text-secondary">{{ \App\Models\User::where('role', 'staff')->count() }}</span>
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
                    <button type="button" class="btn btn-success active period-btn" data-period="1Y">1 Tahun</button>
                    <button type="button" class="btn btn-outline-success period-btn" data-period="ALL">Semua</button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="emisiChart" width="400" height="250"></canvas>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <h6 class="mb-0">Total Emisi Karbon</h6>
                        <h4 class="text-success mb-0" id="totalEmisi">0 kg CO₂e</h4>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success">{{ \App\Models\EmisiKarbon::where('status', 'approved')->count() }}</span>
                        <span class="text-muted">Data Terverifikasi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="card-title fw-bold"><i class="bi bi-activity text-success me-2"></i>Aktivitas Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush mt-2">
                    @forelse($recentActivities ?? [] as $activity)
                    <div class="list-group-item border-bottom border-light d-flex justify-content-between align-items-center px-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light p-2 me-3">
                                <i class="bi bi-clock-history text-success"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-medium">{{ $activity->description ?? 'Aktivitas tidak diketahui' }}</p>
                                @if(isset($activity->subject))
                                <small class="text-muted">{{ $activity->subject }}</small>
                                @endif
                            </div>
                        </div>
                        <span class="badge bg-light text-success rounded-pill">{{ isset($activity->created_at) ? $activity->created_at->diffForHumans() : 'Beberapa waktu lalu' }}</span>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="bi bi-calendar2-x text-muted fs-1"></i>
                        <p class="text-muted mb-0 mt-2">Tidak ada aktivitas terbaru.</p>
                    </div>
                    @endforelse
                </div>
                
                {{-- Catatan untuk Pengembang: Pastikan variabel $recentActivities dikirim dari SuperAdminController@dashboard --}}
                {{-- Contoh struktur $activity: (object)['description' => 'Deskripsi aktivitas', 'subject' => 'Subjek (opsional)', 'created_at' => Carbon::now()] --}}
                
                <div class="d-grid mt-3">
                    <button class="btn btn-outline-success btn-sm rounded-pill">
                        <i class="bi bi-eye me-1"></i>Lihat Semua Aktivitas
                    </button>
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
                                <th class="border-0">No Telepon</th>
                                <th class="border-0 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Perusahaan::latest()->take(5)->get() as $perusahaan)
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
                                <td>{{ $perusahaan->no_telp_perusahaan }}</td>
                                <td class="text-center">
                                    <a href="{{ route('superadmin.perusahaan.show', $perusahaan->kode_perusahaan) }}" 
                                       class="btn btn-sm btn-outline-success rounded-pill">
                                       <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="bi bi-building-x text-muted fs-1"></i>
                                    <p class="text-muted mb-0 mt-2">Belum ada perusahaan terdaftar</p>
                                </td>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@php
// Fungsi untuk mendapatkan data emisi berdasarkan periode
function getEmissionData($period) {
    $now = now();
    $labels = [];
    $data = [];
    
    switch($period) {
        case '1Y': // 1 tahun
            $startDate = $now->copy()->subYear();
            $groupBy = 'month';
            $format = 'M Y';
            break;
        case 'ALL': // Semua data
            $startDate = \App\Models\EmisiKarbon::min('tanggal_emisi') ? \Carbon\Carbon::parse(\App\Models\EmisiKarbon::min('tanggal_emisi')) : $now->copy()->subYear();
            $groupBy = 'month';
            $format = 'M Y';
            break;
        default:
            $startDate = $now->copy()->subYear();
            $groupBy = 'month';
            $format = 'M Y';
    }
    
    // Ambil data emisi karbon
    $emissions = \App\Models\EmisiKarbon::where('tanggal_emisi', '>=', $startDate)
        ->orderBy('tanggal_emisi')
        ->get();
    
    // Kelompokkan data berdasarkan periode
    $groupedData = [];
    
    foreach ($emissions as $emission) {
        $date = \Carbon\Carbon::parse($emission->tanggal_emisi);
        $key = '';
        
        if ($groupBy == 'month') {
            $key = $date->format('Y-m');
            $label = $date->format($format);
        }
        
        if (!isset($groupedData[$key])) {
            $groupedData[$key] = [
                'label' => $label,
                'value' => 0
            ];
        }
        
        $groupedData[$key]['value'] += $emission->kadar_emisi_karbon;
    }
    
    // Urutkan data berdasarkan tanggal
    ksort($groupedData);
    
    // Siapkan data untuk chart
    foreach ($groupedData as $item) {
        $labels[] = $item['label'];
        $data[] = $item['value'];
    }
    
    // Hitung total emisi
    $totalEmisi = array_sum($data);
    
    return [
        'labels' => $labels,
        'data' => $data,
        'total_emisi' => $totalEmisi
    ];
}

// Ambil data default (1 tahun)
$defaultData = getEmissionData('1Y');
@endphp

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animasi sederhana untuk card-card dashboard
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.transition = 'all 0.3s ease';
                card.style.transform = 'translateY(0)';
                card.style.opacity = '1';
            }, 100 * index);
        });
        
        // Data chart dari server
        const chartData = {
            '1Y': {
                labels: {!! json_encode($defaultData['labels']) !!},
                data: {!! json_encode($defaultData['data']) !!},
                total_emisi: {!! json_encode($defaultData['total_emisi']) !!}
            }
        };
        
        // Update total emisi
        document.getElementById('totalEmisi').textContent = 
            `${parseFloat(chartData['1Y'].total_emisi).toLocaleString('id-ID')} kg CO₂e`;
        
        // Fungsi untuk update chart
        function updateChart(period) {
            const data = chartData[period];
            
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
            
            // Update total emisi
            document.getElementById('totalEmisi').textContent = 
                `${parseFloat(data.total_emisi).toLocaleString('id-ID')} kg CO₂e`;
        }
        
        // Event listener untuk tombol periode
        const periodButtons = document.querySelectorAll('.period-btn');
        periodButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Hapus kelas active dari semua tombol
                periodButtons.forEach(btn => {
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-success');
                });
                
                // Tambahkan kelas active ke tombol yang diklik
                this.classList.remove('btn-outline-success');
                this.classList.add('btn-success');
                
                // Muat data untuk periode yang dipilih
                const period = this.getAttribute('data-period');
                
                // Jika data untuk periode ini belum ada, ambil dari server
                if (!chartData[period]) {
                    // Tampilkan loading state
                    document.querySelector('.chart-container').style.opacity = '0.5';
                    
                    // Kirim request ke server untuk mendapatkan data
                    fetch(`{{ route('superadmin.dashboard') }}?get_chart_data=${period}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Simpan data
                        chartData[period] = data;
                        
                        // Update chart
                        updateChart(period);
                        
                        // Hapus loading state
                        document.querySelector('.chart-container').style.opacity = '1';
                    })
                    .catch(error => {
                        console.error('Error loading chart data:', error);
                        document.querySelector('.chart-container').style.opacity = '1';
                    });
                } else {
                    // Update chart dengan data yang sudah ada
                    updateChart(period);
                }
            });
        });
        
        // Pastikan elemen chart ada sebelum memuat data
        if (document.getElementById('emisiChart')) {
            // Inisialisasi chart dengan data default
            updateChart('1Y');
        } else {
            console.error('Element #emisiChart tidak ditemukan');
        }
        
        // Tambahkan event listener untuk menangani error pada chart
        window.addEventListener('error', function(e) {
            if (e.target.tagName === 'CANVAS') {
                console.error('Error pada chart:', e);
                document.querySelector('.chart-container').innerHTML = '<div class="alert alert-warning">Tidak dapat memuat visualisasi. Silakan refresh halaman.</div>';
            }
        }, true);
    });
</script>

<style>
    /* Styles khusus untuk dashboard Super Admin */
    .card {
        transform: translateY(20px);
        opacity: 0.8;
    }
    
    .bg-gradient-success-light {
        background: linear-gradient(to right, #e6f7ee, #ffffff);
        border-left: 4px solid #198754;
    }
    
    .rounded-4 {
        border-radius: 0.75rem;
    }
    
    .chart-container {
        position: relative;
    }
</style>
@endsection