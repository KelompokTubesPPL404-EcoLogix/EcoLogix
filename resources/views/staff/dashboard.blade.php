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
                    @php
                    // Fungsi untuk mendapatkan data emisi berdasarkan periode
                    function getEmissionData($period) {
                        $user = Auth::user();
                        $kodeStaff = $user->kode_user;
                        $now = now();
                        $labels = [];
                        $data = [];
                        
                        switch($period) {
                            case '1M': // 1 bulan (30 hari terakhir)
                                $startDate = $now->copy()->subDays(30);
                                $groupBy = 'day';
                                $format = 'd/m';
                                break;
                            case '3M': // 3 bulan
                                $startDate = $now->copy()->subMonths(3);
                                $groupBy = 'week';
                                $format = 'W/m';
                                break;
                            case '6M': // 6 bulan
                                $startDate = $now->copy()->subMonths(6);
                                $groupBy = 'month';
                                $format = 'M';
                                break;
                            case '1Y': // 1 tahun
                                $startDate = $now->copy()->subYear();
                                $groupBy = 'month';
                                $format = 'M Y';
                                break;
                            default:
                                $startDate = $now->copy()->subDays(30);
                                $groupBy = 'day';
                                $format = 'd/m';
                        }
                        
                        // Ambil data emisi karbon
                        $emissions = \App\Models\EmisiKarbon::where('kode_staff', $kodeStaff)
                            ->where('tanggal_emisi', '>=', $startDate)
                            ->orderBy('tanggal_emisi')
                            ->get();
                        
                        // Kelompokkan data berdasarkan periode
                        $groupedData = [];
                        
                        foreach ($emissions as $emission) {
                            $date = \Carbon\Carbon::parse($emission->tanggal_emisi);
                            $key = '';
                            
                            if ($groupBy == 'day') {
                                $key = $date->format('Y-m-d');
                                $label = $date->format($format);
                            } elseif ($groupBy == 'week') {
                                $key = $date->year . '-' . $date->week;
                                $label = $date->format($format);
                            } elseif ($groupBy == 'month') {
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
                        
                        // Hitung perbandingan dengan periode sebelumnya
                        $currentTotal = array_sum($data);
                        
                        // Ambil data periode sebelumnya untuk perbandingan
                        $previousStartDate = $startDate->copy()->subDays($startDate->diffInDays($now));
                        $previousEmissions = \App\Models\EmisiKarbon::where('kode_staff', $kodeStaff)
                            ->where('tanggal_emisi', '>=', $previousStartDate)
                            ->where('tanggal_emisi', '<', $startDate)
                            ->sum('kadar_emisi_karbon');
                        
                        $comparison = 0;
                        if ($previousEmissions > 0) {
                            $comparison = round((($currentTotal - $previousEmissions) / $previousEmissions) * 100, 0);
                        }
                        
                        return [
                            'labels' => $labels,
                            'data' => $data,
                            'comparison' => $comparison
                        ];
                    }
                    
                    // Ambil data default (1 bulan)
                    $defaultData = getEmissionData('1M');
                    @endphp
                    
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Data chart dari server
                        const chartData = {
                            '1M': {
                                labels: {!! json_encode($defaultData['labels']) !!},
                                data: {!! json_encode($defaultData['data']) !!},
                                comparison: {!! json_encode($defaultData['comparison']) !!}
                            }
                        };
                        
                        // Inisialisasi chart
                        const ctx = document.getElementById('emissionChart').getContext('2d');
                        window.emissionChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: chartData['1M'].labels,
                                datasets: [{
                                    label: 'Emisi Karbon (kg CO2e)',
                                    data: chartData['1M'].data,
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
                                const period = this.getAttribute('data-period');
                                activePeriod = period;
                                
                                // Jika data untuk periode ini belum ada, ambil dari server
                                if (!chartData[period]) {
                                    // Tampilkan loading state
                                    document.getElementById('emissionChart').style.opacity = '0.5';
                                    
                                    // Kirim request ke server untuk mendapatkan data
                                    fetch(`{{ route('staff.dashboard') }}?get_chart_data=${period}`, {
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
                                        document.getElementById('emissionChart').style.opacity = '1';
                                    })
                                    .catch(error => {
                                        console.error('Error loading chart data:', error);
                                        document.getElementById('emissionChart').style.opacity = '1';
                                    });
                                } else {
                                    // Update chart dengan data yang sudah ada
                                    updateChart(period);
                                }
                            });
                        });
                        
                        // Fungsi untuk update chart
                        function updateChart(period) {
                            const data = chartData[period];
                            
                            // Update chart
                            window.emissionChart.data.labels = data.labels;
                            window.emissionChart.data.datasets[0].data = data.data;
                            window.emissionChart.update();
                            
                            // Update comparison text
                            updateComparisonText(period, data.comparison);
                        }
                        
                        // Fungsi untuk update teks perbandingan
                        function updateComparisonText(period, comparisonValue) {
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
                        }
                        
                        // Set teks perbandingan awal
                        updateComparisonText('1M', chartData['1M'].comparison);
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
@php
// Fungsi untuk mendapatkan data kategori emisi
function getCategoryData() {
    $user = Auth::user();
    $kodeStaff = $user->kode_user;
    
    // Ambil data kategori emisi
    $categories = ['Produk', 'Energi', 'Transportasi'];
    $colors = ['#198754', '#0dcaf0', '#0d6efd'];
    $data = [];
    $percentages = [];
    
    // Hitung total emisi
    $totalEmisi = \App\Models\EmisiKarbon::where('kode_staff', $kodeStaff)->count();
    
    // Hitung jumlah per kategori
    foreach ($categories as $index => $category) {
        $count = \App\Models\EmisiKarbon::where('kode_staff', $kodeStaff)
            ->where('kategori_emisi_karbon', $category)
            ->count();
        
        $data[] = $count;
        $percentages[] = $totalEmisi > 0 ? round(($count / $totalEmisi) * 100, 0) : 0;
    }
    
    return [
        'labels' => $categories,
        'data' => $data,
        'colors' => $colors,
        'percentages' => $percentages
    ];
}

// Ambil data kategori
$categoryData = getCategoryData();
@endphp
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Donut Chart
        const donutChart = new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryData['labels']) !!},
                datasets: [{
                    label: 'Tipe Emisi',
                    data: {!! json_encode($categoryData['data']) !!},
                    backgroundColor: {!! json_encode($categoryData['colors']) !!},
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