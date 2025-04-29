<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showStaffRegisterForm()
    {
        return view('auth.register.staff');
    }

    public function showAdminRegisterForm()
    {
        return view('auth.register.admin');
    }

    public function showManagerRegisterForm()
    {
        return view('auth.register.manager');
    }

    public function showSuperAdminRegisterForm()
    {
        return view('auth.register.super_admin');
    }

    public function registerStaff(Request $request)
    {
        $request->validate([
            'nama_staff' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:staff',
            'password' => 'required|string|min:8|confirmed',
            'no_telepon' => 'required|string|max:15'
        ]);

        try {
            $kodeUser = 'USR-' . Str::random(6);
            
            DB::insert("
                INSERT INTO staff (
                    kode_staff, nama_staff, email, password, 
                    no_telepon, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
                [
                    $kodeUser,
                    $request->nama_staff,
                    $request->email,
                    Hash::make($request->password),
                    $request->no_telepon
                ]
            );

            return redirect()->route('login')
                           ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.']);
        }
    }

    public function registerAdmin(Request $request)
    {
        $request->validate([
            'nama_admin' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admin',
            'password' => 'required|string|min:8|confirmed',
            'no_telepon' => 'required|string|max:15'
        ]);

        try {
            $kodeAdmin = 'ADM-' . Str::random(6);
            
            DB::insert("
                INSERT INTO admin (
                    kode_admin, nama_admin, email, password, 
                    no_telepon, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
                [
                    $kodeAdmin,
                    $request->nama_admin,
                    $request->email,
                    Hash::make($request->password),
                    $request->no_telepon
                ]
            );

            return redirect()->route('login')
                           ->with('success', 'Registrasi admin berhasil! Silakan login dengan akun Anda.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.']);
        }
    }

    public function registerManager(Request $request)
    {
        try {
            $request->validate([
                'nama_manager' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:manager',
                'password' => 'required|string|min:8|confirmed',
                'no_telepon' => 'required|string|max:15'
            ]);

            $kodeManager = 'MGR-' . Str::random(6);
            
            DB::insert("
                INSERT INTO manager (
                    kode_manager, nama_manager, email, password, 
                    no_telepon, kode_perusahaan, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())",
                [
                    $kodeManager,
                    $request->nama_manager,
                    $request->email,
                    Hash::make($request->password),
                    $request->no_telepon,
                    $request->kode_perusahaan
                ]
            );

            return redirect()->route('login')
                           ->with('success', 'Registrasi manager berhasil! Silakan login dengan akun Anda.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) { // Duplicate entry error
                return back()->withInput()
                            ->withErrors(['email' => 'Email sudah terdaftar. Silakan gunakan email lain.']);
            }
            return back()->withInput()
                        ->withErrors(['error' => 'Terjadi kesalahan pada database. Silakan coba lagi.']);
        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.']);
        }
    }

    public function registerSuperAdmin(Request $request)
    {
        $request->validate([
            'nama_super_admin' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:super_admin',
            'password' => 'required|string|min:8|confirmed',
            'no_telepon' => 'required|string|max:15'
        ]);

        try {
            $kodeSuperAdmin = 'SAD-' . Str::random(6);
            
            DB::insert("
                INSERT INTO super_admin (
                    kode_super_admin, nama_super_admin, email, password, 
                    no_telepon, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
                [
                    $kodeSuperAdmin,
                    $request->nama_super_admin,
                    $request->email,
                    Hash::make($request->password),
                    $request->no_telepon
                ]
            );

            return redirect()->route('login')
                           ->with('success', 'Registrasi super admin berhasil! Silakan login dengan akun Anda.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.']);
        }
    }
}