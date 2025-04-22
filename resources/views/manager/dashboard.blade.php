@extends('layouts.manager')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div>
            <span class="text-success">Periode: </span>
            <select class="form-select form-select-sm d-inline-block w-auto">
                <option>Januari 2025</option>
                <option>Februari 2025</option>
                <option>Maret 2025</option>
                <option selected>April 2025</option>
            </select>
        </div>
    </div>

    <!-- Ringkasan Kartu -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Emisi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">245 Ton CO<sub>2</sub>e</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cloud-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Emisi Dikompensasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">178 Ton CO<sub>2</sub>e</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Persentase Kompensasi
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">72%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 72%"
                                            aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard-data fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Carbon Credit Tersedia
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">125 Kredit</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-dollar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Grafik Emisi -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Emisi Karbon per Bulan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="emisiChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Sumber Emisi</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="sumberEmisiChart" height="300"></canvas>
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
    // Grafik Emisi Karbon per Bulan
    const emisiCtx = document.getElementById('emisiChart').getContext('2d');
    const emisiChart = new Chart(emisiCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Emisi Karbon (Ton CO2e)',
                data: [210, 225, 235, 245, 0, 0, 0, 0, 0, 0, 0, 0],
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

    // Grafik Sumber Emisi
    const sumberEmisiCtx = document.getElementById('sumberEmisiChart').getContext('2d');
    const sumberEmisiChart = new Chart(sumberEmisiCtx, {
        type: 'doughnut',
        data: {
            labels: ['Transportasi', 'Listrik', 'Produksi', 'Limbah', 'Lainnya'],
            datasets: [{
                label: 'Emisi (Ton CO2e)',
                data: [85, 65, 55, 25, 15],
                backgroundColor: [
                    '#28a745',
                    '#4e73df',
                    '#ffc107',
                    '#e74a3b',
                    '#36b9cc'
                ],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                }
            },
            cutout: '70%',
        }
    });
</script>
@endsection