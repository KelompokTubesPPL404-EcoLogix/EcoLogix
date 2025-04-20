<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EcoLogix Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f9fafb;
        }
        .logo {
            font-family: 'Montserrat', sans-serif;
        }
        .shadow-custom {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <!-- Navbar -->
    <div class="bg-white shadow-sm px-6 py-3 flex justify-between items-center sticky top-0 z-10 border-b border-gray-100">
        <div class="flex items-center">
            <div class="text-2xl font-bold text-green-700 flex items-center">
                <img src="ECOLOGIX.png" class="h-8 mr-2">
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button class="relative p-2 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>
            <img src="https://i.pravatar.cc/40" alt="profile" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
        </div>
    </div>

    <!-- Konten -->
    <main class="w-full px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>
</body>
</html>