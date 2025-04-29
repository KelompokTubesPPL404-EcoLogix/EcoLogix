<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $tables = ['super_admins', 'admins', 'managers', 'staffs'];
        $user = null;
        $role = null;

        foreach ($tables as $table) {
            $result = DB::selectOne("
                SELECT * FROM {$table} 
                WHERE email = ?", 
                [$request->email]
            );

            if ($result) {
                $user = $result;
                $role = rtrim($table, 's');
                if ($role === 'staff') {
                    $role = 'staff';
                }
                break;
            }
        }

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::guard($role)->loginUsingId($user->id);
            return redirect()->intended($this->redirectTo($role));
        }

        return back()->withErrors([
            'email' => 'Email atau Password Anda Salah.',
        ]);
    }

    protected function redirectTo($guard)
    {
        switch ($guard) {
            case 'super_admin':
                return '/super_admin/dashboard';
            case 'admin':
                return '/admin/dashboard';
            case 'manager':
                return '/manager/dashboard';
            default:
                return '/dashboard';
        }
    }

    public function logout(Request $request)
    {
        $guard = Auth::getDefaultDriver();
        Auth::guard($guard)->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    protected function authenticated(Request $request, $user)
    {
        Log::info('User authenticated', [
            'user' => $user,
            'super_admin_check' => Auth::guard('super_admin')->check(),
            'admin_check' => Auth::guard('admin')->check(),
            'staff_check' => Auth::guard('staff')->check(),
            'manager_check' => Auth::guard('manager')->check()
        ]);
        
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::guard('super_admin')->check()) {
            return redirect()->route('super_admin.dashboard');
        } elseif (Auth::guard('staff')->check()) {
            return redirect()->route('staff.dashboard');
        } elseif (Auth::guard('manager')->check()) {
            return redirect()->route('manager.dashboard');
        }
    }
}