@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 w-full">
    <!-- Emission Carbon Chart -->
        <div class="col-span-3 bg-white p-6 rounded-xl shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Emission Carbon</h2>
                <div class="flex space-x-2 text-xs">
                    <button class="px-2 py-1 rounded bg-gray-100 hover:bg-gray-200">1M</button>
                    <button class="px-2 py-1 rounded bg-gray-100 hover:bg-gray-200">3M</button>
                    <button class="px-2 py-1 rounded bg-gray-100 hover:bg-gray-200">6M</button>
                    <button class="px-2 py-1 rounded bg-gray-100 hover:bg-gray-200">1Y</button>
                </div>
            </div>
            <div class="relative" style="height: 300px;"> <!-- Tambahkan fixed height di sini -->
                <div class="absolute top-0 left-10 bg-green-100 text-green-800 rounded-full px-2 py-0.5 text-xs font-medium">
                    +23% vs last month
                </div>
                <canvas id="emissionChart"></canvas> <!-- Hapus height attribute dari canvas -->
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
            <a href="#" class="text-green-600 text-sm">See All</a>
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
            <button class="text-sm text-green-600">View Report</button>
        </div>
        <div class="flex justify-center py-4">
            <canvas id="donutChart" height="200"></canvas>
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
        // Konfigurasi Emission Chart (diperbarui)
        const emissionChart = new Chart(document.getElementById('emissionChart'), {
            type: 'line',
            data: {
                labels: ['Feb 1', 'Feb 8', 'Feb 8', 'Feb 8', 'Feb 15', 'Feb 8', 'Feb 22', 'Feb 28'],
                datasets: [{
                    label: 'Carbon Emission',
                    data: [12, 10, 11, 12, 15, 13, 12, 14],
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true, // Fitur responsive diaktifkan
                maintainAspectRatio: false, // Memastikan aspect ratio tidak dipertahankan
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

        const donutChart = new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: ['product', 'degrowth', 'growth'],
                datasets: [{
                    label: 'Emission Type',
                    data: [40, 32, 28],
                    backgroundColor: ['#065f46', '#22C55E', '#86efac'],
                    borderWidth: 0
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                cutout: '75%',
                maintainAspectRatio: false
            }
        });
        
        // Connect the button to open the modal
        const openModalBtn = document.getElementById('openEmisiModal');
        const modal = document.getElementById('emisiModal');
        
        if (openModalBtn && modal) {
            openModalBtn.addEventListener('click', function() {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        }
    });
</script>
@endsection