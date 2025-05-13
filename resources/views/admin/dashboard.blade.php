@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Emission Carbon</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="text-success">
                            <span>+23%</span> vs last month
                        </div>
                        <div class="period-selector">
                            <button class="btn btn-sm">1M</button>
                            <button class="btn btn-sm">3M</button>
                            <button class="btn btn-sm">6M</button>
                            <button class="btn btn-sm">1Y</button>
                        </div>
                    </div>
                    <div class="chart-container" style="height: 300px; width: 100%;">
                        <canvas id="emissionChart"></canvas>
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
                                @for ($i = 1; $i <= 6; $i++)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>01/01/25</td>
                                    <td>sampah</td>
                                    <td>limbah</td>
                                    <td>0.05 kg</td>
                                    <td>0.05 kg CO₂e</td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="mb-1">49.65 ton CO₂e</h3>
                    <p class="text-muted">Total Carbon Emission</p>
                    <div class="d-flex mb-3">
                        <span class="badge bg-success me-2">+20%</span>
                        <small class="text-muted">since last month</small>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="mb-1">10</h3>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Line chart for emissions
        const emissionCtx = document.getElementById('emissionChart').getContext('2d');
        const emissionChart = new Chart(emissionCtx, {
            type: 'line',
            data: {
                labels: ['Feb 1', 'Feb 8', 'Feb 15', 'Feb 22', 'Feb 28'],
                datasets: [{
                    label: 'Carbon Emission',
                    data: [40, 35, 45, 40, 50],
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderColor: '#28a745',
                    borderWidth: 2,
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
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Donut chart for emission types
        const typeCtx = document.getElementById('emissionTypeChart').getContext('2d');
        const typeChart = new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Product', 'Shipment', 'Growth'],
                datasets: [{
                    data: [40, 32, 28],
                    backgroundColor: ['#28a745', '#007bff', '#ffc107']
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
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