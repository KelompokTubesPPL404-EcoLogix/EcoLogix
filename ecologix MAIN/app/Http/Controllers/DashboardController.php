<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function staffDashboard()
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        $user = Auth::guard('staff')->user();
        if (!$user) {
            return redirect()->route('login');
        }

        return view('pages.staff.dashboard');
        
    }

    public function adminDashboard()
    {
        return view('pages.admin.dashboard');
        $totalUsers = DB::selectOne("SELECT COUNT(*) as total FROM staff")->total;
      
       
    }

    public function super_adminDashboard()
    {
        return view('pages.super_admin.dashboard');
        $totalUsers = DB::selectOne("SELECT COUNT(*) as total FROM staff")->total;
    }

    public function managerDashboard()
    {
        return view('pages.manager.dashboard');
        $totalPengguna = DB::selectOne("SELECT COUNT(*) as total FROM staff")->total;
        
    }


}