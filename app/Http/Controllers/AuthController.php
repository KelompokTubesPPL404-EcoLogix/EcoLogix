<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    // Login untuk semua user
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'admin_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Cek apakah user mencoba login sebagai Super Admin
        $user = User::where('email', $request->email)->first();
        
        // Jika user ditemukan dan role-nya super_admin, cek token
        if ($user && $user->role === 'super_admin') {
            // Jika user mencoba login sebagai super admin tapi tidak memberikan token
            if (empty($request->admin_token)) {
                // Catat percobaan login yang gagal
                $this->recordFailedLoginAttempt($request->ip(), $request->email);
                
                // Periksa apakah IP ini sudah terlalu banyak percobaan gagal
                if ($this->checkTooManyFailedAttempts($request->ip())) {
                    return redirect()->back()->withErrors(['email' => 'Terlalu banyak percobaan login. Silakan coba lagi nanti.'])->withInput();
                }
                
                return redirect()->back()->withErrors(['admin_token' => 'Login sebagai Super Admin memerlukan token keamanan'])->withInput();
            }
            
            // Verifikasi token Super Admin dari lingkungan konfigurasi
            $validToken = config('auth.super_admin_token');
            if (!$validToken || $request->admin_token !== $validToken) {
                // Catat percobaan login yang gagal
                $this->recordFailedLoginAttempt($request->ip(), $request->email);
                
                // Periksa apakah IP ini sudah terlalu banyak percobaan gagal
                if ($this->checkTooManyFailedAttempts($request->ip())) {
                    return redirect()->back()->withErrors(['email' => 'Terlalu banyak percobaan login. Silakan coba lagi nanti.'])->withInput();
                }
                
                return redirect()->back()->withErrors(['admin_token' => 'Token Super Admin tidak valid'])->withInput();
            }
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Reset percobaan login setelah login berhasil
            $this->resetFailedLoginAttempts($request->ip());
            
            $user = Auth::user();
            
            // Redirect berdasarkan role
            switch ($user->role) {
                case 'super_admin':
                    return redirect()->route('superadmin.dashboard');
                case 'manager':
                    return redirect()->route('manager.dashboard');
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'staff':
                    return redirect()->route('staff.dashboard');
                default:
                    return redirect()->route('home');
            }
        }

        // Catat percobaan login yang gagal
        $this->recordFailedLoginAttempt($request->ip(), $request->email);
        
        // Periksa apakah IP ini sudah terlalu banyak percobaan gagal
        if ($this->checkTooManyFailedAttempts($request->ip())) {
            return redirect()->back()->withErrors(['email' => 'Terlalu banyak percobaan login. Silakan coba lagi nanti.'])->withInput();
        }

        return redirect()->back()->withErrors(['email' => 'Email atau password salah'])->withInput();
    }
    
    /**
     * Catat percobaan login yang gagal
     * 
     * @param string $ip
     * @param string $email
     * @return void
     */
    private function recordFailedLoginAttempt($ip, $email)
    {
        $key = "login_attempts:{$ip}";
        $attempts = Cache::get($key, []);
        $attempts[] = [
            'time' => now()->timestamp,
            'email' => $email
        ];
        
        // Simpan percobaan login dengan waktu kadaluarsa 1 jam
        Cache::put($key, $attempts, now()->addHour());
    }
    
    /**
     * Periksa apakah terlalu banyak percobaan login yang gagal dari IP tertentu
     * 
     * @param string $ip
     * @return bool
     */
    private function checkTooManyFailedAttempts($ip)
    {
        $key = "login_attempts:{$ip}";
        $attempts = Cache::get($key, []);
        
        // Filter percobaan login dalam 15 menit terakhir
        $recentAttempts = array_filter($attempts, function($attempt) {
            return $attempt['time'] > now()->subMinutes(15)->timestamp;
        });
        
        // Jika ada lebih dari 5 percobaan login yang gagal dalam 15 menit terakhir
        return count($recentAttempts) >= 5;
    }
    
    /**
     * Reset percobaan login yang gagal
     * 
     * @param string $ip
     * @return void
     */
    private function resetFailedLoginAttempts($ip)
    {
        $key = "login_attempts:{$ip}";
        Cache::forget($key);
    }

    // Register khusus untuk Super Admin
    public function registerSuperAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'no_hp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Generate kode super admin
        $lastSuperAdmin = User::where('role', 'super_admin')
                             ->orderBy('kode_user', 'desc')
                             ->first();
        $newCode = 'SA001';
        
        if ($lastSuperAdmin) {
            $lastCode = substr($lastSuperAdmin->kode_user, 2);
            $newCode = 'SA' . str_pad((int)$lastCode + 1, 3, '0', STR_PAD_LEFT);
        }

        // Buat super admin di tabel users
        $user = new User();
        $user->kode_user = $newCode;
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'super_admin';
        $user->no_hp = $request->no_hp;
        $user->save();

        Auth::login($user);
        return redirect()->route('superadmin.dashboard');
    }

    // Register perusahaan oleh Super Admin
    public function registerPerusahaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'no_telp_perusahaan' => 'required|string',
            'email_perusahaan' => 'required|string|email|max:255|unique:perusahaan,email_perusahaan',
            'password_perusahaan' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Pastikan user yang melakukan ini adalah super admin
        if (!Auth::user() || !Auth::user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Hanya Super Admin yang dapat mendaftarkan perusahaan');
        }

        // Generate kode perusahaan
        $lastPerusahaan = Perusahaan::orderBy('kode_perusahaan', 'desc')->first();
        $newCode = 'PRS001';
        
        if ($lastPerusahaan) {
            $lastCode = substr($lastPerusahaan->kode_perusahaan, 3);
            $newCode = 'PRS' . str_pad((int)$lastCode + 1, 3, '0', STR_PAD_LEFT);
        }

        // Buat perusahaan baru
        $perusahaan = new Perusahaan();
        $perusahaan->kode_perusahaan = $newCode;
        $perusahaan->nama_perusahaan = $request->nama_perusahaan;
        $perusahaan->alamat_perusahaan = $request->alamat_perusahaan;
        $perusahaan->no_telp_perusahaan = $request->no_telp_perusahaan;
        $perusahaan->email_perusahaan = $request->email_perusahaan;
        $perusahaan->password_perusahaan = Hash::make($request->password_perusahaan);
        $perusahaan->kode_super_admin = Auth::user()->kode_user;
        $perusahaan->save();

        return redirect()->route('superadmin.perusahaan.index')->with('success', 'Perusahaan berhasil ditambahkan');
    }

    // Register Manager oleh perusahaan atau Super Admin
    public function registerManager(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'no_hp' => 'required|string',
            'kode_perusahaan' => 'required|exists:perusahaan,kode_perusahaan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Hanya Super Admin yang boleh mendaftarkan Manager
        if (!Auth::user() || !Auth::user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Hanya Super Admin yang dapat mendaftarkan Manager');
        }

        // Generate kode manager
        $lastManager = User::where('role', 'manager')
                         ->orderBy('kode_user', 'desc')
                         ->first();
        $newCode = 'MGR001';
        
        if ($lastManager) {
            $lastCode = substr($lastManager->kode_user, 3);
            $newCode = 'MGR' . str_pad((int)$lastCode + 1, 3, '0', STR_PAD_LEFT);
        }

        // Buat manager baru dalam tabel users
        $user = new User();
        $user->kode_user = $newCode;
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'manager';
        $user->no_hp = $request->no_hp;
        $user->kode_perusahaan = $request->kode_perusahaan;
        $user->save();

        // Update perusahaan dengan kode manager
        $perusahaan = Perusahaan::where('kode_perusahaan', $request->kode_perusahaan)->first();
        if ($perusahaan) {
            $perusahaan->kode_manager = $newCode;
            $perusahaan->save();
        }

        return redirect()->route('superadmin.perusahaan.index')->with('success', 'Manager berhasil ditambahkan');
    }

    // Register Admin oleh Manager
    public function registerAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'no_hp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Pastikan user yang melakukan ini adalah manager
        if (!Auth::user() || !Auth::user()->isManager()) {
            return redirect()->back()->with('error', 'Hanya Manager yang dapat mendaftarkan Admin');
        }

        // Generate kode admin
        $lastAdmin = User::where('role', 'admin')
                       ->orderBy('kode_user', 'desc')
                       ->first();
        $newCode = 'ADM001';
        
        if ($lastAdmin) {
            $lastCode = substr($lastAdmin->kode_user, 3);
            $newCode = 'ADM' . str_pad((int)$lastCode + 1, 3, '0', STR_PAD_LEFT);
        }

        // Buat admin baru dalam tabel users
        $user = new User();
        $user->kode_user = $newCode;
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->no_hp = $request->no_hp;
        $user->kode_perusahaan = Auth::user()->kode_perusahaan;
        $user->save();

        return redirect()->route('manager.admin.index')->with('success', 'Admin berhasil ditambahkan');
    }

    // Register Staff oleh Admin atau Manager
    public function registerStaff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'no_hp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Pastikan user yang melakukan ini adalah admin atau manager
        if (!Auth::user() || !(Auth::user()->isAdmin() || Auth::user()->isManager())) {
            return redirect()->back()->with('error', 'Hanya Admin atau Manager yang dapat mendaftarkan Staff');
        }

        // Generate kode staff
        $lastStaff = User::where('role', 'staff')
                        ->orderBy('kode_user', 'desc')
                        ->first();
        $newCode = 'STF001';
        
        if ($lastStaff) {
            $lastCode = substr($lastStaff->kode_user, 3);
            $newCode = 'STF' . str_pad((int)$lastCode + 1, 3, '0', STR_PAD_LEFT);
        }

        // Buat staff baru dalam tabel users
        $user = new User();
        $user->kode_user = $newCode;
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'staff';
        $user->no_hp = $request->no_hp;
        $user->alamat = $request->alamat;
        $user->kode_perusahaan = Auth::user()->kode_perusahaan;
        $user->save();

        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil ditambahkan');
        } else {
            return redirect()->route('manager.staff.index')->with('success', 'Staff berhasil ditambahkan');
        }
    }

    // Logout untuk semua user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
    
    /**
     * Cek apakah email adalah milik Super Admin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSuperAdmin(Request $request)
    {
        $email = $request->input('email');
        $isSuperAdmin = false;
        
        if ($email) {
            $user = User::where('email', $email)->first();
            $isSuperAdmin = $user && $user->role === 'super_admin';
        }
        
        return response()->json(['isSuperAdmin' => $isSuperAdmin]);
    }
}