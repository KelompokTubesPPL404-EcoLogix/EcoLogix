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
@php
// Fungsi untuk mendapatkan data emisi berdasarkan periode
function getEmissionData($period) {
    $user = Auth::user();
    $kodePerusahaan = $user->kode_perusahaan;
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
    $emissions = \App\Models\EmisiKarbon::where('kode_staff', 'like', $kodePerusahaan . '%')
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
    $previousEmissions = \App\Models\EmisiKarbon::where('kode_staff', 'like', $kodePerusahaan . '%')
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

// Fungsi untuk mendapatkan data kategori emisi
function getCategoryData() {
    $user = Auth::user();
    $kodePerusahaan = $user->kode_perusahaan;
    
    // Ambil data kategori emisi
    $categories = ['Produk', 'Energi', 'Transportasi'];
    $colors = ['#198754', '#0d6efd', '#ffc107'];
    $data = [];
    $percentages = [];
    
    // Hitung total emisi
    $totalEmisi = \App\Models\EmisiKarbon::where('kode_staff', 'like', $kodePerusahaan . '%')->count();
    
    // Hitung jumlah per kategori
    foreach ($categories as $index => $category) {
        $count = \App\Models\EmisiKarbon::where('kode_staff', 'like', $kodePerusahaan . '%')
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

// Ambil data default (1 bulan)
$defaultData = getEmissionData('1M');
$categoryData = getCategoryData();
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
                    setActivePeriod(period, index);
                    
                    // Jika data untuk periode ini belum ada, ambil dari server
                    if (!chartData[period]) {
                        // Tampilkan loading state
                        document.getElementById('emisiChart').style.opacity = '0.5';
                        
                        // Kirim request ke server untuk mendapatkan data
                        fetch(`{{ route('admin.dashboard') }}?get_chart_data=${period}`, {
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
                            document.getElementById('emisiChart').style.opacity = '1';
                        })
                        .catch(error => {
                            console.error('Error loading chart data:', error);
                            document.getElementById('emisiChart').style.opacity = '1';
                        });
                    } else {
                        // Update chart dengan data yang sudah ada
                        updateChart(period);
                    }
                }
            });
        });

        // Set active period and update UI
        function setActivePeriod(period, index) {
            activePeriod = period;
            periodBtns.forEach((btn, i) => {
                if (i === index) {
                    btn.classList.remove('btn-outline-success');
                    btn.classList.add('btn-success', 'active');
                } else {
                    btn.classList.remove('btn-success', 'active');
                    btn.classList.add('btn-outline-success');
                }
            });
        }
        
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
        }

        // Initialize Donut Chart
        const donutChart = new Chart(document.getElementById('emissionTypeChart'), {
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
                cutout: '70%'
            }
        });
        
        // Update persentase di legend
        const percentages = {!! json_encode($categoryData['percentages']) !!};
        const legendElements = document.querySelectorAll('.chart-legend .d-flex > div');
        const maxCategories = Math.min({!! json_encode($categoryData['labels']) !!}.length, 3);
        
        for (let i = 0; i < maxCategories; i++) {
            if (legendElements[i]) {
                const label = legendElements[i].querySelector('span + div');
                if (label) {
                    label.textContent = `${percentages[i]}%`;
                }
            }
        }
        
        // Inisialisasi chart dengan data default
        updateChart('1M');
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