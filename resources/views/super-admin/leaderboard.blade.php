@extends('layouts.appsuperadmin')

@section('title', 'Leaderboard Perusahaan')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Leaderboard Perusahaan</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Peringkat Perusahaan Berdasarkan Emisi Karbon</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="leaderboardTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Nama Perusahaan</th>
                                    <th>Total Emisi Karbon (ton CO<sub>2</sub>)</th>
                                    <th>Total Kompensasi (ton CO<sub>2</sub>)</th>
                                    <th>Sisa Emisi (ton CO<sub>2</sub>)</th>
                                    <th>Persentase Kompensasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaderboardData as $index => $data)
                                <tr>
                                    <td class="text-center">
                                        @if($index < 3)
                                            <span class="badge badge-{{ $index == 0 ? 'success' : ($index == 1 ? 'primary' : 'info') }} p-2">
                                                {{ $index + 1 }}
                                            </span>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td>{{ $data->nama_perusahaan }}</td>
                                    <td class="text-right">{{ number_format($data->total_emisi, 2) }}</td>
                                    <td class="text-right">{{ number_format($data->total_kompensasi, 2) }}</td>
                                    <td class="text-right">
                                        <span class="font-weight-bold {{ $data->sisa_emisi > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($data->sisa_emisi, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $persentaseKompensasi = $data->total_emisi > 0 ? 
                                                min(100, round(($data->total_kompensasi / $data->total_emisi) * 100, 2)) : 0;
                                        @endphp
                                        <div class="progress">
                                            <div class="progress-bar {{ $persentaseKompensasi == 100 ? 'bg-success' : 'bg-info' }}" 
                                                role="progressbar" 
                                                style="width: {{ $persentaseKompensasi }}%" 
                                                aria-valuenow="{{ $persentaseKompensasi }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                {{ $persentaseKompensasi }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data perusahaan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Perbandingan Emisi Karbon Perusahaan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="leaderboardChart"></canvas>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>
@endsection

@section('scripts')
<script>
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
        var labels = [];
        var emisiData = [];
        var kompensasiData = [];
        var sisaData = [];

        @foreach($leaderboardData->take(10) as $data)
            labels.push("{{ $data->nama_perusahaan }}");
            emisiData.push({{ $data->total_emisi }});
            kompensasiData.push({{ $data->total_kompensasi }});
            sisaData.push({{ $data->sisa_emisi }});
        @endforeach

        var leaderboardChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Total Emisi",
                        backgroundColor: "rgba(78, 115, 223, 0.8)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        data: emisiData,
                    },
                    {
                        label: "Total Kompensasi",
                        backgroundColor: "rgba(28, 200, 138, 0.8)",
                        borderColor: "rgba(28, 200, 138, 1)",
                        data: kompensasiData,
                    },
                    {
                        label: "Sisa Emisi",
                        backgroundColor: "rgba(231, 74, 59, 0.8)",
                        borderColor: "rgba(231, 74, 59, 1)",
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
            }
        });
    });
</script>
@endsection