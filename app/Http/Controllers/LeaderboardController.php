<?php

namespace App\Http\Controllers;

use App\Models\EmisiKarbon;
use App\Models\KompensasiEmisiCarbon;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeaderboardController extends Controller
{
    /**
     * Menampilkan leaderboard perusahaan berdasarkan emisi karbon
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Redirect berdasarkan role user
        $role = Auth::user()->role;
        
        if ($role === 'super_admin') {
            return $this->superAdminLeaderboard($request);
        } elseif ($role === 'manager') {
            return $this->managerLeaderboard($request);
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Menampilkan leaderboard untuk super admin
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function superAdminLeaderboard(Request $request)
    {
        // Ambil data perusahaan dengan total emisi, kompensasi, dan sisa emisi
        $leaderboardData = $this->getLeaderboardData();
        
        return view('super-admin.leaderboard', compact('leaderboardData'));
    }

    /**
     * Menampilkan leaderboard untuk manager
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function managerLeaderboard(Request $request)
    {
        // Ambil data perusahaan dengan total emisi, kompensasi, dan sisa emisi
        $leaderboardData = $this->getLeaderboardData();
        
        // Dapatkan kode perusahaan manager saat ini
        $kodePerusahaan = Auth::user()->kode_perusahaan;
        
        // Cari peringkat perusahaan manager saat ini
        $perusahaanRank = 0;
        foreach ($leaderboardData as $index => $data) {
            if ($data->kode_perusahaan === $kodePerusahaan) {
                $perusahaanRank = $index + 1;
                break;
            }
        }
        
        return view('manager.leaderboard', compact('leaderboardData', 'perusahaanRank'));
    }

    /**
     * Mendapatkan data leaderboard perusahaan
     *
     * @return \Illuminate\Support\Collection
     */
    private function getLeaderboardData()
    {
        // Query untuk mendapatkan total emisi, kompensasi, dan sisa emisi per perusahaan
        $leaderboardData = DB::table('perusahaan')
            ->leftJoin('emisi_carbon', function($join) {
                $join->on('perusahaan.kode_perusahaan', '=', 'emisi_carbon.kode_perusahaan')
                     ->where('emisi_carbon.status', '=', 'approved');
            })
            ->leftJoin('kompensasi_emisi_carbon', 'emisi_carbon.kode_emisi_carbon', '=', 'kompensasi_emisi_carbon.kode_emisi_carbon')
            ->select(
                'perusahaan.kode_perusahaan',
                'perusahaan.nama_perusahaan',
                DB::raw('COALESCE(SUM(emisi_carbon.kadar_emisi_karbon), 0) as total_emisi'),
                DB::raw('COALESCE(SUM(kompensasi_emisi_carbon.jumlah_kompensasi), 0) as total_kompensasi'),
                DB::raw('(COALESCE(SUM(emisi_carbon.kadar_emisi_karbon), 0) - COALESCE(SUM(kompensasi_emisi_carbon.jumlah_kompensasi), 0)) as sisa_emisi')
            )
            ->groupBy('perusahaan.kode_perusahaan', 'perusahaan.nama_perusahaan')
            ->orderBy('sisa_emisi', 'asc') // Urutkan berdasarkan sisa emisi (terkecil ke terbesar)
            ->get();

        // Log data untuk debugging
        Log::info('Leaderboard Data', [
            'count' => $leaderboardData->count(),
            'data' => $leaderboardData->toArray()
        ]);

        return $leaderboardData;
    }
}