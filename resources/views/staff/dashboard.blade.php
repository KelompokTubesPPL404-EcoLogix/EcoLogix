@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 w-full">
    <!-- Emission Carbon Chart -->
        <div class="col-span-3 bg-white p-6 rounded-xl shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Emission Carbon</h2>
                <div class="flex space-x-2 text-xs">
                    <button class="px-2 py-1 rounded period-btn active" data-period="1M">1M</button>
                    <button class="px-2 py-1 rounded period-btn" data-period="3M">3M</button>
                    <button class="px-2 py-1 rounded period-btn" data-period="6M">6M</button>
                    <button class="px-2 py-1 rounded period-btn" data-period="1Y">1Y</button>
                </div>
            </div>
            <div class="relative" style="height: 300px;">
                <div class="absolute top-0 left-10 bg-green-100 text-green-800 rounded-full px-2 py-0.5 text-xs font-medium">
                    +23% vs last month
                </div>
                <canvas id="emissionChart"></canvas>
            </div>
        </div>

    <!-- Input Emisi Karbon Card -->
    <div class="flex flex-col gap-4">
        <!-- Input Button Card -->
        <div class="bg-white p-6 rounded-xl shadow h-auto">
            <button id="openEmisiModal" class="bg-green-600 hover:bg-green-700 text-white font-semibold w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Input Emisi Karbon
            </button>
        </div>
        
        <!-- Total Carbon Emission Card -->
        <div class="bg-white p-6 rounded-xl shadow h-auto">
            <div>
                <p class="text-3xl font-bold">49.65 <span class="text-sm">ton Co₂e</span></p>
                <p class="text-gray-600 text-sm">Total Carbon Emision</p>
                <p class="text-green-600 text-xs mt-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                    </svg>
                    1.20% since last month
                </p>
            </div>
        </div>
        
        <!-- Input Total Card -->
        <div class="bg-white p-6 rounded-xl shadow h-auto">
            <div>
                <p class="text-xl font-semibold">6</p>
                <p class="text-sm text-gray-500">Input Total</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabel dan Chart Donut -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6 w-full">
    <!-- Input Emission History Table -->
    <div class="col-span-2 bg-white p-6 rounded-xl shadow overflow-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Input Emision History</h2>
            <a href="{{ url('/staff/history') }}" class="text-green-600 text-sm">See All</a>
        </div>
        <table class="w-full text-sm">
            <thead class="text-left text-gray-600 border-b">
                <tr>
                    <th class="pb-3">no</th>
                    <th class="pb-3">date</th>
                    <th class="pb-3">emision category</th>
                    <th class="pb-3">sub category</th>
                    <th class="pb-3">activity score</th>
                    <th class="pb-3">emission levels</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 1; $i <= 6; $i++)
                <tr class="border-b last:border-none">
                    <td class="py-3">{{ $i }}</td>
                    <td class="py-3">01/01/25</td>
                    <td class="py-3">sampah</td>
                    <td class="py-3">limbah</td>
                    <td class="py-3">0.05 kg</td>
                    <td class="py-3">0.05 kg Co₂e</td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <!-- Emission Type Chart -->
    <div class="bg-white p-6 rounded-xl shadow">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-lg font-semibold">Emission Type</h2>
                <p class="text-xs text-gray-500">January 2025</p>
            </div>
            
        </div>
        <div class="flex justify-center py-4" style="height: 200px;">
            <canvas id="donutChart"></canvas>
        </div>
        <div class="mt-4 text-sm space-y-1">
            <p class="flex items-center"><span class="inline-block w-3 h-3 bg-green-800 mr-2 rounded-full"></span> product - 40%</p>
            <p class="flex items-center"><span class="inline-block w-3 h-3 bg-green-600 mr-2 rounded-full"></span> degrowth - 32%</p>
            <p class="flex items-center"><span class="inline-block w-3 h-3 bg-green-400 mr-2 rounded-full"></span> growth - 28%</p>
        </div>
    </div>
</div>

<!-- Include Modal -->
@include('modals.input-emisi')

<!-- ChartJS script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize chart
        const emissionChart = new Chart(document.getElementById('emissionChart'), {
            type: 'line',
            data: {
                labels: [], // Empty initially, will be populated
                datasets: [{
                    label: 'Carbon Emission',
                    data: [], // Empty initially
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: false,
                        grid: {
                            display: true,
                            drawBorder: false
                        },
                        ticks: {
                            display: false
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

        // Period buttons functionality
        const periodBtns = document.querySelectorAll('.period-btn');
        let activePeriod = '1M'; // Default active period
        
        // Initialize with default period
        loadChartData(activePeriod);
        
        // Set up button event listeners
        periodBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const period = this.getAttribute('data-period');
                if (period !== activePeriod) {
                    setActivePeriod(period);
                    loadChartData(period);
                }
            });
        });

        // Set active period and update UI
        function setActivePeriod(period) {
            activePeriod = period;
            periodBtns.forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('data-period') === period);
            });
        }

        // Load chart data for selected period
        async function loadChartData(period) {
            try {
                // Show loading state (optional)
                document.getElementById('emissionChart').style.opacity = '0.5';
                
                // In a real app, you would fetch from your API:
                // const response = await fetch(`/api/emission-data?period=${period}`);
                // const data = await response.json();
                
                // Simulated API response - replace with actual fetch in production
                const data = await simulateFetchData(period);
                
                // Update chart
                emissionChart.data.labels = data.labels;
                emissionChart.data.datasets[0].data = data.values;
                emissionChart.update();
                
                // Update comparison text
                updateComparisonText(data.comparison);
                
            } catch (error) {
                console.error('Error loading chart data:', error);
                // Show error to user (optional)
            } finally {
                document.getElementById('emissionChart').style.opacity = '1';
            }
        }

        // Simulate API fetch - REMOVE IN PRODUCTION
        function simulateFetchData(period) {
            return new Promise(resolve => {
                setTimeout(() => {
                    const data = {
                        '1M': {
                            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                            values: [12, 10, 11, 12],
                            comparison: '+23% vs last month'
                        },
                        '3M': {
                            labels: ['Month 1', 'Month 2', 'Month 3'],
                            values: [10, 12, 14],
                            comparison: '+15% vs last quarter'
                        },
                        '6M': {
                            labels: ['Q1', 'Q2'],
                            values: [8, 10],
                            comparison: '+12% vs last half year'
                        },
                        '1Y': {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                            values: [5, 8, 10, 12, 15, 18],
                            comparison: '+20% vs last year'
                        }
                    };
                    resolve(data[period]);
                }, 300); // Simulate network delay
            });
        }

        // Update the comparison text
        function updateComparisonText(text) {
            const comparisonElement = document.querySelector('.absolute.top-0.left-10');
            if (comparisonElement) {
                comparisonElement.textContent = text;
            }
        }

        // Initialize Donut Chart
        const donutChart = new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: ['product', 'degrowth', 'growth'],
                datasets: [{
                    label: 'Emission Type',
                    data: [40, 32, 28],
                    backgroundColor: [
                        '#065f46', // green-800
                        '#22C55E', // green-500
                        '#86efac'  // green-300
                    ],
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