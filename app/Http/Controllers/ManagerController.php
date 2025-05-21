<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar manajer, idealnya dikelompokkan atau difilter berdasarkan perusahaan.
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'super_admin') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $perusahaanId = $request->query('perusahaan_id');
        $query = User::where('role', 'manager');

        if ($perusahaanId) {
            $query->where('kode_perusahaan', $perusahaanId);
        }
        
        $managers = $query->with('perusahaan')->latest()->paginate(10);
        $perusahaanList = Perusahaan::orderBy('nama_perusahaan')->get(); // Untuk filter

        return view('super-admin.manager.index', compact('managers', 'perusahaanList', 'perusahaanId'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk membuat manajer baru.
     */
    public function create(Request $request)
    {
        if (Auth::user()->role !== 'super_admin') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }
        
        $perusahaanList = Perusahaan::orderBy('nama_perusahaan')->get();
        $kode_perusahaan_selected = $request->kode_perusahaan; // Ambil dari query param jika ada

        return view('super-admin.manager.create', compact('perusahaanList', 'kode_perusahaan_selected'));
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan manajer baru ke database.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'super_admin') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'required|string|max:15',
            'kode_perusahaan' => 'required|exists:perusahaan,kode_perusahaan',
        ]);

        // Generate kode manager (MGRXXX)
        $lastManager = User::where('role', 'manager')
                            ->orderBy('kode_user', 'desc')
                            ->first();
        $newCodeNumber = 1;
        if ($lastManager) {
            $lastCode = $lastManager->kode_user;
            if (preg_match('/MGR(\d+)/', $lastCode, $matches)) {
                 $newCodeNumber = (int)$matches[1] + 1;
            }
        }
        $newManagerCode = 'MGR' . str_pad($newCodeNumber, 3, '0', STR_PAD_LEFT);

        User::create([
            'kode_user' => $newManagerCode,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'manager',
            'no_hp' => $request->no_hp,
            'kode_perusahaan' => $request->kode_perusahaan,
        ]);

        return redirect()->route('superadmin.manager.index')->with('success', 'Manager berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail manajer.
     */
    public function show(User $manager) // Route model binding
    {
        if (Auth::user()->role !== 'super_admin' || $manager->role !== 'manager') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin atau manajer tidak ditemukan.');
        }
        $manager->load('perusahaan'); // Eager load perusahaan
        return view('super-admin.manager.show', compact('manager'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit manajer.
     */
    public function edit(User $manager) // Route model binding
    {
        if (Auth::user()->role !== 'super_admin' || $manager->role !== 'manager') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin atau manajer tidak ditemukan.');
        }
        
        $perusahaanList = Perusahaan::orderBy('nama_perusahaan')->get();
        return view('super-admin.manager.edit', compact('manager', 'perusahaanList'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data manajer di database.
     */
    public function update(Request $request, User $manager) // Route model binding
    {
        if (Auth::user()->role !== 'super_admin' || $manager->role !== 'manager') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin atau manajer tidak ditemukan.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($manager->kode_user, 'kode_user')],
            'no_hp' => 'required|string|max:15',
            'kode_perusahaan' => 'required|exists:perusahaan,kode_perusahaan',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $dataToUpdate = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'kode_perusahaan' => $request->kode_perusahaan,
        ];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $manager->update($dataToUpdate);

        return redirect()->route('superadmin.manager.index')->with('success', 'Data manager berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus manajer dari database.
     */
    public function destroy(User $manager) // Route model binding
    {
        if (Auth::user()->role !== 'super_admin' || $manager->role !== 'manager') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin atau manajer tidak ditemukan.');
        }

        try {
            $manager->delete();
            return redirect()->route('superadmin.manager.index')->with('success', 'Manager berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle potential foreign key constraint violations or other DB errors
            return redirect()->route('superadmin.manager.index')->with('error', 'Gagal menghapus manager. Mungkin masih terkait dengan data lain.');
        }
    }
}