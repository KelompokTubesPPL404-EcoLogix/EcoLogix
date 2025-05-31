<?php

namespace App\Http\Controllers;

use App\Models\EmisiKarbon;
use App\Models\User;
use App\Models\Perusahaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardControllerNew extends Controller
{
    /**
     * Menampilkan dashboard admin
     *
     * @return \Illuminate\View\View
     */
    public function adminDashboard()
    {
        // Data untuk chart emisi bulanan (default 1 bulan)
        $emisiChartData = $this->prepareEmisiChartData('1M');
        
        // Data untuk chart kategori emisi
        $categoryChartData = $this->prepareEmisiCategoryData();
        
        // Data statistik dashboard
        $dashboardStats = $this->prepareDashboardStats();
        
        // Ambil data emisi terbaru yang approved
        $latestEmissions = EmisiKarbon::where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')
            ->where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();
        
        return view('admin.dashboard', compact(
            'emisiChartData', 
            'categoryChartData', 
            'dashboardStats', 
            'latestEmissions'
        ));
    }
    
    /**
     * Menampilkan dashboard manager
     *
     * @return \Illuminate\View\View
     */
    public function managerDashboard()
    {
        // Data untuk chart emisi bulanan (default 1 bulan)
        $emisiChartData = $this->prepareEmisiChartData('1M');
        
        // Data untuk chart kategori emisi
        $categoryChartData = $this->prepareEmisiCategoryData();
        
        // Data statistik dashboard
        $dashboardStats = $this->prepareDashboardStats();
        
        // Ambil data emisi terbaru yang approved
        $latestEmissions = EmisiKarbon::where('kode_staff', 'like', Auth::user()->kode_perusahaan . '%')
            ->where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();
        
        return view('manager.dashboard', compact(
            'emisiChartData', 
            'categoryChartData', 
            'dashboardStats', 
            'latestEmissions'
        ));
    }
    
    /**
     * Menampilkan dashboard staff
     *
     * @return \Illuminate\View\View
     */
    public function staffDashboard()
    {
        // Data untuk chart emisi personal (default 1 bulan)
        $emisiChartData = $this->prepareEmisiChartData('1M');
        
        // Data untuk chart kategori emisi
        $categoryChartData = $this->prepareEmisiCategoryData();
        
        // Data statistik dashboard
        $dashboardStats = $this->prepareDashboardStats();
        
        // Ambil data emisi terbaru milik staff
        $latestEmissions = EmisiKarbon::where('kode_staff', Auth::user()->kode_user)
            ->latest()
            ->take(6)
            ->get();
        
        return view('staff.dashboard', compact(
            'emisiChartData', 
            'categoryChartData', 
            'dashboardStats', 
            'latestEmissions'
        ));
    }
    
    /**
     * Menampilkan dashboard super admin
     *
     * @return \Illuminate\View\View
     */
    public function superAdminDashboard()
    {
        // Data untuk chart emisi keseluruhan (default 1 bulan)
        $emisiChartData = $this->prepareEmisiChartData('1M');
        
        // Data untuk chart perusahaan emisi
        $companyChartData = $this->prepareEmisiCompanyData();
        
        // Data statistik dashboard
        $dashboardStats = $this->prepareDashboardStats();
        
        // Ambil data perusahaan terbaru
        $latestCompanies = Perusahaan::latest()
            ->take(5)
            ->get();
        
        return view('super-admin.dashboard', compact(
            'emisiChartData', 
            'companyChartData', 
            'dashboardStats', 
            'latestCompanies'
        ));
    }
    
    /**
     * Mempersiapkan data emisi untuk chart berdasarkan periode
     *
     * @param  string  $period
     * @return array
     */
    private function prepareEmisiChartData($period = '1M')
    {
        $role = Auth::user()->role;
        $kodeUser = Auth::user()->kode_user;
        $kodePerusahaan = Auth::user()->kode_perusahaan;
        
        $query = EmisiKarbon::query();
        
        if ($role === 'staff') {
            $query->where('kode_staff', $kodeUser);
        } elseif ($role === 'admin' || $role === 'manager') {
            $query->where('kode_staff', 'like', $kodePerusahaan . '%');
            // Admin dan manager hanya melihat data yang sudah disetujui
            $query->where('status', 'approved');
        } elseif ($role === 'super_admin') {
            // Super Admin dapat melihat semua data yang sudah disetujui dari semua perusahaan
            $query->where('status', 'approved');
        }
        
        // Filter berdasarkan periode
        switch ($period) {
            case '1M': // 1 bulan
                $startDate = Carbon::now()->subMonth();
                $groupBy = 'week';
                $labels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
                break;
            case '3M': // 3 bulan
                $startDate = Carbon::now()->subMonths(3);
                $groupBy = 'month';
                $labels = [Carbon::now()->subMonths(2)->format('M'), Carbon::now()->subMonth()->format('M'), Carbon::now()->format('M')];
                break;
            case '6M': // 6 bulan
                $startDate = Carbon::now()->subMonths(6);
                $groupBy = 'month';
                $labels = [];
                for ($i = 5; $i >= 0; $i--) {
                    $labels[] = Carbon::now()->subMonths($i)->format('M');
                }
                break;
            case '1Y': // 1 tahun
                $startDate = Carbon::now()->subYear();
                $groupBy = 'month';
                $labels = [];
                for ($i = 11; $i >= 0; $i--) {
                    $labels[] = Carbon::now()->subMonths($i)->format('M');
                }
                break;
            case 'ALL': // Semua data (untuk Super Admin)
                $startDate = Carbon::now()->subYears(5); // Ambil data 5 tahun terakhir
                $groupBy = 'year';
                $labels = [];
                $currentYear = Carbon::now()->year;
                for ($i = 5; $i >= 0; $i--) {
                    $labels[] = (string)($currentYear - $i);
                }
                break;
            default:
                $startDate = Carbon::now()->subMonth();
                $groupBy = 'week';
                $labels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
        }
        
        $query->where('tanggal_emisi', '>=', $startDate);
        
        // Ambil data berdasarkan groupBy
        $data = [];
        
        if ($groupBy === 'week') {
            // Group by week
            $weeklyData = $query->select(DB::raw('WEEK(tanggal_emisi) as week'), DB::raw('SUM(kadar_emisi_karbon) as total'))
                ->groupBy('week')
                ->orderBy('week')
                ->get();
            
            // Initialize data array with zeros
            $data = array_fill(0, 4, 0);
            
            // Fill in actual data
            foreach ($weeklyData as $item) {
                $weekIndex = $item->week % 4;
                $data[$weekIndex] = (float) $item->total;
            }
        } elseif ($groupBy === 'year') {
            // Group by year
            $yearlyData = $query->select(DB::raw('YEAR(tanggal_emisi) as year'), DB::raw('SUM(kadar_emisi_karbon) as total'))
                ->groupBy('year')
                ->orderBy('year')
                ->get();
            
            // Initialize data array with zeros
            $data = array_fill(0, count($labels), 0);
            
            // Fill in actual data
            foreach ($yearlyData as $item) {
                $yearIndex = array_search((string)$item->year, $labels);
                if ($yearIndex !== false) {
                    $data[$yearIndex] = (float) $item->total;
                }
            }
        } else {
            // Group by month
            $monthlyData = $query->select(DB::raw('MONTH(tanggal_emisi) as month'), DB::raw('SUM(kadar_emisi_karbon) as total'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            // Initialize data array with zeros
            $data = array_fill(0, count($labels), 0);
            
            // Fill in actual data
            foreach ($monthlyData as $item) {
                $monthIndex = Carbon::now()->month - $item->month;
                if ($monthIndex < 0) {
                    $monthIndex += 12;
                }
                if ($monthIndex < count($labels)) {
                    $data[$monthIndex] = (float) $item->total;
                }
            }
        }
        
        // Hitung perbandingan dengan periode sebelumnya
        $currentTotal = array_sum($data);
        
        // Ambil data periode sebelumnya untuk perbandingan
        $previousStartDate = clone $startDate;
        $previousEndDate = clone $startDate;
        
        switch ($period) {
            case '1M':
                $previousStartDate->subMonth();
                break;
            case '3M':
                $previousStartDate->subMonths(3);
                $previousEndDate->subMonths(3);
                break;
            case '6M':
                $previousStartDate->subMonths(6);
                $previousEndDate->subMonths(6);
                break;
            case '1Y':
                $previousStartDate->subYear();
                $previousEndDate->subYear();
                break;
            case 'ALL':
                $previousStartDate->subYears(5);
                $previousEndDate->subYears(5);
                break;
        }
        
        $previousQuery = EmisiKarbon::query();
        
        if ($role === 'staff') {
            $previousQuery->where('kode_staff', $kodeUser);
        } elseif ($role === 'admin' || $role === 'manager') {
            $previousQuery->where('kode_staff', 'like', $kodePerusahaan . '%');
            // Admin dan manager hanya melihat data yang sudah disetujui
            $previousQuery->where('status', 'approved');
        } elseif ($role === 'super_admin') {
            // Super Admin dapat melihat semua data yang sudah disetujui
            $previousQuery->where('status', 'approved');
        }
        
        $previousTotal = $previousQuery->whereBetween('tanggal_emisi', [$previousStartDate, $previousEndDate])
            ->sum('kadar_emisi_karbon');
        
        $percentChange = 0;
        if ($previousTotal > 0) {
            $percentChange = (($currentTotal - $previousTotal) / $previousTotal) * 100;
        }
        
        // Format data untuk chart
        return [
            'labels' => $labels,
            'data' => array_map(function($value) {
                return round($value, 2);
            }, $data),
            'comparison' => round($percentChange, 2),
            'period' => $period
        ];
    }
    
    /**
     * Mempersiapkan data kategori emisi untuk chart (donut chart)
     *
     * @return array
     */
    private function prepareEmisiCategoryData()
    {
        $kodePerusahaan = Auth::user()->kode_perusahaan;
        $role = Auth::user()->role;
        $kodeUser = Auth::user()->kode_user;
        
        // Filter berdasarkan role
        $query = EmisiKarbon::query();
        
        if ($role === 'staff') {
            $query->where('kode_staff', $kodeUser);
        } elseif ($role === 'admin' || $role === 'manager') {
            $query->where('kode_staff', 'like', $kodePerusahaan . '%');
            // Admin dan manager hanya melihat data yang sudah disetujui
            $query->where('status', 'approved');
        } elseif ($role === 'super_admin') {
            // Super Admin melihat semua data yang sudah disetujui dari semua perusahaan
            $query->where('status', 'approved');
        }
        
        // Ambil data 1 bulan terakhir
        $query->where('tanggal_emisi', '>=', Carbon::now()->subMonth());
        
        // Group by kategori
        $categoryData = $query->select('kategori_emisi_karbon', DB::raw('SUM(kadar_emisi_karbon) as total'))
            ->groupBy('kategori_emisi_karbon')
            ->orderBy('total', 'desc')
            ->get();
        
        $labels = [];
        $data = [];
        $colors = [
            '#28a745', // success
            '#4e73df', // primary
            '#ffc107', // warning
            '#e74a3b', // danger
            '#36b9cc', // info
        ];
        
        $totalEmisi = $categoryData->sum('total');
        
        foreach ($categoryData as $index => $category) {
            $labels[] = $category->kategori_emisi_karbon;
            $data[] = (float) $category->total;
        }
        
        // Hitung persentase untuk setiap kategori
        $percentages = [];
        foreach ($data as $value) {
            $percentages[] = $totalEmisi > 0 ? round(($value / $totalEmisi) * 100) : 0;
        }
        
        // Ensure we have at least one category
        if (empty($labels)) {
            $labels = ['Belum ada data'];
            $data = [0];
            $colors = ['#e9ecef'];
            $percentages = [0];
        }

        return [
            'labels' => $labels,
            'data' => array_map(function($value) {
                return round($value, 2);
            }, $data),
            'percentages' => $percentages,
            'colors' => array_slice($colors, 0, count($labels))
        ];
    }
    
    /**
     * Mempersiapkan data emisi berdasarkan perusahaan untuk Super Admin
     *
     * @return array
     */
    private function prepareEmisiCompanyData()
    {
        // Verifikasi user adalah super admin
        $role = Auth::user()->role;
        if ($role !== 'super_admin') {
            return [
                'labels' => ['Unauthorized'],
                'data' => [0],
                'percentages' => [0],
                'colors' => ['#e9ecef']
            ];
        }
        
        // Ambil data emisi karbon dikelompokkan berdasarkan perusahaan
        $companyData = DB::table('emisi_carbon')
            ->join('perusahaan', 'emisi_carbon.kode_perusahaan', '=', 'perusahaan.kode_perusahaan')
            ->where('emisi_carbon.status', 'approved')
            ->select('perusahaan.nama_perusahaan', DB::raw('SUM(emisi_carbon.kadar_emisi_karbon) as total'))
            ->groupBy('perusahaan.nama_perusahaan')
            ->orderBy('total', 'desc')
            ->get();
        
        $labels = [];
        $data = [];
        $percentages = [];
        $colors = [
            '#28a745', // success - hijau
            '#17a2b8', // info - biru muda
            '#007bff', // primary - biru
            '#6f42c1', // purple
            '#fd7e14', // orange
            '#e83e8c', // pink
            '#6c757d', // secondary - abu-abu
            '#20c997', // teal
            '#ffc107', // warning - kuning
            '#dc3545', // danger - merah
        ];
        
        $totalEmisi = $companyData->sum('total');
        
        foreach ($companyData as $index => $company) {
            $labels[] = $company->nama_perusahaan;
            $data[] = round($company->total, 2);
            $percentages[] = $totalEmisi > 0 ? round(($company->total / $totalEmisi) * 100) : 0;
        }
        
        // Jika tidak ada data, tampilkan placeholder
        if (empty($labels)) {
            $labels = ['Belum Ada Data'];
            $data = [0];
            $percentages = [0];
            $colors = ['#e9ecef'];
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'percentages' => $percentages,
            'colors' => array_slice($colors, 0, count($labels))
        ];
    }
    
    /**
     * Mempersiapkan statistik dashboard
     *
     * @return array
     */
    private function prepareDashboardStats()
    {
        $kodePerusahaan = Auth::user()->kode_perusahaan;
        $role = Auth::user()->role;
        $kodeUser = Auth::user()->kode_user;
        
        // Filter berdasarkan role
        $query = EmisiKarbon::query();
        
        if ($role === 'staff') {
            $query->where('kode_staff', $kodeUser);
        } elseif ($role === 'admin' || $role === 'manager') {
            $query->where('kode_staff', 'like', $kodePerusahaan . '%');
        } elseif ($role === 'super_admin') {
            // Super Admin melihat semua data dari semua perusahaan
            // Tidak perlu filter tambahan
        }
        
        // Total emisi (hanya yang approved untuk admin dan manager)
        $totalEmisiQuery = clone $query;
        if ($role === 'admin' || $role === 'manager') {
            $totalEmisiQuery->where('status', 'approved');
        }
        $totalEmisi = $totalEmisiQuery->sum('kadar_emisi_karbon');
        
        // Total input
        $totalInput = $query->count();
        
        // Pending approval (untuk admin)
        $pendingCount = 0;
        $approvedCount = 0;
        $rejectedCount = 0;
        
        if ($role === 'admin' || $role === 'manager') {
            $pendingCount = EmisiKarbon::where('kode_staff', 'like', $kodePerusahaan . '%')
                ->where('status', 'pending')
                ->count();
                
            $approvedCount = EmisiKarbon::where('kode_staff', 'like', $kodePerusahaan . '%')
                ->where('status', 'approved')
                ->count();
                
            $rejectedCount = EmisiKarbon::where('kode_staff', 'like', $kodePerusahaan . '%')
                ->where('status', 'rejected')
                ->count();
        }
        
        // Statistik tim (untuk manager)
        $teamStats = [];
        if ($role === 'manager') {
            $adminCount = User::where('role', 'admin')
                ->where('kode_perusahaan', $kodePerusahaan)
                ->count();
                
            $staffCount = User::where('role', 'staff')
                ->where('kode_perusahaan', $kodePerusahaan)
                ->count();
                
            $teamStats = [
                'admin_count' => $adminCount,
                'staff_count' => $staffCount
            ];
        }
        
        // Emisi dikompensasi (untuk manager)
        $compensatedEmission = 0;
        if ($role === 'manager') {
            // Logika untuk menghitung emisi yang sudah dikompensasi
            // Ini tergantung pada implementasi sistem kompensasi
            $compensatedEmission = DB::table('kompensasi_emisi_carbon')
                ->where('kode_perusahaan', $kodePerusahaan)
                ->sum('jumlah_kompensasi');
        }
        
        return [
            'total_emisi' => round($totalEmisi, 2),
            'total_input' => $totalInput,
            'pending_count' => $pendingCount,
            'approved_count' => $approvedCount,
            'rejected_count' => $rejectedCount,
            'team_stats' => $teamStats,
            'compensated_emission' => $compensatedEmission
        ];
    }
}
