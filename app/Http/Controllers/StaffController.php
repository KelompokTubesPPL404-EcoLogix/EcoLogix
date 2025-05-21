<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Jika user adalah super_admin, ambil semua staff
        if ($user->isSuperAdmin()) {
            $staffs = User::where('role', 'staff')->get();
        } else {
            // Jika bukan super_admin (misal admin), ambil staff dari perusahaan yang sama
            $staffs = User::where('role', 'staff')
                         ->where('kode_perusahaan', $user->kode_perusahaan)
                         ->get();
        }
        return view('admin.staff.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $perusahaans = Perusahaan::all();
        } else {
            $perusahaans = Perusahaan::where('kode_perusahaan', $user->kode_perusahaan)->get();
        }
        return view('admin.staff.create', compact('perusahaans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'kode_perusahaan' => 'required|exists:perusahaan,kode_perusahaan',
        ]);

        User::create([
            'kode_user' => uniqid('USR-'),
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'kode_perusahaan' => $request->kode_perusahaan,
        ]);

        return redirect()->route('admin.staff.index')
                         ->with('success', 'Staff berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $staff)
    {
        // Pastikan user yang ditampilkan adalah staff
        if ($staff->role !== 'staff') {
            abort(404);
        }
        return view('admin.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $staff)
    {
        // Pastikan user yang diedit adalah staff
        if ($staff->role !== 'staff') {
            abort(404);
        }
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $perusahaans = Perusahaan::all();
        } else {
            $perusahaans = Perusahaan::where('kode_perusahaan', $user->kode_perusahaan)->get();
        }
        return view('admin.staff.edit', compact('staff', 'perusahaans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $staff)
    {
        // Pastikan user yang diupdate adalah staff
        if ($staff->role !== 'staff') {
            abort(404);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $staff->id,
            'password' => 'nullable|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'kode_perusahaan' => 'required|exists:perusahaan,kode_perusahaan',
        ]);

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'kode_perusahaan' => $request->kode_perusahaan,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')
                         ->with('success', 'Data staff berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $staff)
    {
        // Pastikan user yang dihapus adalah staff
        if ($staff->role !== 'staff') {
            abort(404);
        }
        $staff->delete();
        return redirect()->route('admin.staff.index')
                         ->with('success', 'Staff berhasil dihapus.');
    }
}