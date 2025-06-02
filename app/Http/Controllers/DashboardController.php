<?php

namespace App\Http\Controllers;

use App\Models\EmisiKarbon;
use App\Models\User;
use App\Models\Perusahaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Redirect berdasarkan role user
        $role = Auth::user()->role;
        
        if ($role === 'super_admin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($role === 'manager') {
            return redirect()->route('manager.dashboard');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'staff') {
            return redirect()->route('staff.dashboard');
        }
        
        return abort(403, 'Unauthorized role');
    }
    
    /**
     * Dashboard admin
     *
     * @return \Illuminate\View\View
     */
    public function admin(Request $request)
    {
        $period = $request->input('period', '1M');
        $kodePerusahaan = Auth::user()->kode_perusahaan;
        
        // Dapatkan data emisi untuk chart
        $emisiChartData = $this->prepareEmisiChartData($period, 'admin');
        
        // Dapatkan data kategori emisi
        $categoryChartData = $this->prepareCategoryChartData('admin');
        
        // Dapatkan statistik dashboard
        $dashboardStats = $this->prepareDashboardStats('admin');
        
        // Dapatkan data perusahaan
        $perusahaan = Perusahaan::where('kode_perusahaan', $kodePerusahaan)->first();
        
        // Dapatkan staff terbaru
        $latestStaff = User::where('kode_perusahaan', $kodePerusahaan)
            ->where('role', 'staff')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Dapatkan data emisi terbaru
        $latestEmissions = EmisiKarbon::where('kode_perusahaan', $kodePerusahaan)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Debug: Log data yang dikirim ke view
        Log::info('Admin Dashboard Data', [
            'period' => $period,
            'emisiChartData' => $emisiChartData,
            'categoryChartData' => $categoryChartData,
            'dashboardStats' => $dashboardStats
        ]);
        
        return view('admin.dashboard', compact(
            'emisiChartData', 
            'categoryChartData', 
            'dashboardStats', 
            'perusahaan', 
            'latestStaff', 
            'latestEmissions'
        ));
    }
    
    /**
     * Dashboard manager
     *
     * @return \Illuminate\View\View
     */
    public function manager(Request $request)
    {
        $period = $request->input('period', '1M');
        $kodePerusahaan = Auth::user()->kode_perusahaan;
        
        // Dapatkan data emisi untuk chart
        $emisiChartData = $this->prepareEmisiChartData($period, 'manager');
        
        // Dapatkan data kategori emisi
        $categoryChartData = $this->prepareCategoryChartData('manager');
        
        // Dapatkan statistik dashboard
        $dashboardStats = $this->prepareDashboardStats('manager');
        
        // Dapatkan data staff untuk perusahaan
        $staffData = $this->prepareStaffData();
        
        // Dapatkan data emisi terbaru
        $latestEmissions = EmisiKarbon::where('kode_perusahaan', $kodePerusahaan)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Debug: Log data yang dikirim ke view
        Log::info('Manager Dashboard Data', [
            'period' => $period,
            'emisiChartData' => $emisiChartData,
            'categoryChartData' => $categoryChartData,
            'dashboardStats' => $dashboardStats
        ]);
        
        return view('manager.dashboard', compact(
            'emisiChartData', 
            'categoryChartData', 
            'dashboardStats', 
            'staffData',
            'latestEmissions'
        ));
    }
    
    /**
     * Dashboard staff
     *
     * @return \Illuminate\View\View
     */
    public function staff(Request $request)
    {
        $period = $request->input('period', '1M');
        $kodeUser = Auth::user()->kode_user;
        
        // Dapatkan data emisi untuk chart
        $emisiChartData = $this->prepareEmisiChartData($period, 'staff');
        
        // Dapatkan data kategori emisi
        $categoryChartData = $this->prepareCategoryChartData('staff');
        
        // Dapatkan statistik dashboard
        $dashboardStats = $this->prepareDashboardStats('staff');
        
        // Dapatkan data emisi terbaru
        $latestEmissions = EmisiKarbon::where('kode_staff', $kodeUser)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        try {


            return view('staff.dashboard', compact(
                'emisiChartData', 
                'categoryChartData', 
                'dashboardStats', 
                'latestEmissions'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading staff dashboard: ' . $e->getMessage(), ['exception' => $e]);
            // Return an empty or error view, or redirect with an error message
            return view('staff.dashboard', [
                'emisiChartData' => ['labels' => [], 'data' => [], 'comparison' => 0],
                'categoryChartData' => ['labels' => [], 'data' => [], 'colors' => [], 'percentages' => []],
                'dashboardStats' => ['total_emisi' => 0, 'total_input' => 0],
                'latestEmissions' => collect(),
                'error' => 'Failed to load dashboard data. Please try again later.'
            ]);
        }
    }
    
    /**
     * Dashboard super admin
     *
     * @return \Illuminate\View\View
     */
    public function superAdmin(Request $request)
    {
        $period = $request->input('period', '1M');
        
        // Dapatkan data emisi untuk chart
        $emisiChartData = $this->prepareEmisiChartData($period, 'super_admin');
        
        // Dapatkan data perusahaan untuk chart
        $companyChartData = $this->prepareCompanyChartData();
        
        // Dapatkan perusahaan terbaru
        $latestCompanies = Perusahaan::orderBy('created_at', 'desc')->take(5)->get();
        
        // Debug: Log data yang dikirim ke view
        Log::info('Super Admin Dashboard Data', [
            'period' => $period,
            'emisiChartData' => $emisiChartData,
            'companyChartData' => $companyChartData,
            'latestCompanies_count' => $latestCompanies->count()
        ]);
        
        return view('super-admin.dashboard', compact(
            'emisiChartData', 
            'companyChartData', 
            'latestCompanies'
        ));
    }
    
    /**
     * Prepare emisi chart data berdasarkan periode dan role
     *
     * @param string $period
     * @param string $role
     * @return array
     */
    private function prepareEmisiChartData($period, $role)
    {
        
        // Tentukan rentang waktu berdasarkan periode
        $today = Carbon::now();
        $startDate = null;
        $format = '';
        $groupByFormat = '';
        
        // Default to monthly view (regardless of period) as requested
        $format = 'M Y';
        $groupByFormat = 'Y-m';
        
        switch ($period) {
            case '1M':
                $startDate = $today->copy()->subMonths(12); // Show last 12 months for better visualization
                break;
            case '3M':
                $startDate = $today->copy()->subMonths(12); // Show last 12 months
                break;
            case '6M':
                $startDate = $today->copy()->subMonths(12); // Show last 12 months
                break;
            case '1Y':
                $startDate = $today->copy()->subMonths(12); // Full year
                break;
            case 'ALL':
                $startDate = Carbon::createFromDate(2020, 1, 1); // From 2020
                break;
            default:
                $startDate = $today->copy()->subMonths(12); // Default to showing last 12 months
        }
        
        // Dapatkan emisi berdasarkan role
        $query = EmisiKarbon::query();
        $query->where('status', 'approved');
        
        if ($role === 'staff') {
            $query->where('kode_staff', Auth::user()->kode_user);
        } elseif ($role === 'admin' || $role === 'manager') {
            $query->where('kode_perusahaan', Auth::user()->kode_perusahaan);
        }
        
        // Filter berdasarkan tanggal
        $query->whereBetween('tanggal_emisi', [$startDate->format('Y-m-d'), $today->format('Y-m-d')]);
        
        // Group by tanggal
        $emissions = $query->select(
            DB::raw("DATE_FORMAT(tanggal_emisi, '{$groupByFormat}') as date"),
            DB::raw('SUM(kadar_emisi_karbon) as total_emisi')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();
        
        // Debug: Log raw emissions data
        Log::info('Raw Emissions Data', [
            'emissions' => $emissions->toArray(),
            'query_sql' => $query->toSql(),
            'query_bindings' => $query->getBindings()
        ]);
        
        // Buat array untuk labels dan data
        $dates = [];
        $emisiValues = [];
        
        // Buat array tanggal lengkap dari start_date hingga today
        $current = $startDate->copy();
        while ($current <= $today) {
            $dateKey = $current->format($groupByFormat);
            $dates[$dateKey] = $current->format($format);
            $emisiValues[$dateKey] = 0;
            
            // Increment sesuai dengan format
            if ($groupByFormat === 'Y-m-d') {
                $current->addDay();
            } elseif ($groupByFormat === 'Y-m') {
                $current->addMonth();
            } else {
                $current->addYear();
            }
        }
        
        // Isi data dari database
        $totalEmisi = 0;
        foreach ($emissions as $emission) {
            // Periksa apakah tanggal adalah format literal atau tanggal sebenarnya
            $dateKey = $emission->date;
            $emissionValue = (float) ($emission->total_emisi ?? 0);
            $totalEmisi += $emissionValue;
            
            // Jika tanggal adalah format literal, gunakan tanggal saat ini
            if ($dateKey === 'Y-m' || $dateKey === 'Y-m-d' || $dateKey === 'Y') {
                $dateKey = $today->format($groupByFormat);
            }
            
            // Pastikan tanggal ada dalam array dates
            if (isset($dates[$dateKey])) {
                $emisiValues[$dateKey] = $emissionValue;
            } else {
                // Jika tanggal tidak ada dalam array, tambahkan ke bulan terakhir
                $lastKey = array_key_last($dates);
                if ($lastKey) {
                    $emisiValues[$lastKey] = $emissionValue;
                }
            }
        }
        
        // Pastikan semua nilai adalah numerik dan tidak null
        $cleanData = [];
        $cleanLabels = [];
        
        // Pastikan array labels dan data memiliki jumlah elemen yang sama
        foreach ($dates as $key => $label) {
            $cleanLabels[] = $label;
            $cleanData[] = is_numeric($emisiValues[$key]) ? (float) $emisiValues[$key] : 0;
        }
        
        // Debug: Log the data being returned
        Log::info('Chart Data Debug', [
            'period' => $period,
            'role' => $role,
            'labels_count' => count($cleanLabels),
            'data_count' => count($cleanData),
            'labels' => $cleanLabels,
            'data' => $cleanData,
            'emissions_from_db' => $emissions->toArray(),
            'total_emisi' => $totalEmisi
        ]);
        
        // Dapatkan nilai perbandingan dengan periode sebelumnya
        $comparison = $this->calculateComparison($period, $role);
        
        // Pastikan data tidak kosong - PERBAIKAN: Periksa total emisi, bukan array
        if ($totalEmisi <= 0) {
            // Jika tidak ada data, berikan data dummy untuk mencegah chart kosong
            return [
                'labels' => ['No Data'],
                'data' => [0],
                'comparison' => 0
            ];
        }
        
        return [
            'labels' => $cleanLabels,
            'data' => $cleanData,
            'comparison' => $comparison
        ];
    }
    
    /**
     * Hitung perbandingan emisi dengan periode sebelumnya
     *
     * @param string $period
     * @param string $role
     * @return float
     */
    private function calculateComparison($period, $role)
    {
        $today = Carbon::now();
        $endDate = $today->copy();
        $startDate = null;
        $previousStartDate = null;
        $previousEndDate = null;
        
        switch ($period) {
            case '1M':
                $startDate = $today->copy()->subMonth();
                $previousStartDate = $startDate->copy()->subMonth();
                $previousEndDate = $startDate->copy()->subDay();
                break;
            case '3M':
                $startDate = $today->copy()->subMonths(3);
                $previousStartDate = $startDate->copy()->subMonths(3);
                $previousEndDate = $startDate->copy()->subDay();
                break;
            case '6M':
                $startDate = $today->copy()->subMonths(6);
                $previousStartDate = $startDate->copy()->subMonths(6);
                $previousEndDate = $startDate->copy()->subDay();
                break;
            case '1Y':
                $startDate = $today->copy()->subYear();
                $previousStartDate = $startDate->copy()->subYear();
                $previousEndDate = $startDate->copy()->subDay();
                break;
            default:
                $startDate = $today->copy()->subMonth();
                $previousStartDate = $startDate->copy()->subMonth();
                $previousEndDate = $startDate->copy()->subDay();
        }
        
        // Dapatkan emisi untuk periode saat ini
        $query = EmisiKarbon::query();
        $query->where('status', 'approved');
        
        if ($role === 'staff') {
            $query->where('kode_staff', Auth::user()->kode_user);
        } elseif ($role === 'admin' || $role === 'manager') {
            $query->where('kode_perusahaan', Auth::user()->kode_perusahaan);
        }
        
        // Filter periode saat ini
        $currentEmission = (clone $query)
            ->whereBetween('tanggal_emisi', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->sum('kadar_emisi_karbon');
        
        // Filter periode sebelumnya
        $previousEmission = (clone $query)
            ->whereBetween('tanggal_emisi', [$previousStartDate->format('Y-m-d'), $previousEndDate->format('Y-m-d')])
            ->sum('kadar_emisi_karbon');
        
        // Hitung persentase perubahan
        if ($previousEmission > 0) {
            $comparison = (($currentEmission - $previousEmission) / $previousEmission) * 100;
            return round($comparison, 2);
        }
        
        return 0;
    }
    
    /**
     * Prepare category chart data berdasarkan role
     *
     * @param string $role
     * @return array
     */
    private function prepareCategoryChartData($role)
    {
        // Dapatkan emisi berdasarkan kategori
        $query = EmisiKarbon::query();
        $query->where('status', 'approved');
        
        if ($role === 'staff') {
            $query->where('kode_staff', Auth::user()->kode_user);
        } elseif ($role === 'admin' || $role === 'manager') {
            $query->where('kode_perusahaan', Auth::user()->kode_perusahaan);
        }
        
        $emissions = $query->select('kategori_emisi_karbon', DB::raw('SUM(kadar_emisi_karbon) as total_emisi'))
            ->groupBy('kategori_emisi_karbon')
            ->orderBy('total_emisi', 'desc')
            ->get();
        
        // Warna untuk chart
        $colors = [
            '#28a745', '#20c997', '#17a2b8', '#0dcaf0', '#0d6efd',
            '#6610f2', '#6f42c1', '#d63384', '#dc3545', '#fd7e14',
            '#ffc107', '#198754', '#0dcaf0', '#0d6efd', '#6610f2'
        ];
        
        $labels = [];
        $data = [];
        $colorSet = [];
        $percentages = [];
        
        $totalEmisi = $emissions->sum('total_emisi');
        
        foreach ($emissions as $index => $emission) {
            $labels[] = $emission->kategori_emisi_karbon ?? 'Unknown';
            $emisiValue = (float) ($emission->total_emisi ?? 0);
            $data[] = $emisiValue;
            $colorSet[] = $colors[$index % count($colors)];
            
            // Hitung persentase
            $percentage = $totalEmisi > 0 ? round(($emisiValue / $totalEmisi) * 100, 2) : 0;
            $percentages[] = $percentage;
        }
        
        // Debug: Log category chart data
        Log::info('Category Chart Data Debug', [
            'role' => $role,
            'categories_count' => count($labels),
            'labels' => $labels,
            'data' => $data,
            'total_emisi' => $totalEmisi,
            'emissions_from_db' => $emissions->toArray()
        ]);
        
        // Pastikan data tidak kosong untuk chart
        if (empty($data)) {
            return [
                'labels' => ['No Data'],
                'data' => [0],
                'colors' => ['#e9ecef'],
                'percentages' => [100]
            ];
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colorSet,
            'percentages' => $percentages
        ];
    }
    
    /**
     * Prepare company chart data untuk super admin
     *
     * @return array
     */
    private function prepareCompanyChartData()
    {
        // Dapatkan total emisi per perusahaan
        $companies = DB::table('perusahaan')
            ->leftJoin('emisi_carbon', 'perusahaan.kode_perusahaan', '=', 'emisi_carbon.kode_perusahaan')
            ->where('emisi_carbon.status', 'approved')
            ->select(
                'perusahaan.kode_perusahaan',
                'perusahaan.nama_perusahaan',
                DB::raw('SUM(emisi_carbon.kadar_emisi_karbon) as total_emisi')
            )
            ->groupBy('perusahaan.kode_perusahaan', 'perusahaan.nama_perusahaan')
            ->orderBy('total_emisi', 'desc')
            ->get();
        
        // Warna untuk chart
        $colors = [
            '#28a745', '#20c997', '#17a2b8', '#0dcaf0', '#0d6efd',
            '#6610f2', '#6f42c1', '#d63384', '#dc3545', '#fd7e14',
            '#ffc107', '#198754', '#0dcaf0', '#0d6efd', '#6610f2'
        ];
        
        $labels = [];
        $data = [];
        $colorSet = [];
        $percentages = [];
        
        $totalEmisi = $companies->sum('total_emisi');
        
        foreach ($companies as $index => $company) {
            $labels[] = $company->nama_perusahaan ?? 'Unknown Company';
            $emisiValue = (float) ($company->total_emisi ?? 0);
            $data[] = $emisiValue;
            $colorSet[] = $colors[$index % count($colors)];
            
            // Hitung persentase
            $percentage = $totalEmisi > 0 ? round(($emisiValue / $totalEmisi) * 100, 2) : 0;
            $percentages[] = $percentage;
        }
        
        // Debug: Log company chart data
        Log::info('Company Chart Data Debug', [
            'companies_count' => count($labels),
            'labels' => $labels,
            'data' => $data,
            'total_emisi' => $totalEmisi,
            'companies_from_db' => $companies->toArray()
        ]);
        
        // Pastikan data tidak kosong untuk chart
        if (empty($data)) {
            return [
                'labels' => ['No Data'],
                'data' => [0],
                'colors' => ['#e9ecef'],
                'percentages' => [100]
            ];
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colorSet,
            'percentages' => $percentages
        ];
    }
    
    /**
     * Prepare staff data untuk manager
     *
     * @return array
     */
    private function prepareStaffData()
    {
        $kodePerusahaan = Auth::user()->kode_perusahaan;
        
        // Dapatkan semua staff di perusahaan
        $staffs = User::where('kode_perusahaan', $kodePerusahaan)
            ->where('role', 'staff')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $staffData = [];
        
        foreach ($staffs as $staff) {
            // Hitung total emisi staff
            $totalEmisi = EmisiKarbon::where('kode_staff', $staff->kode_user)
                ->where('status', 'approved')
                ->sum('kadar_emisi_karbon');
                
            // Hitung total input data staff
            $totalInput = EmisiKarbon::where('kode_staff', $staff->kode_user)->count();
            
            // Hitung berdasarkan status
            $totalPending = EmisiKarbon::where('kode_staff', $staff->kode_user)
                ->where('status', 'pending')
                ->count();
                
            $totalApproved = EmisiKarbon::where('kode_staff', $staff->kode_user)
                ->where('status', 'approved')
                ->count();
                
            $totalRejected = EmisiKarbon::where('kode_staff', $staff->kode_user)
                ->where('status', 'rejected')
                ->count();
            
            $staffData[] = [
                'kode_user' => $staff->kode_user,
                'nama' => $staff->nama,
                'email' => $staff->email,
                'total_emisi' => $totalEmisi,
                'total_input' => $totalInput,
                'total_pending' => $totalPending,
                'total_approved' => $totalApproved,
                'total_rejected' => $totalRejected
            ];
        }
        
        return $staffData;
    }
    
    /**
     * Prepare dashboard statistics berdasarkan role
     *
     * @param string $role
     * @return array
     */
    private function prepareDashboardStats($role)
    {
        $query = EmisiKarbon::query();
        
        if ($role === 'staff') {
            $query->where('kode_staff', Auth::user()->kode_user);
        } elseif ($role === 'admin' || $role === 'manager') {
            $query->where('kode_perusahaan', Auth::user()->kode_perusahaan);
        }
        
        // Hitung total emisi
        $totalEmisi = (clone $query)->where('status', 'approved')->sum('kadar_emisi_karbon');
        
        // Hitung total input
        $totalInput = (clone $query)->count();
        
        // Hitung berdasarkan status
        $pendingCount = (clone $query)->where('status', 'pending')->count();
        $approvedCount = (clone $query)->where('status', 'approved')->count();
        $rejectedCount = (clone $query)->where('status', 'rejected')->count();
        
        return [
            'total_emisi' => $totalEmisi,
            'total_input' => $totalInput,
            'pending_count' => $pendingCount,
            'approved_count' => $approvedCount,
            'rejected_count' => $rejectedCount
        ];
    }
    
    /**
     * AJAX: Mendapatkan data emisi berdasarkan periode
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmisiData(Request $request)
    {
        $period = $request->input('period', '1M');
        $role = Auth::user()->role;
        
        $emisiChartData = $this->prepareEmisiChartData($period, $role);
        
        return response()->json($emisiChartData);
    }
    
    /**
     * Mendapatkan data untuk chart tipe emisi (donut chart)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmisiByCategory()
    {
        $role = Auth::user()->role;
        $categoryChartData = $this->prepareCategoryChartData($role);
        
        return response()->json($categoryChartData);
    }
    
    /**
     * Mendapatkan statistik dashboard
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardStats()
    {
        $role = Auth::user()->role;
        $dashboardStats = $this->prepareDashboardStats($role);
        
        return response()->json($dashboardStats);
    }
    
    /**
     * Mendapatkan data emisi berdasarkan perusahaan untuk Super Admin
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmisiByCompany()
    {
        $companyChartData = $this->prepareCompanyChartData();
        
        return response()->json($companyChartData);
    }
}
