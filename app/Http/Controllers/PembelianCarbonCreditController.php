<?php

namespace App\Http\Controllers;

use App\Models\PembelianCarbonCredit;
use App\Models\KompensasiEmisiCarbon;
use App\Models\PenyediaCarbonCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PembelianCarbonCreditController extends Controller
{
    /**
     * Get kompensasi and penyedia data for auto-population of fields
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getFormData(Request $request)
    {
        $kode_kompensasi = $request->input('kode_kompensasi');
        $kode_penyedia = $request->input('kode_penyedia');
        
        $data = [
            'jumlah_kompensasi' => null,
            'harga_per_ton' => null
        ];
        
        if ($kode_kompensasi) {
            $kompensasi = KompensasiEmisiCarbon::find($kode_kompensasi);
            if ($kompensasi) {
                $data['jumlah_kompensasi'] = $kompensasi->jumlah_kompensasi;
            }
        }
        
        if ($kode_penyedia) {
            $penyedia = PenyediaCarbonCredit::find($kode_penyedia);
            if ($penyedia) {
                $data['harga_per_ton'] = $penyedia->harga_per_ton;
                $data['mata_uang'] = $penyedia->mata_uang;
            }
        }
        
        return response()->json($data);
    }
    
    /**
     * Display a listing of the pembelian carbon credit.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get current user and company
        $user = Auth::user();
        
        // Fetch all pembelian carbon credit for the current company
        $pembelianList = PembelianCarbonCredit::with('penyedia')
            ->orderBy('tanggal_pembelian', 'desc')
            ->get();
            
        // Log any pembelian without a valid penyedia for debugging purposes
        foreach ($pembelianList as $pembelian) {
            if (!$pembelian->penyedia) {
                \Illuminate\Support\Facades\Log::warning("Pembelian with code {$pembelian->kode_pembelian_carbon_credit} has no valid penyedia (kode_penyedia: {$pembelian->kode_penyedia})");
            }
        }

        return view('manager.pembelian-carbon-credit.index', compact('pembelianList'));
    }

    /**
     * Show the form for creating a new pembelian carbon credit.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get current user
        $user = Auth::user();
        
        // Get kompensasi emisi list for dropdown
        $kompensasiList = KompensasiEmisiCarbon::where('kode_perusahaan', $user->kode_perusahaan)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get penyedia carbon credit list for dropdown
        $penyediaList = PenyediaCarbonCredit::where('kode_perusahaan', $user->kode_perusahaan)
            ->orderBy('nama_penyedia')
            ->get();
            
        return view('manager.pembelian-carbon-credit.create', compact('kompensasiList', 'penyediaList'));
    }

    /**
     * Store a newly created pembelian carbon credit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_kompensasi' => 'required|exists:kompensasi_emisi_carbon,kode_kompensasi',
            'kode_penyedia' => 'required|exists:penyedia_carbon_credit,kode_penyedia',
            'jumlah_kompensasi' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'tanggal_pembelian' => 'required|date',
            'bukti_pembayaran' => 'required|image|max:2048',
            'deskripsi' => 'required|string',
            // harga_per_ton is not required in validation as we'll get it from the penyedia
        ]);
        
        // Generate unique code for pembelian carbon credit
        $lastPembelian = PembelianCarbonCredit::orderBy('created_at', 'desc')->first();
        $kodeNumber = $lastPembelian ? intval(substr($lastPembelian->kode_pembelian_carbon_credit, 4)) + 1 : 1;
        $kodePembelian = 'PCC-' . str_pad($kodeNumber, 4, '0', STR_PAD_LEFT);
        
        // Get selected penyedia for harga_per_ton
        $penyedia = PenyediaCarbonCredit::where('kode_penyedia', $request->kode_penyedia)->first();
        $hargaPerTon = $penyedia ? $penyedia->harga_per_ton : 0;
        
        // Handle file upload
        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $buktiPath = $file->storeAs('bukti_pembayaran', $fileName, 'public');
        }
        
        // Create new pembelian carbon credit
        $pembelian = new PembelianCarbonCredit();
        $pembelian->kode_pembelian_carbon_credit = $kodePembelian;
        $pembelian->kode_penyedia = $request->kode_penyedia;
        $pembelian->kode_kompensasi = $request->kode_kompensasi;
        $pembelian->kode_admin = Auth::id(); // Using regular auth user ID
        $pembelian->jumlah_kompensasi = $request->jumlah_kompensasi;
        $pembelian->harga_per_ton = $hargaPerTon;
        $pembelian->total_harga = $request->total_harga;
        $pembelian->tanggal_pembelian = $request->tanggal_pembelian;
        $pembelian->bukti_pembayaran = $buktiPath;
        $pembelian->deskripsi = $request->deskripsi;
        $pembelian->save();
        
        return redirect()->route('manager.pembelian-carbon-credit.index')
            ->with('success', 'Data pembelian carbon credit berhasil ditambahkan.');
    }

    /**
     * Display the specified pembelian carbon credit.
     *
     * @param  string  $kode_pembelian_carbon_credit
     * @return \Illuminate\Http\Response
     */
    public function show($kode_pembelian_carbon_credit)
    {
        $pembelian = PembelianCarbonCredit::with(['penyedia'])
            ->findOrFail($kode_pembelian_carbon_credit);
        
        return view('manager.pembelian-carbon-credit.show', compact('pembelian'));
    }

    /**
     * Show the form for editing the specified pembelian carbon credit.
     *
     * @param  string  $kode_pembelian_carbon_credit
     * @return \Illuminate\Http\Response
     */
    public function edit($kode_pembelian_carbon_credit)
    {
        $user = Auth::user();
        
        $pembelian = PembelianCarbonCredit::findOrFail($kode_pembelian_carbon_credit);
        
        $kompensasiList = KompensasiEmisiCarbon::where('kode_perusahaan', $user->kode_perusahaan)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $penyediaList = PenyediaCarbonCredit::where('kode_perusahaan', $user->kode_perusahaan)
            ->orderBy('nama_penyedia')
            ->get();
            
        return view('manager.pembelian-carbon-credit.edit', compact('pembelian', 'kompensasiList', 'penyediaList'));
    }

    /**
     * Update the specified pembelian carbon credit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $kode_pembelian_carbon_credit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kode_pembelian_carbon_credit)
    {
        $request->validate([
            'kode_kompensasi' => 'required|exists:kompensasi_emisi_carbon,kode_kompensasi',
            'kode_penyedia' => 'required|exists:penyedia_carbon_credit,kode_penyedia',
            'jumlah_kompensasi' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'tanggal_pembelian' => 'required|date',
            'bukti_pembayaran' => 'nullable|image|max:2048',
            'deskripsi' => 'required|string',
        ]);
        
        $pembelian = PembelianCarbonCredit::findOrFail($kode_pembelian_carbon_credit);
        
        // Get selected penyedia for harga_per_ton
        $penyedia = PenyediaCarbonCredit::where('kode_penyedia', $request->kode_penyedia)->first();
        $hargaPerTon = $penyedia ? $penyedia->harga_per_ton : 0;
        
        // Handle file upload if a new file is provided
        if ($request->hasFile('bukti_pembayaran')) {
            // Delete old file if exists
            if ($pembelian->bukti_pembayaran) {
                Storage::disk('public')->delete($pembelian->bukti_pembayaran);
            }
            
            // Store new file
            $file = $request->file('bukti_pembayaran');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $buktiPath = $file->storeAs('bukti_pembayaran', $fileName, 'public');
            
            $pembelian->bukti_pembayaran = $buktiPath;
        }
        
        $pembelian->kode_kompensasi = $request->kode_kompensasi;
        $pembelian->kode_penyedia = $request->kode_penyedia;
        $pembelian->jumlah_kompensasi = $request->jumlah_kompensasi;
        $pembelian->harga_per_ton = $hargaPerTon;
        $pembelian->total_harga = $request->total_harga;
        $pembelian->tanggal_pembelian = $request->tanggal_pembelian;
        $pembelian->deskripsi = $request->deskripsi;
        $pembelian->save();
        
        return redirect()->route('manager.pembelian-carbon-credit.index')
            ->with('success', 'Data pembelian carbon credit berhasil diperbarui.');
    }

    /**
     * Remove the specified pembelian carbon credit.
     *
     * @param  string  $kode_pembelian_carbon_credit
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode_pembelian_carbon_credit)
    {
        $pembelian = PembelianCarbonCredit::findOrFail($kode_pembelian_carbon_credit);
        
        // Delete file if exists
        if ($pembelian->bukti_pembayaran) {
            Storage::disk('public')->delete($pembelian->bukti_pembayaran);
        }
        
        $pembelian->delete();
        
        return redirect()->route('manager.pembelian-carbon-credit.index')
            ->with('success', 'Data pembelian carbon credit berhasil dihapus.');
    }
}
