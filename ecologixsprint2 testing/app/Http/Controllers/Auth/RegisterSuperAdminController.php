<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterSuperAdminController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register.super_admin');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_super_admin' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:super_admins',
            'password' => 'required|string|min:8|confirmed',
            'no_telepon' => 'required|string|max:255',
        ]);

        $superAdmin = SuperAdmin::create([
            'kode_super_admin' => 'SA-' . Str::random(5),
            'nama_super_admin' => $request->nama_super_admin,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi Super Admin berhasil! Silakan login.');
    }
}