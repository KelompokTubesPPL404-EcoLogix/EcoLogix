<?php

namespace App\Http\Controllers;

use App\Models\PenyediaCarbonCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PenyediaCarbonCreditController extends Controller
{
    /**
     * Menampilkan daftar penyedia carbon credit
     */
    public function index()
    {   
        // Pastikan user adalah manager
        if (!Auth::user() || Auth::user()->role !== 'manager') {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        $penyediaList = PenyediaCarbonCredit::where('kode_perusahaan', $user->kode_perusahaan)->get();
        return view('manager.penyedia-carbon-credit.index', compact('penyediaList'));
    }

    /**
     * Menampilkan form untuk membuat penyedia carbon credit baru
     */
    public function create()
    {
        // Pastikan user adalah manager
        if (!Auth::user() || Auth::user()->role !== 'manager') {
            return redirect()->route('login');
        }
        
        return view('manager.penyedia-carbon-credit.create');
    }

    /**
     * Menyimpan penyedia carbon credit baru ke database
     */
    public function store(Request $request)
    {
        // Pastikan user adalah manager
        if (!Auth::user() || Auth::user()->role !== 'manager') {
            return redirect()->route('login');
        }
        
        $request->validate([
            'nama_penyedia' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_per_ton' => 'required|numeric|min:0',
            'mata_uang' => 'required|string|max:10',
        ]);

        // Generate kode penyedia
        $lastPenyedia = PenyediaCarbonCredit::orderBy('kode_penyedia', 'desc')->first();
        $newCode = 'PCC001';
        
        if ($lastPenyedia) {
            $lastCode = substr($lastPenyedia->kode_penyedia, 3);
            $newCode = 'PCC' . str_pad((int)$lastCode + 1, 3, '0', STR_PAD_LEFT);
        }

        $user = Auth::user();
        
        PenyediaCarbonCredit::create([
            'kode_penyedia' => $newCode,
            'kode_perusahaan' => $user->kode_perusahaan,
            'nama_penyedia' => $request->nama_penyedia,
            'deskripsi' => $request->deskripsi,
            'harga_per_ton' => $request->harga_per_ton,
            'mata_uang' => $request->mata_uang,
            'is_active' => true,
        ]);

        return redirect()->route('manager.penyedia-carbon-credit.index')
            ->with('success', 'Penyedia carbon credit berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail penyedia carbon credit
     */
    public function show($kodePenyedia)
    {
        // Pastikan user adalah manager
        if (!Auth::user() || Auth::user()->role !== 'manager') {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        $penyedia = PenyediaCarbonCredit::where('kode_penyedia', $kodePenyedia)
            ->where('kode_perusahaan', $user->kode_perusahaan)
            ->firstOrFail();
            
        return view('manager.penyedia-carbon-credit.show', compact('penyedia'));
    }

    /**
     * Menampilkan form untuk mengedit penyedia carbon credit
     */
    public function edit($kodePenyedia)
    {
        // Pastikan user adalah manager
        if (!Auth::user() || Auth::user()->role !== 'manager') {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        $penyedia = PenyediaCarbonCredit::where('kode_penyedia', $kodePenyedia)
            ->where('kode_perusahaan', $user->kode_perusahaan)
            ->firstOrFail();
            
        return view('manager.penyedia-carbon-credit.edit', compact('penyedia'));
    }

    /**
     * Mengupdate penyedia carbon credit di database
     */
    public function update(Request $request, $kodePenyedia)
    {
        // Pastikan user adalah manager
        if (!Auth::user() || Auth::user()->role !== 'manager') {
            return redirect()->route('login');
        }
        
        $request->validate([
            'nama_penyedia' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_per_ton' => 'required|numeric|min:0',
            'mata_uang' => 'required|string|max:10',
            'is_active' => 'boolean',
        ]);

        $user = Auth::user();
        $penyedia = PenyediaCarbonCredit::where('kode_penyedia', $kodePenyedia)
            ->where('kode_perusahaan', $user->kode_perusahaan)
            ->firstOrFail();
            
        $penyedia->update([
            'nama_penyedia' => $request->nama_penyedia,
            'deskripsi' => $request->deskripsi,
            'harga_per_ton' => $request->harga_per_ton,
            'mata_uang' => $request->mata_uang,
            'is_active' => (bool) $request->is_active,
        ]);

        return redirect()->route('manager.penyedia-carbon-credit.index')
            ->with('success', 'Penyedia carbon credit berhasil diperbarui!');
    }

    /**
     * Menghapus penyedia carbon credit dari database
     */
    public function destroy($kodePenyedia)
    {
        // Pastikan user adalah manager
        if (!Auth::user() || Auth::user()->role !== 'manager') {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        $penyedia = PenyediaCarbonCredit::where('kode_penyedia', $kodePenyedia)
            ->where('kode_perusahaan', $user->kode_perusahaan)
            ->firstOrFail();
        
        // Cek apakah penyedia sudah digunakan dalam pembelian
        $pembelianCount = $penyedia->pembelianCarbonCredit()->count();
        
        if ($pembelianCount > 0) {
            return redirect()->route('manager.penyedia-carbon-credit.index')
                ->with('error', 'Penyedia carbon credit tidak dapat dihapus karena sudah digunakan dalam pembelian!');
        }
        
        $penyedia->delete();
        
        return redirect()->route('manager.penyedia-carbon-credit.index')
            ->with('success', 'Penyedia carbon credit berhasil dihapus!');
    }
}