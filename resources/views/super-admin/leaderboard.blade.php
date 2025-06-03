@extends('layouts.appsuperadmin')

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
        .rank-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            color: white;
            font-weight: bold;
        }
        .rank-1 {
            background: linear-gradient(45deg, #28a745, #20c997);
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }
        .rank-2 {
            background: linear-gradient(45deg, #007bff, #17a2b8);
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }
        .rank-3 {
            background: linear-gradient(45deg, #17a2b8, #20c997);
            box-shadow: 0 4px 10px rgba(23, 162, 184, 0.3);
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
                    <i class="bi bi-trophy me-2"></i>@yield('title')
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-leaf me-1"></i>Peringkat perusahaan berdasarkan emisi karbon dan upaya kompensasi di sistem Ecologix
                </p>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
                <div class="card-header eco-gradient text py-3">
                    <h6 class="m-0 fw-bold text">
                        <i class="bi bi-stars me-2"></i>Peringkat Perusahaan Berdasarkan Emisi Karbon
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-eco table-hover mb-0" id="leaderboardTable">
                            <thead>
                                <tr>
                                    <th class="border-0 fw-bold text-center">
                                        <i class="bi bi-award me-1"></i>Peringkat
                                    </th>
                                    <th class="border-0 fw-bold">
                                        <i class="bi bi-building me-1"></i>Nama Perusahaan
                                    </th>
                                    <th class="border-0 fw-bold text-center">
                                        <i class="bi bi-cloud me-1"></i>Total Emisi (ton CO<sub>2</sub>)
                                    </th>
                                    <th class="border-0 fw-bold text-center">
                                        <i class="bi bi-arrow-down-circle me-1"></i>Total Kompensasi (ton CO<sub>2</sub>)
                                    </th>
                                    <th class="border-0 fw-bold text-center">
                                        <i class="bi bi-exclamation-circle me-1"></i>Sisa Emisi (ton CO<sub>2</sub>)
                                    </th>
                                    <th class="border-0 fw-bold text-center">
                                        <i class="bi bi-graph-up me-1"></i>Persentase Kompensasi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaderboardData as $index => $data)
                                <tr>
                                    <td class="text-center">
                                        @if($index < 3)
                                            <div class="rank-badge rank-{{ $index + 1 }}">
                                                {{ $index + 1 }}
                                            </div>
                                        @else
                                            <span class="text-muted fw-medium">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-building-fill text-success me-2"></i>
                                            <span class="fw-medium">{{ $data->nama_perusahaan }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ number_format($data->total_emisi, 2) }}</td>
                                    <td class="text-center">{{ number_format($data->total_kompensasi, 2) }}</td>
                                    <td class="text-center">
                                        <span class="fw-bold {{ $data->sisa_emisi > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format(max(0, $data->sisa_emisi), 2) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $persentaseKompensasi = $data->total_emisi > 0 ? 
                                                min(100, round(($data->total_kompensasi / $data->total_emisi) * 100, 2)) : 0;
                                        @endphp
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar {{ $persentaseKompensasi == 100 ? 'bg-success' : 'bg-info' }}" 
                                                role="progressbar" 
                                                style="width: {{ $persentaseKompensasi }}%" 
                                                aria-valuenow="{{ $persentaseKompensasi }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="small fw-medium mt-1 d-block {{ $persentaseKompensasi == 100 ? 'text-success' : 'text-info' }}">
                                            {{ $persentaseKompensasi }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-info-circle text-muted mb-2 fs-4"></i>
                                            <span class="text-muted">Tidak ada data perusahaan yang tersedia</span>
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
    <!-- <div class="row">
        <div class="col-12">
            <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
                <div class="card-header eco-gradient text py-3">
                    <h6 class="m-0 fw-bold text">
                        <i class="bi bi-bar-chart-fill me-2"></i>Grafik Perbandingan Emisi Karbon Perusahaan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar p-3">
                        <canvas id="leaderboardChart" style="height: 400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>
@endsection

@section('scripts')
<script>
    // Fungsi untuk format angka
    function number_format(number, decimals, dec_point, thousands_sep) {
        // Format angka dengan pemisah ribuan dan desimal
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
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
        // Inisialisasi DataTable dengan opsi yang disempurnakan
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
            },
            "pageLength": 10,
            "dom": '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
            "responsive": true
        });

        // Persiapkan data untuk chart
        var ctx = document.getElementById("leaderboardChart");
        var labels = [];
        var emisiData = [];
        var kompensasiData = [];
        var sisaData = [];

        @foreach($leaderboardData->take(10) as $data)
            labels.push("{{ $data->nama_perusahaan }}");
            emisiData.push({{ $data->total_emisi }});
            kompensasiData.push({{ $data->total_kompensasi }});
            sisaData.push({{ max(0, $data->sisa_emisi) }});
        @endforeach

        var leaderboardChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Total Emisi",
                        backgroundColor: "rgba(40, 167, 69, 0.8)",
                        borderColor: "rgba(40, 167, 69, 1)",
                        data: emisiData,
                    },
                    {
                        label: "Total Kompensasi",
                        backgroundColor: "rgba(23, 162, 184, 0.8)",
                        borderColor: "rgba(23, 162, 184, 1)",
                        data: kompensasiData,
                    },
                    {
                        label: "Sisa Emisi",
                        backgroundColor: "rgba(220, 53, 69, 0.8)",
                        borderColor: "rgba(220, 53, 69, 1)",
                        data: sisaData,
                    }
                ],
            },
            options: {
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
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 10
                        },
                        maxBarThickness: 25,
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value, index, values) {
                                return number_format(value) + ' ton';
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + number_format(tooltipItem.yLabel) + ' ton CO2';
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Animasi cards pada load
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.transition = 'all 0.3s ease';
                card.style.transform = 'translateY(0)';
                card.style.opacity = '1';
            }, 100 * index);
        });
    });
</script>
@endsection