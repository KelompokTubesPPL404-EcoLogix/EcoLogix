<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PerusahaanController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'super_admin') {
            return redirect()->route('login');
        }
        
        $perusahaan = Perusahaan::all();
        return view('super-admin.perusahaan.index', compact('perusahaan'));
    }

    
    public function create()
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'super_admin') {
            return redirect()->route('login');
        }
        
        $superAdmins = User::superAdmin()->get();
        return view('super-admin.perusahaan.create', compact('superAdmins'));
    }
   
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'super_admin') {
            return redirect()->route('login');
        }
        
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'no_telp_perusahaan' => 'required|string|max:15',
            'email_perusahaan' => 'required|email|unique:perusahaan,email_perusahaan',
            'password_perusahaan' => 'required|string|min:8',
            'kode_super_admin' => 'required|exists:users,kode_user',
        ]);
   
        $kodePerusahaan = 'P-' . Str::upper(Str::random(8));

        Perusahaan::create([
            'kode_perusahaan' => $kodePerusahaan,
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat_perusahaan' => $request->alamat_perusahaan,
            'no_telp_perusahaan' => $request->no_telp_perusahaan,
            'email_perusahaan' => $request->email_perusahaan,
            'password_perusahaan' => Hash::make($request->password_perusahaan),
            'kode_super_admin' => $request->kode_super_admin,
        ]);

        return redirect()->route('superadmin.perusahaan.index')
            ->with('success', 'Perusahaan berhasil ditambahkan!');
    }
   
    public function show($kode_perusahaan)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'super_admin') {
            return redirect()->route('login');
        }
        
        $perusahaan = Perusahaan::findOrFail($kode_perusahaan);
        return view('super-admin.perusahaan.show', compact('perusahaan'));
    }
 
    public function edit($kode_perusahaan)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'super_admin') {
            return redirect()->route('login');
        }
        
        $perusahaan = Perusahaan::findOrFail($kode_perusahaan);
        $superAdmins = User::where('role', 'super_admin')->get();
        return view('super-admin.perusahaan.edit', compact('perusahaan', 'superAdmins'));
    }
    public function update(Request $request, $kode_perusahaan)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'super_admin') {
            return redirect()->route('login');
        }
        
        $perusahaan = Perusahaan::findOrFail($kode_perusahaan);

        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'no_telp_perusahaan' => 'required|string|max:15',
            'email_perusahaan' => 'required|email|unique:perusahaan,email_perusahaan,' . $kode_perusahaan . ',kode_perusahaan',
            'kode_super_admin' => 'required|exists:users,kode_user',
        ]);
        $data = [
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat_perusahaan' => $request->alamat_perusahaan,
            'no_telp_perusahaan' => $request->no_telp_perusahaan,
            'email_perusahaan' => $request->email_perusahaan,
            'kode_super_admin' => $request->kode_super_admin,
        ];
   
        if ($request->filled('password_perusahaan')) {
            $request->validate([
                'password_perusahaan' => 'string|min:8',
            ]);
            $data['password_perusahaan'] = Hash::make($request->password_perusahaan);
        }

        $perusahaan->update($data);

        return redirect()->route('superadmin.perusahaan.index')
            ->with('success', 'Data perusahaan berhasil diperbarui!');
    }

    public function destroy($kode_perusahaan)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'super_admin') {
            return redirect()->route('login');
        }
        
        $perusahaan = Perusahaan::findOrFail($kode_perusahaan);
        $perusahaan->delete();

        return redirect()->route('superadmin.perusahaan.index')
            ->with('success', 'Perusahaan berhasil dihapus!');
    }
}