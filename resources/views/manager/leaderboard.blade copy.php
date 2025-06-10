@extends('layouts.manager')

@section('title', 'Leaderboard Perusahaan')

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
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-left: 4px solid #28a745;
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
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
        /* Peringkat Badge Colors */
        .rank-gold {
            background: linear-gradient(45deg, #FFD700, #FFA500) !important;
            color: #fff !important;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
            font-weight: bold;
        }
        .rank-silver {
            background: linear-gradient(45deg, #C0C0C0, #A9A9A9) !important;
            color: #333 !important;
            box-shadow: 0 4px 15px rgba(192, 192, 192, 0.4);
            font-weight: bold;
        }
        .rank-bronze {
            background: linear-gradient(45deg, #CD7F32, #B87333) !important;
            color: #fff !important;
            box-shadow: 0 4px 15px rgba(205, 127, 50, 0.4);
            font-weight: bold;
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
                    <i class="bi bi-trophy me-2"></i>Leaderboard Perusahaan
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-leaf me-1"></i>Peringkat perusahaan berdasarkan performa emisi karbon dan kompensasi
                </p>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Peringkat Perusahaan Saat Ini -->
        <div class="col-12 mb-4">
            <div class="stats-card card border-0 shadow-lg rounded-3 h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Peringkat Perusahaan Anda</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if($perusahaanRank > 0)
                                    Peringkat #{{ $perusahaanRank }} dari {{ count($leaderboardData) }} Perusahaan
                                @else
                                    Belum ada data emisi yang disetujui
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-trophy fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
                <div class="card-header eco-gradient text-white py-3">
                    <h6 class="m-0 fw-bold text-success">
                        <i class="bi bi-stars me-2"></i>Peringkat Perusahaan Berdasarkan Emisi Karbon
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-eco table-hover mb-0" id="leaderboardTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="border-0 fw-bold text-center">
                                        <i class="bi bi-award me-1"></i>Peringkat
                                    </th>
                                    <th class="border-0 fw-bold">
                                        <i class="bi bi-building me-1"></i>Nama Perusahaan
                                    </th>
                                    <th class="border-0 fw-bold text-center">
                                        <i class="bi bi-cloud me-1"></i>Total Emisi Karbon (ton CO<sub>2</sub>)
                                    </th>
                                    <th class="border-0 fw-bold text-center">
                                        <i class="bi bi-arrow-repeat me-1"></i>Total Kompensasi (ton CO<sub>2</sub>)
                                    </th>
                                    <th class="border-0 fw-bold text-center">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Sisa Emisi (ton CO<sub>2</sub>)
                                    </th>
                                    <th class="border-0 fw-bold text-center">
                                        <i class="bi bi-percent me-1"></i>Persentase Kompensasi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaderboardData as $index => $data)
                                <tr class="{{ $data->kode_perusahaan === Auth::user()->kode_perusahaan ? 'table-success' : '' }}">
                                    <td class="text-center">
                                        @if($index < 3)
                                            @if($index == 0)
                                                <span class="badge rank-gold p-2">
                                                    <i class="bi bi-trophy-fill me-1"></i>{{ $index + 1 }}
                                                </span>
                                            @elseif($index == 1)
                                                <span class="badge rank-silver p-2">
                                                    <i class="bi bi-trophy-fill me-1"></i>{{ $index + 1 }}
                                                </span>
                                            @else
                                                <span class="badge rank-bronze p-2">
                                                    <i class="bi bi-trophy-fill me-1"></i>{{ $index + 1 }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="fw-bold text-success">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-building-fill text-success me-2"></i>
                                            <div>
                                                <div class="fw-bold text-success">{{ $data->nama_perusahaan }}</div>
                                                @if($data->kode_perusahaan === Auth::user()->kode_perusahaan)
                                                    <span class="badge bg-success text-white mt-1">
                                                        <i class="bi bi-star-fill me-1"></i>Perusahaan Anda
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cloud-fill text-success me-2"></i>
                                            <span class="fw-bold">{{ number_format($data->total_emisi, 2) }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="bi bi-arrow-repeat text-info me-2"></i>
                                            <span class="fw-bold">{{ number_format($data->total_kompensasi, 2) }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="bi bi-exclamation-triangle {{ $data->sisa_emisi > 0 ? 'text-danger' : 'text-success' }} me-2"></i>
                                            <span class="fw-bold {{ $data->sisa_emisi > 0 ? 'text-danger' : 'text-success' }}">
                                                {{ number_format(max(0, $data->sisa_emisi), 2) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $persentaseKompensasi = $data->total_emisi > 0 ? 
                                                min(100, round(($data->total_kompensasi / $data->total_emisi) * 100, 2)) : 0;
                                        @endphp
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="progress me-2" style="width: 100px; height: 25px;">
                                                <div class="progress-bar {{ $persentaseKompensasi == 100 ? 'bg-success' : 'bg-info' }}" 
                                                    role="progressbar" 
                                                    style="width: {{ $persentaseKompensasi }}%" 
                                                    aria-valuenow="{{ $persentaseKompensasi }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span class="fw-bold text-success">{{ $persentaseKompensasi }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox display-1 d-block mb-3 text-success"></i>
                                            <h5>Tidak ada data perusahaan</h5>
                                            <p>Belum ada data leaderboard yang tersedia</p>
                                        </div>
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

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
                <div class="card-header eco-gradient text-white py-3">
                    <h6 class="m-0 fw-bold text-success">
                        <i class="bi bi-bar-chart-fill me-2"></i>Grafik Perbandingan Emisi Karbon Perusahaan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar" style="height: 400px;">
                        <canvas id="leaderboardChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
                <div class="card-header eco-gradient text-white py-3">
                    <h6 class="m-0 fw-bold text-success">
                        <i class="bi bi-lightbulb-fill me-2"></i>Tips Mengurangi Emisi Karbon
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="stats-card card border-0 shadow-sm h-100 rounded-3">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Efisiensi Energi</div>
                                            <div class="h6 mb-0 text-gray-800">
                                                Tingkatkan efisiensi energi dengan menggunakan peralatan hemat energi dan mengoptimalkan penggunaan listrik di perusahaan Anda.
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="bi bi-lightbulb fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="stats-card card border-0 shadow-sm h-100 rounded-3">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Kompensasi Karbon</div>
                                            <div class="h6 mb-0 text-gray-800">
                                                Lakukan kompensasi emisi karbon dengan membeli carbon credit dari penyedia yang terpercaya untuk menyeimbangkan emisi yang tidak dapat dihindari.
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="bi bi-leaf fa-2x text-info"></i>
                                        </div>
                                    </div>
                                </div>
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
<script>
    // Function to format numbers
    function number_format(number, decimals = 0, dec_point = '.', thousands_sep = ',') {
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    $(document).ready(function() {
        $('#leaderboardTable').DataTable({
            "order": [[ 0, "asc" ]],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data yang tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
            }
        });

        // Persiapkan data untuk chart
        var ctx = document.getElementById("leaderboardChart");
        
        if (ctx) {
            var labels = [];
            var emisiData = [];
            var kompensasiData = [];
            var sisaData = [];
            var backgroundColors = [];

            @foreach($leaderboardData->take(10) as $data)
                labels.push("{{ $data->nama_perusahaan }}");
                emisiData.push({{ $data->total_emisi ?? 0 }});
                kompensasiData.push({{ $data->total_kompensasi ?? 0 }});
                sisaData.push({{ max(0, $data->sisa_emisi ?? 0) }});
                
                // Highlight perusahaan saat ini dengan warna berbeda
                @if($data->kode_perusahaan === Auth::user()->kode_perusahaan)
                    backgroundColors.push('rgba(40, 167, 69, 0.8)');
                @else
                    backgroundColors.push('rgba(40, 167, 69, 0.4)');
                @endif
            @endforeach

            var leaderboardChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "Total Emisi",
                            backgroundColor: backgroundColors,
                            borderColor: "rgba(40, 167, 69, 1)",
                            borderWidth: 1,
                            data: emisiData,
                        },
                        {
                            label: "Total Kompensasi",
                            backgroundColor: "rgba(32, 201, 151, 0.8)",
                            borderColor: "rgba(32, 201, 151, 1)",
                            borderWidth: 1,
                            data: kompensasiData,
                        },
                        {
                            label: "Sisa Emisi",
                            backgroundColor: "rgba(231, 74, 59, 0.8)",
                            borderColor: "rgba(231, 74, 59, 1)",
                            borderWidth: 1,
                            data: sisaData,
                        }
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 10
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                callback: function(value) {
                                    return number_format(value) + ' ton';
                                }
                            },
                            grid: {
                                color: "rgb(234, 236, 244)",
                                borderDash: [2],
                                drawBorder: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            titleMarginBottom: 10,
                            titleColor: '#6e707e',
                            titleFont: {
                                size: 14
                            },
                            backgroundColor: "rgb(255,255,255)",
                            bodyColor: "#858796",
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    var datasetLabel = context.dataset.label || '';
                                    return datasetLabel + ': ' + number_format(context.raw) + ' ton CO₂';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection