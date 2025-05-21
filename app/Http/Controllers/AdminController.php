<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar admin untuk perusahaan manajer yang sedang login.
     */
    public function index()
    {
        $manager = Auth::user();
        if (!$manager || $manager->role !== 'manager') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $admins = User::where('role', 'admin')
                        ->where('kode_perusahaan', $manager->kode_perusahaan)
                        ->with('perusahaan')
                        ->latest()
                        ->paginate(10);

        return view('manager.admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk membuat admin baru.
     */
    public function create()
    {
        $manager = Auth::user();
        if (!$manager || $manager->role !== 'manager') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }
        // Perusahaan sudah diketahui dari manager yang login
        $perusahaan = Perusahaan::where('kode_perusahaan', $manager->kode_perusahaan)->first();
        if (!$perusahaan) {
             return redirect()->route('manager.dashboard')->with('error', 'Perusahaan tidak ditemukan.');
        }
        return view('manager.admin.create', compact('perusahaan'));
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan admin baru ke database.
     */
    public function store(Request $request)
    {
        $manager = Auth::user();
        if (!$manager || $manager->role !== 'manager') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'required|string|max:15',
        ]);

        // Generate kode admin (ADMXXX)
        $lastAdmin = User::where('role', 'admin')
        ->where('kode_perusahaan', $manager->kode_perusahaan)
        ->orderBy('kode_user', 'desc')
        ->first();

$newCodeNumber = 1;
if ($lastAdmin) {
$lastCode = $lastAdmin->kode_user;

// Ekstrak nomor dari kode_user dengan lebih robust
if (preg_match('/^ADM(\d+)$/i', $lastCode, $matches)) {
$newCodeNumber = (int)$matches[1] + 1;
} else {
// Jika format tidak sesuai, cari kode terbesar secara numerik
$maxCode = User::where('role', 'admin')
            ->where('kode_perusahaan', $manager->kode_perusahaan)
            ->where('kode_user', 'regexp', '^ADM[0-9]+$')
            ->selectRaw('MAX(CAST(SUBSTRING(kode_user, 4) AS UNSIGNED)) as max_code')
            ->first();

$newCodeNumber = $maxCode->max_code ? $maxCode->max_code + 1 : 1;
}
}

// Generate kode baru dengan padding 4 digit untuk antisipasi jumlah besar
$newAdminCode = 'ADM' . str_pad($newCodeNumber, 4, '0', STR_PAD_LEFT);

// Validasi unik sebelum digunakan
while (User::where('kode_user', $newAdminCode)->exists()) {
$newCodeNumber++;
$newAdminCode = 'ADM' . str_pad($newCodeNumber, 4, '0', STR_PAD_LEFT);
}

        User::create([
            'kode_user' => $newAdminCode,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'no_hp' => $request->no_hp,
            'kode_perusahaan' => $manager->kode_perusahaan,
        ]);

        return redirect()->route('manager.admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail admin.
     */
    public function show(User $admin) // Route model binding
    {
        $manager = Auth::user();
        if (!$manager || $manager->role !== 'manager' || $admin->kode_perusahaan !== $manager->kode_perusahaan || $admin->role !== 'admin') {
            return redirect()->route('manager.admin.index')->with('error', 'Anda tidak memiliki izin atau admin tidak ditemukan.');
        }
        $admin->load('perusahaan');
        return view('manager.admin.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit admin.
     */
    public function edit(User $admin)
    {
        $manager = Auth::user();
        if (!$manager || $manager->role !== 'manager' || $admin->kode_perusahaan !== $manager->kode_perusahaan || $admin->role !== 'admin') {
            return redirect()->route('manager.admin.index')->with('error', 'Anda tidak memiliki izin atau admin tidak ditemukan.');
        }
        $perusahaan = Perusahaan::where('kode_perusahaan', $manager->kode_perusahaan)->first();
        return view('manager.admin.edit', compact('admin', 'perusahaan'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data admin di database.
     */
    public function update(Request $request, User $admin)
    {
        $manager = Auth::user();
        if (!$manager || $manager->role !== 'manager' || $admin->kode_perusahaan !== $manager->kode_perusahaan || $admin->role !== 'admin') {
            return redirect()->route('manager.admin.index')->with('error', 'Anda tidak memiliki izin atau admin tidak ditemukan.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->kode_user, 'kode_user')],
            'no_hp' => 'required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $dataToUpdate = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $admin->update($dataToUpdate);

        return redirect()->route('manager.admin.index')->with('success', 'Data admin berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus admin dari database.
     */
    public function destroy(User $admin)
    {
        $manager = Auth::user();
        if (!$manager || $manager->role !== 'manager' || $admin->kode_perusahaan !== $manager->kode_perusahaan || $admin->role !== 'admin') {
            return redirect()->route('manager.admin.index')->with('error', 'Anda tidak memiliki izin atau admin tidak ditemukan.');
        }

        try {
            // Tambahan: Cek apakah admin memiliki staff terkait sebelum menghapus jika ada relasi
            $admin->delete();
            return redirect()->route('manager.admin.index')->with('success', 'Admin berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('manager.admin.index')->with('error', 'Gagal menghapus admin. Mungkin masih terkait dengan data lain.');
        }
    }
}