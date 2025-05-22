<?php

namespace App\Http\Controllers;

use App\Models\EmisiKarbon;
use App\Models\User;
use App\Models\Perusahaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        
        return view('staff.dashboard', compact(
            'emisiChartData', 
            'categoryChartData', 
            'dashboardStats', 
            'latestEmissions'
        ));
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
        
        switch ($period) {
            case '1M':
                $startDate = $today->copy()->subMonth();
                $format = 'd M';
                $groupByFormat = 'Y-m-d';
                break;
            case '3M':
                $startDate = $today->copy()->subMonths(3);
                $format = 'd M';
                $groupByFormat = 'Y-m-d';
                break;
            case '6M':
                $startDate = $today->copy()->subMonths(6);
                $format = 'M Y';
                $groupByFormat = 'Y-m';
                break;
            case '1Y':
                $startDate = $today->copy()->subYear();
                $format = 'M Y';
                $groupByFormat = 'Y-m';
                break;
            case 'ALL':
                $startDate = Carbon::createFromDate(2020, 1, 1);
                $format = 'Y';
                $groupByFormat = 'Y';
                break;
            default:
                $startDate = $today->copy()->subMonth();
                $format = 'd M';
                $groupByFormat = 'Y-m-d';
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
        foreach ($emissions as $emission) {
            $emisiValues[$emission->date] = (float) $emission->total_emisi;
        }
        
        // Dapatkan nilai perbandingan dengan periode sebelumnya
        $comparison = $this->calculateComparison($period, $role);
        
        return [
            'labels' => array_values($dates),
            'data' => array_values($emisiValues),
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
            $labels[] = $emission->kategori_emisi_karbon;
            $data[] = (float) $emission->total_emisi;
            $colorSet[] = $colors[$index % count($colors)];
            
            // Hitung persentase
            $percentage = $totalEmisi > 0 ? round(($emission->total_emisi / $totalEmisi) * 100, 2) : 0;
            $percentages[] = $percentage;
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
            $labels[] = $company->nama_perusahaan;
            $data[] = (float) $company->total_emisi;
            $colorSet[] = $colors[$index % count($colors)];
            
            // Hitung persentase
            $percentage = $totalEmisi > 0 ? round(($company->total_emisi / $totalEmisi) * 100, 2) : 0;
            $percentages[] = $percentage;
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
        // Dapatkan emisi berdasarkan role
        $query = EmisiKarbon::query();
        $query->where('status', 'approved');
        
        if ($role === 'staff') {
            $query->where('kode_staff', Auth::user()->kode_user);
        } elseif ($role === 'admin' || $role === 'manager') {
            $query->where('kode_perusahaan', Auth::user()->kode_perusahaan);
        }
        
        // Total emisi
        $totalEmisi = $query->sum('kadar_emisi_karbon');
        
        // Total input data
        $totalInputQuery = EmisiKarbon::query();
        
        if ($role === 'staff') {
            $totalInputQuery->where('kode_staff', Auth::user()->kode_user);
        } elseif ($role === 'admin' || $role === 'manager') {
            $totalInputQuery->where('kode_perusahaan', Auth::user()->kode_perusahaan);
        }
        
        $totalInput = $totalInputQuery->count();
        
        // Hitung status emisi
        $statusQuery = EmisiKarbon::query();
        
        if ($role === 'staff') {
            $statusQuery->where('kode_staff', Auth::user()->kode_user);
        } elseif ($role === 'admin' || $role === 'manager') {
            $statusQuery->where('kode_perusahaan', Auth::user()->kode_perusahaan);
        }
        
        $pendingCount = (clone $statusQuery)->where('status', 'pending')->count();
        $approvedCount = (clone $statusQuery)->where('status', 'approved')->count();
        $rejectedCount = (clone $statusQuery)->where('status', 'rejected')->count();
        
        // Total user berdasarkan role
        $totalUserQuery = User::query();
        
        if ($role === 'admin' || $role === 'manager') {
            $totalUserQuery->where('kode_perusahaan', Auth::user()->kode_perusahaan);
            
            // Jika admin, hitung total staff
            if ($role === 'admin') {
                $totalUserQuery->where('role', 'staff');
            }
        }
        
        $totalUser = $role === 'staff' ? 0 : $totalUserQuery->count();
        
        return [
            'total_emisi' => $totalEmisi,
            'total_input' => $totalInput,
            'total_user' => $totalUser,
            'pending_count' => $pendingCount,
            'approved_count' => $approvedCount,
            'rejected_count' => $rejectedCount
        ];
    }
    
    private function getEmisiData(Request $request)
    {
        $period = $request->input('period', '1M');
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
        } elseif ($role === 'super-admin') {
            // Super Admin dapat melihat semua data yang sudah disetujui
            $previousQuery->where('status', 'approved');
        }
        
        $previousTotal = $previousQuery->whereBetween('tanggal_emisi', [$previousStartDate, $previousEndDate])
            ->sum('kadar_emisi_karbon');
        
        $percentChange = 0;
        if ($previousTotal > 0) {
            $percentChange = (($currentTotal - $previousTotal) / $previousTotal) * 100;
        }
        
        // Format response consistently
        $formattedData = [
            'labels' => $labels,
            'data' => array_map(function($value) {
                return round($value, 2);
            }, $data),
            'comparison' => round($percentChange, 2),
            'period' => $period
        ];

        return response()->json($formattedData);
    }
    
    /**
     * Mendapatkan data untuk chart tipe emisi (donut chart)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmisiByCategory()
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

        return response()->json([
            'labels' => $labels,
            'data' => array_map(function($value) {
                return round($value, 2);
            }, $data),
            'percentages' => $percentages,
            'colors' => array_slice($colors, 0, count($labels))
        ]);
    }
    
    /**
     * Mendapatkan statistik dashboard
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardStats()
    {
        $kodePerusahaan = Auth::user()->kode_perusahaan;
        $role = Auth::user()->role;
        $kodeUser = Auth::user()->kode_user;
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
        
        return response()->json([
            'total_emisi' => round($totalEmisi, 2),
            'total_input' => $totalInput,
            'pending_count' => $pendingCount,
            'approved_count' => $approvedCount,
            'rejected_count' => $rejectedCount,
            'team_stats' => $teamStats,
            'compensated_emission' => $compensatedEmission
        ]);
    }
    
    /**
     * Mendapatkan data emisi berdasarkan perusahaan untuk Super Admin
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmisiByCompany()
    {
        // Verifikasi user adalah super admin
        $role = Auth::user()->role;
        if ($role !== 'super_admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
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
        
        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'percentages' => $percentages,
            'colors' => array_slice($colors, 0, count($labels))
        ]);
    }
    }