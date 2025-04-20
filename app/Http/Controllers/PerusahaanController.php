<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PerusahaanController extends Controller
{
    /**
     * Menampilkan daftar perusahaan
     */
    public function index()
    {
        $perusahaan = Perusahaan::all();
        return view('perusahaan.index', compact('perusahaan'));
    }

    /**
     * Menampilkan form untuk membuat perusahaan baru
     */
    public function create()
    {
        $superAdmins = SuperAdmin::all();
        return view('perusahaan.create', compact('superAdmins'));
    }

    /**
     * Menyimpan perusahaan baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'no_telp_perusahaan' => 'required|string|max:15',
            'email_perusahaan' => 'required|email|unique:perusahaan,email_perusahaan',
            'password_perusahaan' => 'required|string|min:8',
            'kode_super_admin' => 'required|exists:super_admin,kode_super_admin',
        ]);

        // Generate kode perusahaan
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

        return redirect()->route('perusahaan.index')
            ->with('success', 'Perusahaan berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail perusahaan
     */
    public function show($kode_perusahaan)
    {
        $perusahaan = Perusahaan::findOrFail($kode_perusahaan);
        return view('perusahaan.show', compact('perusahaan'));
    }

    /**
     * Menampilkan form untuk mengedit perusahaan
     */
    public function edit($kode_perusahaan)
    {
        $perusahaan = Perusahaan::findOrFail($kode_perusahaan);
        $superAdmins = SuperAdmin::all();
        return view('perusahaan.edit', compact('perusahaan', 'superAdmins'));
    }

    /**
     * Mengupdate data perusahaan di database
     */
    public function update(Request $request, $kode_perusahaan)
    {
        $perusahaan = Perusahaan::findOrFail($kode_perusahaan);

        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'no_telp_perusahaan' => 'required|string|max:15',
            'email_perusahaan' => 'required|email|unique:perusahaan,email_perusahaan,' . $kode_perusahaan . ',kode_perusahaan',
            'kode_super_admin' => 'required|exists:super_admin,kode_super_admin',
        ]);

        $data = [
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat_perusahaan' => $request->alamat_perusahaan,
            'no_telp_perusahaan' => $request->no_telp_perusahaan,
            'email_perusahaan' => $request->email_perusahaan,
            'kode_super_admin' => $request->kode_super_admin,
        ];

        // Update password jika diisi
        if ($request->filled('password_perusahaan')) {
            $request->validate([
                'password_perusahaan' => 'string|min:8',
            ]);
            $data['password_perusahaan'] = Hash::make($request->password_perusahaan);
        }

        $perusahaan->update($data);

        return redirect()->route('perusahaan.index')
            ->with('success', 'Data perusahaan berhasil diperbarui!');
    }

    /**
     * Menghapus perusahaan dari database
     */
    public function destroy($kode_perusahaan)
    {
        $perusahaan = Perusahaan::findOrFail($kode_perusahaan);
        $perusahaan->delete();

        return redirect()->route('perusahaan.index')
            ->with('success', 'Perusahaan berhasil dihapus!');
    }
}