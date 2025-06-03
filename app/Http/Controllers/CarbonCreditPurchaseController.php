<?php

namespace App\Http\Controllers;

use App\Models\PembelianCarbonCredit;
use App\Models\KompensasiEmisiCarbon;
use App\Models\PenyediaCarbonCredit;
use App\Models\User;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CarbonCreditPurchaseController extends Controller
{
    /**
     * Display a listing of carbon credit purchases
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Get authenticated user
        $user = Auth::user();
        
        // Query purchases with relationships
        $purchases = PembelianCarbonCredit::with([
                'penyedia', 
                'kompensasiEmisiCarbon', 
                'admin', 
                'perusahaan'
            ])
            ->when($user->role != 'super-admin', function ($query) use ($user) {
                return $query->where('kode_perusahaan', $user->kode_perusahaan);
            })
            ->orderBy('tanggal_pembelian', 'desc')
            ->get();
        
        // Calculate statistics for dashboard
        $totalCarbon = $purchases->sum('jumlah_kompensasi');
        
        // Calculate total spent with currency conversion
        $totalSpent = 0;
        foreach ($purchases as $purchase) {
            // Convert all to IDR (Rupiah)
            $exchangeRate = 1; // Default for IDR
            if ($purchase->penyedia && $purchase->penyedia->mata_uang == 'USD') {
                $exchangeRate = 15500; // USD to IDR conversion rate
            } elseif ($purchase->penyedia && $purchase->penyedia->mata_uang == 'EUR') {
                $exchangeRate = 16700; // EUR to IDR conversion rate
            }
            $totalSpent += $purchase->total_harga * $exchangeRate;
        }
        
        // Fix division by zero and calculate average price in Rupiah
        $averagePrice = ($purchases->count() > 0 && $totalCarbon > 0) ? $totalSpent / $totalCarbon : 0;
        
        // Group purchases by month for chart data
        $monthlyPurchases = [];
        
        if ($purchases->count() > 0) {
            $monthlyPurchases = $purchases
                ->groupBy(function($purchase) {
                    return Carbon::parse($purchase->tanggal_pembelian)->format('M Y');
                })
                ->map(function($group) {
                    $totalValue = 0;
                    foreach ($group as $purchase) {
                        // Convert all to IDR (Rupiah)
                        $exchangeRate = 1; // Default for IDR
                        if ($purchase->penyedia && $purchase->penyedia->mata_uang == 'USD') {
                            $exchangeRate = 15500; // USD to IDR conversion rate
                        } elseif ($purchase->penyedia && $purchase->penyedia->mata_uang == 'EUR') {
                            $exchangeRate = 16700; // EUR to IDR conversion rate
                        }
                        $totalValue += $purchase->total_harga * $exchangeRate;
                    }
                    
                    return [
                        'count' => $group->count(),
                        'amount' => $group->sum('jumlah_kompensasi')/100, // Convert to tons
                        'value' => $totalValue
                    ];
                });
        }
            
        return view('admin.carbon-credit-purchase.index', compact(
            'purchases', 
            'totalCarbon', 
            'totalSpent', 
            'averagePrice',
            'monthlyPurchases'
        ));
    }

    /**
     * Show form for creating a new carbon credit purchase
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        // Get authenticated user
        $user = Auth::user();
        $kode_perusahaan = $user->kode_perusahaan;
        
        // Get compensations with "Belum Terkompensasi" status
        $compensations = KompensasiEmisiCarbon::where('kode_perusahaan', $kode_perusahaan)
            ->where('status_kompensasi', 'Belum Terkompensasi')
            ->orderBy('tanggal_kompensasi', 'desc')
            ->get();
            
        // Check if no compensations available
        $noCompensations = $compensations->isEmpty();
        
        // Get active providers that belong to user's company
        $user = Auth::user();
        $providers = PenyediaCarbonCredit::where('is_active', true)
            ->where('kode_perusahaan', $user->kode_perusahaan)
            ->orderBy('nama_penyedia', 'asc')
            ->get();
            
        // Check if no providers available
        $noProviders = $providers->isEmpty();
            
        // Get currencies for dropdown
        $currencies = ['IDR' => 'Rupiah (IDR)', 'USD' => 'US Dollar (USD)', 'EUR' => 'Euro (EUR)'];
        
        return view('admin.carbon-credit-purchase.create', compact(
            'compensations', 
            'providers',
            'currencies',
            'noCompensations',
            'noProviders'
        ));
    }
    
    /**
     * Get data for auto-population in purchase form
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
            'harga_per_ton' => null,
            'mata_uang' => null,
            'perusahaan' => null,
            'emisi_info' => null,
            'status' => 'success',
            'message' => null,
            'environmental_impact' => null
        ];
        
        try {
            if ($kode_kompensasi) {
                $kompensasi = KompensasiEmisiCarbon::with(['perusahaan', 'emisiCarbon'])
                    ->where('kode_kompensasi', $kode_kompensasi)
                    ->first();
                    
                if ($kompensasi) {
                    // Check if compensation is already processed
                    if ($kompensasi->status_kompensasi !== 'Belum Terkompensasi') {
                        $data['status'] = 'error';
                        $data['message'] = 'Kompensasi emisi ini sudah diproses sebelumnya.';
                        return response()->json($data, 422);
                    }
                    
                    // Return the actual value in kg (will be converted to tons in frontend)
                    // Nilai disimpan dalam kg di database, akan dikonversi ke ton di frontend
                    $data['jumlah_kompensasi'] = $kompensasi->jumlah_kompensasi;
                    
                    // Add company info
                    if ($kompensasi->perusahaan) {
                        $data['perusahaan'] = [
                            'nama' => $kompensasi->perusahaan->nama_perusahaan,
                            'kode' => $kompensasi->perusahaan->kode_perusahaan,
                            'alamat' => $kompensasi->perusahaan->alamat,
                            'email' => $kompensasi->perusahaan->email,
                            'telepon' => $kompensasi->perusahaan->telepon,
                            'industri' => $kompensasi->perusahaan->industri
                        ];
                    }
                    
                    // Add emission info if available
                    if ($kompensasi->emisiCarbon) {
                        $data['emisi_info'] = [
                            'kode' => $kompensasi->emisiCarbon->kode_emisi_carbon,
                            'sumber' => $kompensasi->emisiCarbon->sumber_emisi,
                            'jenis' => $kompensasi->emisiCarbon->jenis_emisi,
                            'jumlah' => $kompensasi->emisiCarbon->jumlah_emisi,
                            'tanggal' => $kompensasi->emisiCarbon->tanggal_emisi,
                            'tanggal_pencatatan' => $kompensasi->emisiCarbon->tanggal_pencatatan
                        ];
                    }
                    
                    // Calculate environmental impact (tons)
                    $jumlahKompensasiTon = $kompensasi->jumlah_kompensasi / 1000;
                    $data['environmental_impact'] = [
                        'trees_saved' => round($jumlahKompensasiTon * 50), // Approximate trees saved
                        'water_saved' => round($jumlahKompensasiTon * 100000), // Liters of water saved
                        'energy_saved' => round($jumlahKompensasiTon * 2500), // kWh of energy saved
                        'waste_reduced' => round($jumlahKompensasiTon * 300) // kg of waste reduced
                    ];
                } else {
                    $data['status'] = 'error';
                    $data['message'] = 'Data kompensasi tidak ditemukan';
                }
            }
            
            if ($kode_penyedia) {
                $user = Auth::user();
                $penyedia = PenyediaCarbonCredit::where('kode_penyedia', $kode_penyedia)
                    ->where('kode_perusahaan', $user->kode_perusahaan)
                    ->first();
                
                if ($penyedia) {
                    // Return exact value without modification
                    $data['harga_per_ton'] = $penyedia->harga_per_ton;
                    $data['mata_uang'] = $penyedia->mata_uang ?: 'IDR';
                    $data['penyedia_info'] = [
                        'nama_penyedia' => $penyedia->nama_penyedia,
                        'alamat' => $penyedia->alamat,
                        'email' => $penyedia->email,
                        'telepon' => $penyedia->telepon,
                        'website' => $penyedia->website,
                        'deskripsi' => $penyedia->deskripsi
                    ];
                } else {
                    $data['status'] = 'error';
                    $data['message'] = 'Data penyedia tidak ditemukan';
                }
            }
        } catch (\Exception $e) {
            $data['status'] = 'error';
            $data['message'] = 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage();
            
            // Log error
            Log::error("Error in getFormData", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'kode_kompensasi' => $kode_kompensasi,
                'kode_penyedia' => $kode_penyedia
            ]);
            
            return response()->json($data, 500);
        }
        
        return response()->json($data);
    }

    /**
     * Store a newly created carbon credit purchase
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate request with more specific rules
        $validated = $request->validate([
            'kode_kompensasi' => 'required|exists:kompensasi_emisi_carbon,kode_kompensasi',
            'kode_penyedia' => 'required|exists:penyedia_carbon_credit,kode_penyedia',
            'jumlah_kompensasi' => 'required|numeric|min:0.01',
            'harga_per_ton' => 'required|numeric|min:0.01',
            'total_harga' => 'required|numeric|min:0.01',
            'tanggal_pembelian' => 'required|date|before_or_equal:today',
            'bukti_pembayaran' => 'required|image|max:2048|mimes:jpeg,png,jpg',
            'deskripsi' => 'required|string|max:1000',
        ], [
            'kode_kompensasi.required' => 'Kompensasi emisi harus dipilih',
            'kode_penyedia.required' => 'Penyedia carbon credit harus dipilih',
            'jumlah_kompensasi.min' => 'Jumlah kompensasi minimal 0.01 ton',
            'harga_per_ton.min' => 'Harga per ton minimal 0.01',
            'tanggal_pembelian.before_or_equal' => 'Tanggal pembelian tidak boleh melebihi hari ini',
            'bukti_pembayaran.mimes' => 'Bukti pembayaran harus berupa file gambar (jpeg, png, jpg)',
        ]);
        
        // Start database transaction
        DB::beginTransaction();
        
        try {
            // Get authenticated user
            $user = Auth::user();
            
            // Get compensation data with eager loading
            $kompensasi = KompensasiEmisiCarbon::with(['perusahaan', 'emisiCarbon'])
                ->findOrFail($request->kode_kompensasi);
            
            // Verify compensation is not already processed
            if ($kompensasi->status_kompensasi !== 'Belum Terkompensasi') {
                return back()->with('error', 'Kompensasi emisi ini sudah diproses sebelumnya.');
            }
            
            // Verify user has permission for this company
            if ($user->role !== 'super-admin' && $user->kode_perusahaan !== $kompensasi->kode_perusahaan) {
                return back()->with('error', 'Anda tidak memiliki akses untuk memproses kompensasi emisi ini.');
            }
            
            // Get provider data and ensure it belongs to the user's company
            $penyedia = PenyediaCarbonCredit::where('kode_penyedia', $request->kode_penyedia)
                ->where('kode_perusahaan', $user->kode_perusahaan)
                ->firstOrFail();
            
            // Verify total price calculation is correct
            $expectedTotal = $request->jumlah_kompensasi * $request->harga_per_ton;
            if (abs($expectedTotal - $request->total_harga) > 0.01) { // Allow small floating point differences
                return back()
                    ->withInput()
                    ->with('error', 'Perhitungan total harga tidak sesuai. Harap periksa kembali.');
            }
            
            // Generate unique code for purchase with better uniqueness
            $prefix = 'PCC-' . date('ym') . '-';
            $lastPurchase = PembelianCarbonCredit::where('kode_pembelian_carbon_credit', 'like', $prefix . '%')
                ->orderBy('created_at', 'desc')
                ->first();
                
            if ($lastPurchase) {
                $lastNumber = intval(substr($lastPurchase->kode_pembelian_carbon_credit, strlen($prefix)));
                $kodeNumber = $lastNumber + 1;
            } else {
                $kodeNumber = 1;
            }
            
            $kodePembelian = $prefix . str_pad($kodeNumber, 4, '0', STR_PAD_LEFT);
            
            // Handle file upload with better naming
            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $extension = $file->getClientOriginalExtension();
                $fileName = $kodePembelian . '_' . time() . '.' . $extension;
                $buktiPath = $file->storeAs('bukti_pembayaran', $fileName, 'public');
            }
            
            // Convert jumlah_kompensasi from tons to kg (multiply by 1000)
            $jumlahKompensasiKg = $request->jumlah_kompensasi * 1000;
            
            // Create new purchase
            $purchase = new PembelianCarbonCredit();
            $purchase->kode_pembelian_carbon_credit = $kodePembelian;
            $purchase->kode_penyedia = $request->kode_penyedia;
            $purchase->kode_kompensasi = $request->kode_kompensasi;
            $purchase->kode_admin = $user->kode_user;
            $purchase->kode_perusahaan = $kompensasi->kode_perusahaan;
            $purchase->jumlah_kompensasi = $jumlahKompensasiKg; // Store in kg
            $purchase->harga_per_ton = $request->harga_per_ton;
            $purchase->total_harga = $request->total_harga;
            $purchase->tanggal_pembelian = $request->tanggal_pembelian;
            $purchase->bukti_pembayaran = $buktiPath;
            $purchase->deskripsi = $request->deskripsi;
            $purchase->save();
            
            // Update compensation status
            $kompensasi->status_kompensasi = 'Terkompensasi';
            $kompensasi->save();
            
            // Create notification for managers
            try {
                // Create notification for company managers
                if (class_exists('\App\Models\Notifikasi')) {
                    $notifikasi = new \App\Models\Notifikasi();
                    $notifikasi->judul = 'Pembelian Carbon Credit Baru';
                    $notifikasi->isi = 'Pembelian carbon credit dengan kode ' . $kodePembelian . ' telah berhasil diproses.';
                    $notifikasi->tipe = 'carbon-credit';
                    $notifikasi->dibaca = false;
                    $notifikasi->kode_perusahaan = $kompensasi->kode_perusahaan;
                    $notifikasi->save();
                }
            } catch (\Exception $notifError) {
                // Just log notification error, don't rollback the transaction
                Log::warning("Failed to create notification for carbon credit purchase", [
                    'error' => $notifError->getMessage(),
                    'purchase_id' => $kodePembelian
                ]);
            }
            
            // Commit transaction
            DB::commit();
            
            // Log successful purchase with more details
            Log::info("Carbon Credit Purchase successful", [
                'purchase_id' => $purchase->kode_pembelian_carbon_credit,
                'company_id' => $purchase->kode_perusahaan,
                'company_name' => $kompensasi->perusahaan ? $kompensasi->perusahaan->nama_perusahaan : 'Unknown',
                'provider' => $penyedia->nama_penyedia,
                'amount_kg' => $purchase->jumlah_kompensasi,
                'amount_ton' => $request->jumlah_kompensasi,
                'price_per_ton' => $purchase->harga_per_ton,
                'total_price' => $purchase->total_harga,
                'admin' => $user->nama
            ]);
            
            return redirect()->route('admin.carbon-credit-purchase.index')
                ->with('success', 'Pembelian carbon credit berhasil dicatat dan diverifikasi.');
                
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            // Log error with more context
            Log::error("Carbon Credit Purchase failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['bukti_pembayaran']),
                'user_id' => Auth::id(),
                'user_role' => Auth::user() ? Auth::user()->role : 'unknown'
            ]);
            
            // Provide more specific error message if possible
            $errorMessage = 'Terjadi kesalahan saat memproses pembelian. ';
            
            if ($e instanceof \Illuminate\Database\QueryException) {
                // Ambil pesan error SQL yang sebenarnya
                $sqlErrorMessage = $e->getMessage();

                if (str_contains($sqlErrorMessage, 'Duplicate entry')) {
                    $errorMessage .= 'Terdapat duplikasi data. Pastikan nomor referensi atau kode pembelian belum pernah digunakan.';
                } elseif (str_contains($sqlErrorMessage, 'Foreign key constraint fails')) {
                    $errorMessage .= 'Terdapat masalah dengan data terkait (misal: Kompensasi Emisi atau Penyedia tidak valid). Silakan periksa kembali pilihan Anda.';
                } elseif (str_contains($sqlErrorMessage, 'Data too long for column')) {
                    $errorMessage .= 'Ada data yang terlalu panjang untuk disimpan. Harap periksa panjang input Anda.';
                } elseif (str_contains($sqlErrorMessage, 'Out of range value')) {
                    $errorMessage .= 'Nilai numerik melebihi batas yang diizinkan untuk kolom database. Harap periksa jumlah kompensasi atau harga.';
                } else {
                    $errorMessage .= 'Terjadi kesalahan pada database. Detail: ' . $sqlErrorMessage;
                }
            } elseif ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $errorMessage .= 'Data yang diperlukan tidak ditemukan.';
            } else {
                $errorMessage .= 'Silakan coba lagi atau hubungi administrator.';
            }
            
            return back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Display details of a specific carbon credit purchase
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $purchase = PembelianCarbonCredit::with([
            'penyedia', 
            'kompensasiEmisiCarbon', 
            'admin', 
            'perusahaan'
        ])->findOrFail($id);
        
        // Calculate environmental impact estimates
        $jumlahKompensasiTon = $purchase->jumlah_kompensasi / 100; // Convert to tons (based on view display)
        $impact = [
            'trees_equivalent' => round($jumlahKompensasiTon * 45), // ~45 trees per ton CO2
            'energy_savings' => round($jumlahKompensasiTon * 2400), // ~2400 kWh per ton CO2
            'emission_reduction' => $jumlahKompensasiTon // tons
        ];
        
        return view('admin.carbon-credit-purchase.show', compact('purchase', 'impact'));
    }

    /**
     * Show form for editing a carbon credit purchase
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        // Get authenticated user
        $user = Auth::user();
        
        // Get purchase with relationships
        $purchase = PembelianCarbonCredit::with([
            'penyedia', 
            'kompensasiEmisiCarbon', 
            'admin', 
            'perusahaan'
        ])->findOrFail($id);
        
        // Get company ID
        $kode_perusahaan = $purchase->kode_perusahaan;
        
        // Get compensations
        $compensations = KompensasiEmisiCarbon::where('kode_perusahaan', $kode_perusahaan)
            ->where(function($query) use ($purchase) {
                $query->where('status_kompensasi', 'Belum Terkompensasi')
                      ->orWhere('kode_kompensasi', $purchase->kode_kompensasi);
            })
            ->orderBy('tanggal_kompensasi', 'desc')
            ->get();
        
        // Get providers that belong to user's company
        $user = Auth::user();
        $providers = PenyediaCarbonCredit::where(function($query) use ($purchase, $user) {
                $query->where('is_active', true)
                      ->where('kode_perusahaan', $user->kode_perusahaan);
            })
            ->orWhere('kode_penyedia', $purchase->kode_penyedia)
            ->orderBy('nama_penyedia', 'asc')
            ->get();
            
        // Get currencies for dropdown
        $currencies = ['IDR' => 'Rupiah (IDR)', 'USD' => 'US Dollar (USD)', 'EUR' => 'Euro (EUR)'];
        
        // Get payment methods
        $paymentMethods = [
            'Bank Transfer' => 'Bank Transfer',
            'Credit Card' => 'Credit Card',
            'E-wallet' => 'E-wallet',
            'Other' => 'Other'
        ];
        
        return view('admin.carbon-credit-purchase.edit', compact(
            'purchase', 
            'compensations', 
            'providers',
            'currencies',
            'paymentMethods'
        ));
    }

    /**
     * Update the specified carbon credit purchase
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Find purchase first to check for unique reference number
        $purchase = PembelianCarbonCredit::findOrFail($id);
        
        // Validate request with more specific rules
        $validated = $request->validate([
            'kode_kompensasi' => 'required|exists:kompensasi_emisi_carbon,kode_kompensasi',
            'kode_penyedia' => 'required|exists:penyedia_carbon_credit,kode_penyedia',
            'jumlah_kompensasi' => 'required|numeric|min:0.01',
            'harga_per_ton' => 'required|numeric|min:0.01',
            'total_harga' => 'required|numeric|min:0.01',
            'tanggal_pembelian' => 'required|date|before_or_equal:today',
            'bukti_pembayaran' => 'nullable|image|max:2048|mimes:jpeg,png,jpg',
            'deskripsi' => 'required|string|max:1000',
        ], [
            'kode_kompensasi.required' => 'Kompensasi emisi harus dipilih',
            'kode_penyedia.required' => 'Penyedia carbon credit harus dipilih',
            'jumlah_kompensasi.min' => 'Jumlah kompensasi minimal 0.01 ton',
            'harga_per_ton.min' => 'Harga per ton minimal 0.01',
            'tanggal_pembelian.before_or_equal' => 'Tanggal pembelian tidak boleh melebihi hari ini',
            'bukti_pembayaran.mimes' => 'Bukti pembayaran harus berupa file gambar (jpeg, png, jpg)',
        ]);
        
        // Start database transaction
        DB::beginTransaction();
        
        try {
            // Get authenticated user
            $user = Auth::user();
            
            // Verify user has permission for this company
            if ($user->role !== 'super-admin' && $user->kode_perusahaan !== $purchase->kode_perusahaan) {
                return back()->with('error', 'Anda tidak memiliki akses untuk memperbarui pembelian ini.');
            }
            
            // Get old compensation data with eager loading
            $oldKompensasi = KompensasiEmisiCarbon::with(['perusahaan', 'emisiCarbon'])
                ->findOrFail($purchase->kode_kompensasi);
            
            // Get new compensation data if changed
            $newKompensasi = null;
            if ($purchase->kode_kompensasi != $request->kode_kompensasi) {
                $newKompensasi = KompensasiEmisiCarbon::with(['perusahaan', 'emisiCarbon'])
                    ->findOrFail($request->kode_kompensasi);
                
                // Verify new compensation is not already processed
                if ($newKompensasi->status_kompensasi !== 'Belum Terkompensasi') {
                    return back()->with('error', 'Kompensasi emisi baru sudah diproses sebelumnya.');
                }
                
                // Verify new compensation belongs to the same company
                if ($newKompensasi->kode_perusahaan !== $oldKompensasi->kode_perusahaan) {
                    return back()->with('error', 'Kompensasi emisi harus berasal dari perusahaan yang sama.');
                }
            }
            
            // Verify total price calculation is correct
            $expectedTotal = $request->jumlah_kompensasi * $request->harga_per_ton;
            if (abs($expectedTotal - $request->total_harga) > 0.01) { // Allow small floating point differences
                return back()
                    ->withInput()
                    ->with('error', 'Perhitungan total harga tidak sesuai. Harap periksa kembali.');
            }
            
            // Handle file upload if new file is provided
            if ($request->hasFile('bukti_pembayaran')) {
                // Delete old file if exists
                if ($purchase->bukti_pembayaran && Storage::disk('public')->exists($purchase->bukti_pembayaran)) {
                    Storage::disk('public')->delete($purchase->bukti_pembayaran);
                }
                
                // Store new file with better naming
                $file = $request->file('bukti_pembayaran');
                $extension = $file->getClientOriginalExtension();
                $fileName = $purchase->kode_pembelian_carbon_credit . '_' . time() . '.' . $extension;
                $buktiPath = $file->storeAs('bukti_pembayaran', $fileName, 'public');
                $purchase->bukti_pembayaran = $buktiPath;
            }
            
            // Convert jumlah_kompensasi from tons to kg (multiply by 1000)
            $jumlahKompensasiKg = $request->jumlah_kompensasi * 1000;
            
            // Update purchase details
            $purchase->kode_penyedia = $request->kode_penyedia;
            $purchase->jumlah_kompensasi = $jumlahKompensasiKg; // Store in kg
            $purchase->harga_per_ton = $request->harga_per_ton;
            $purchase->total_harga = $request->total_harga;
            $purchase->tanggal_pembelian = $request->tanggal_pembelian;
            $purchase->deskripsi = $request->deskripsi;
            $purchase->updated_at = now(); // Explicitly update the timestamp
            
            // Update compensation if changed
            if ($newKompensasi) {
                // Update old compensation status back to not compensated
                $oldKompensasi->status_kompensasi = 'Belum Terkompensasi';
                $oldKompensasi->save();
                
                // Update new compensation status to compensated
                $newKompensasi->status_kompensasi = 'Terkompensasi';
                $newKompensasi->save();
                
                // Update purchase with new compensation
                $purchase->kode_kompensasi = $request->kode_kompensasi;
                $purchase->kode_perusahaan = $newKompensasi->kode_perusahaan;
                
                // Create notification for change in compensation
                try {
                    if (class_exists('\App\Models\Notifikasi')) {
                        $notifikasi = new \App\Models\Notifikasi();
                        $notifikasi->judul = 'Perubahan Kompensasi Pembelian Carbon Credit';
                        $notifikasi->isi = 'Pembelian carbon credit dengan kode ' . $purchase->kode_pembelian_carbon_credit . 
                                          ' telah diperbarui dengan kompensasi baru.';
                        $notifikasi->tipe = 'carbon-credit-update';
                        $notifikasi->dibaca = false;
                        $notifikasi->kode_perusahaan = $purchase->kode_perusahaan;
                        $notifikasi->save();
                    }
                } catch (\Exception $notifError) {
                    // Just log notification error, don't rollback the transaction
                    Log::warning("Failed to create notification for carbon credit purchase update", [
                        'error' => $notifError->getMessage(),
                        'purchase_id' => $purchase->kode_pembelian_carbon_credit
                    ]);
                }
            }
            
            $purchase->save();
            
            // Commit transaction
            DB::commit();
            
            // Log successful update with more details
            Log::info("Carbon Credit Purchase updated", [
                'purchase_id' => $purchase->kode_pembelian_carbon_credit,
                'company_id' => $purchase->kode_perusahaan,
                'company_name' => $oldKompensasi->perusahaan ? $oldKompensasi->perusahaan->nama_perusahaan : 'Unknown',
                'amount_kg' => $purchase->jumlah_kompensasi,
                'amount_ton' => $request->jumlah_kompensasi,
                'price_per_ton' => $purchase->harga_per_ton,
                'total_price' => $purchase->total_harga,
                'admin' => $user->nama,
                'compensation_changed' => $newKompensasi ? true : false
            ]);
            
            return redirect()->route('admin.carbon-credit-purchase.index')
                ->with('success', 'Pembelian carbon credit berhasil diperbarui.');
                
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            // Log error with more context
            Log::error("Carbon Credit Purchase update failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'purchase_id' => $id,
                'request_data' => $request->except(['bukti_pembayaran']),
                'user_id' => Auth::id(),
                'user_role' => Auth::user() ? Auth::user()->role : 'unknown'
            ]);
            
            // Provide more specific error message if possible
            $errorMessage = 'Terjadi kesalahan saat memperbarui pembelian. ';
            
            if ($e instanceof \Illuminate\Database\QueryException) {
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    $errorMessage .= 'Terdapat duplikasi data. Pastikan nomor referensi belum pernah digunakan.';
                } else {
                    $errorMessage .= 'Terjadi kesalahan pada database.';
                }
            } elseif ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $errorMessage .= 'Data yang diperlukan tidak ditemukan.';
            } else {
                $errorMessage .= 'Silakan coba lagi atau hubungi administrator.';
            }
            
            return back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Remove the specified carbon credit purchase
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Start database transaction
        DB::beginTransaction();
        
        try {
            // Find purchase with related data
            $purchase = PembelianCarbonCredit::with(['penyedia', 'kompensasiEmisiCarbon', 'perusahaan'])
                ->findOrFail($id);
            
            // Get authenticated user
            $user = Auth::user();
            
            // Verify user has permission for this company
            if ($user->role !== 'super-admin' && $user->kode_perusahaan !== $purchase->kode_perusahaan) {
                return back()->with('error', 'Anda tidak memiliki akses untuk menghapus pembelian ini.');
            }
            
            // Store data for logging before deletion
            $purchaseData = [
                'kode_pembelian' => $purchase->kode_pembelian_carbon_credit,
                'kode_kompensasi' => $purchase->kode_kompensasi,
                'kode_perusahaan' => $purchase->kode_perusahaan,
                'nama_perusahaan' => $purchase->perusahaan ? $purchase->perusahaan->nama_perusahaan : 'Unknown',
                'jumlah_kompensasi' => $purchase->jumlah_kompensasi,
                'total_harga' => $purchase->total_harga,
                'tanggal_pembelian' => $purchase->tanggal_pembelian,
                'penyedia' => $purchase->penyedia ? $purchase->penyedia->nama_penyedia : 'Unknown'
            ];
            
            // Get compensation data
            $kompensasi = KompensasiEmisiCarbon::findOrFail($purchase->kode_kompensasi);
            
            // Delete payment proof file if exists
            if ($purchase->bukti_pembayaran && Storage::disk('public')->exists($purchase->bukti_pembayaran)) {
                Storage::disk('public')->delete($purchase->bukti_pembayaran);
            }
            
            // Update compensation status back to not compensated
            $kompensasi->status_kompensasi = 'Belum Terkompensasi';
            $kompensasi->save();
            
            // Create notification for deletion
            try {
                if (class_exists('\App\Models\Notifikasi')) {
                    $notifikasi = new \App\Models\Notifikasi();
                    $notifikasi->judul = 'Pembelian Carbon Credit Dihapus';
                    $notifikasi->isi = 'Pembelian carbon credit dengan kode ' . $purchase->kode_pembelian_carbon_credit . 
                                      ' telah dihapus oleh ' . $user->nama . '.';
                    $notifikasi->tipe = 'carbon-credit-delete';
                    $notifikasi->dibaca = false;
                    $notifikasi->kode_perusahaan = $purchase->kode_perusahaan;
                    $notifikasi->save();
                }
            } catch (\Exception $notifError) {
                // Just log notification error, don't rollback the transaction
                Log::warning("Failed to create notification for carbon credit purchase deletion", [
                    'error' => $notifError->getMessage(),
                    'purchase_id' => $purchase->kode_pembelian_carbon_credit
                ]);
            }
            
            // Delete purchase
            $purchase->delete();
            
            // Commit transaction
            DB::commit();
            
            // Log successful deletion with detailed information
            Log::info("Carbon Credit Purchase deleted successfully", [
                'purchase_data' => $purchaseData,
                'deleted_by' => [
                    'user_id' => $user->kode_user,
                    'user_name' => $user->nama,
                    'user_role' => $user->role
                ],
                'deletion_time' => now()->toDateTimeString()
            ]);
            
            return redirect()->route('admin.carbon-credit-purchase.index')
                ->with('success', 'Pembelian carbon credit berhasil dihapus.');
                
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            // Log error with more context
            Log::error("Carbon Credit Purchase deletion failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'purchase_id' => $id,
                'user_id' => Auth::id(),
                'user_role' => Auth::user() ? Auth::user()->role : 'unknown'
            ]);
            
            // Provide more specific error message if possible
            $errorMessage = 'Terjadi kesalahan saat menghapus pembelian. ';
            
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $errorMessage .= 'Data pembelian tidak ditemukan.';
            } elseif ($e instanceof \Illuminate\Database\QueryException) {
                $errorMessage .= 'Terjadi kesalahan pada database.';
            } else {
                $errorMessage .= 'Silakan coba lagi atau hubungi administrator.';
            }
            
            return back()->with('error', $errorMessage);
        }
    }
    
    /**
     * Generate a report of carbon credit purchases
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function report(Request $request)
    {
        // Get authenticated user
        $user = Auth::user();
        
        // Get filter parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $kode_perusahaan = $request->input('kode_perusahaan');
        
        // Query purchases with relationships
        $query = PembelianCarbonCredit::with([
            'penyedia', 
            'kompensasiEmisiCarbon', 
            'admin', 
            'perusahaan'
        ]);
        
        // Apply date filters if provided
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_pembelian', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('tanggal_pembelian', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('tanggal_pembelian', '<=', $endDate);
        }
        
        // Filter by company if provided or if user is not super-admin
        if ($kode_perusahaan) {
            $query->where('kode_perusahaan', $kode_perusahaan);
        } elseif ($user->role != 'super-admin') {
            $query->where('kode_perusahaan', $user->kode_perusahaan);
        }
        
        // Get purchases ordered by date
        $purchases = $query->orderBy('tanggal_pembelian', 'desc')->get();
        
        // Calculate summary statistics
        $totalCarbon = $purchases->sum('jumlah_kompensasi');
        $totalSpent = $purchases->sum('total_harga');
        
        $summary = [
            'total_purchases' => $purchases->count(),
            'total_carbon' => $totalCarbon,
            'total_spent' => $totalSpent,
            'average_price' => ($purchases->count() > 0 && $totalCarbon > 0) 
                ? $totalSpent / $totalCarbon 
                : 0
        ];
        
        // Get all companies for filter dropdown
        $companies = Perusahaan::orderBy('nama_perusahaan')->get();
        
        return view('admin.carbon-credit-purchase.report', compact(
            'purchases', 
            'summary',
            'companies',
            'startDate',
            'endDate',
            'kode_perusahaan'
        ));
    }
    
    /**
     * Display dashboard for carbon credit purchases
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        try {
            // Get authenticated user
            $user = Auth::user();
            
            // Filter by company if not super-admin
            $companyFilter = $user->role !== 'super-admin' ? $user->kode_perusahaan : null;
            
            // Get recent purchases with eager loading
            $recentPurchases = PembelianCarbonCredit::with(['penyedia', 'kompensasiEmisiCarbon.emisiCarbon', 'perusahaan', 'admin'])
                ->when($companyFilter, function($query) use ($companyFilter) {
                    return $query->where('kode_perusahaan', $companyFilter);
                })
                ->orderBy('tanggal_pembelian', 'desc')
                ->take(5)
                ->get();
            
            // Get pending compensations
            $pendingCompensations = KompensasiEmisiCarbon::with(['perusahaan', 'emisiCarbon', 'manager'])
                ->where('status_kompensasi', 'Belum Terkompensasi')
                ->when($companyFilter, function($query) use ($companyFilter) {
                    return $query->where('kode_perusahaan', $companyFilter);
                })
                ->orderBy('tanggal_kompensasi', 'desc')
                ->take(5)
                ->get();
            
            // Get monthly purchase data for chart (last 12 months)
            $startDate = now()->subMonths(11)->startOfMonth();
            $monthlyPurchases = PembelianCarbonCredit::selectRaw('MONTH(tanggal_pembelian) as month, YEAR(tanggal_pembelian) as year, SUM(jumlah_kompensasi) as total_carbon, SUM(total_harga) as total_spent, COUNT(*) as transaction_count')
                ->when($companyFilter, function($query) use ($companyFilter) {
                    return $query->where('kode_perusahaan', $companyFilter);
                })
                ->where('tanggal_pembelian', '>=', $startDate)
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
            
            // Fill in missing months with zero values
            $formattedMonthlyData = [];
            $currentDate = $startDate->copy();
            $endDate = now()->endOfMonth();
            
            while ($currentDate->lte($endDate)) {
                $year = $currentDate->year;
                $month = $currentDate->month;
                
                $monthData = $monthlyPurchases->first(function($item) use ($year, $month) {
                    return $item->year == $year && $item->month == $month;
                });
                
                $formattedMonthlyData[] = [
                    'month' => $currentDate->format('M Y'),
                    'month_name' => $currentDate->format('F'),
                    'year' => $year,
                    'total_carbon' => $monthData ? $monthData->total_carbon / 1000 : 0, // Convert to tons
                    'total_spent' => $monthData ? $monthData->total_spent : 0,
                    'transaction_count' => $monthData ? $monthData->transaction_count : 0
                ];
                
                $currentDate->addMonth();
            }
            
            // Calculate total carbon offset
            $totalCarbon = PembelianCarbonCredit::when($companyFilter, function($query) use ($companyFilter) {
                    return $query->where('kode_perusahaan', $companyFilter);
                })
                ->sum('jumlah_kompensasi') / 1000; // Convert to tons
            
            // Calculate total spent
            $totalSpent = PembelianCarbonCredit::when($companyFilter, function($query) use ($companyFilter) {
                    return $query->where('kode_perusahaan', $companyFilter);
                })
                ->sum('total_harga');
            
            // Calculate average price per ton
            $avgPricePerTon = $totalCarbon > 0 ? $totalSpent / $totalCarbon : 0;
            
            // Get transaction count
            $transactionCount = PembelianCarbonCredit::when($companyFilter, function($query) use ($companyFilter) {
                    return $query->where('kode_perusahaan', $companyFilter);
                })
                ->count();
            
            // Get top providers
            $topProviders = PembelianCarbonCredit::with('penyedia')
                ->when($companyFilter, function($query) use ($companyFilter) {
                    return $query->where('kode_perusahaan', $companyFilter);
                })
                ->selectRaw('kode_penyedia, COUNT(*) as purchase_count, SUM(jumlah_kompensasi) as total_carbon, SUM(total_harga) as total_spent')
                ->groupBy('kode_penyedia')
                ->orderBy('purchase_count', 'desc')
                ->take(3)
                ->get();
            
            // Calculate environmental impact estimates with more detailed metrics
            $environmentalImpact = [
                'trees_saved' => round($totalCarbon * 50), // Approximate trees saved
                'water_saved' => round($totalCarbon * 100000), // Liters of water saved
                'energy_saved' => round($totalCarbon * 2500), // kWh of energy saved
                'waste_reduced' => round($totalCarbon * 300), // kg of waste reduced
                'co2_equivalent' => round($totalCarbon * 1000), // kg of CO2 equivalent
                'flights_avoided' => round($totalCarbon * 2.5), // Number of flights avoided
                'cars_removed' => round($totalCarbon * 0.2) // Cars removed from road for a year
            ];
            
            // Get company information if filtering by company
            $companyInfo = null;
            if ($companyFilter) {
                $companyInfo = \App\Models\Perusahaan::find($companyFilter);
            }
            
            // Get year-to-date and month-to-date statistics
            $ytdCarbon = PembelianCarbonCredit::when($companyFilter, function($query) use ($companyFilter) {
                    return $query->where('kode_perusahaan', $companyFilter);
                })
                ->whereYear('tanggal_pembelian', now()->year)
                ->sum('jumlah_kompensasi') / 1000; // Convert to tons
                
            $mtdCarbon = PembelianCarbonCredit::when($companyFilter, function($query) use ($companyFilter) {
                    return $query->where('kode_perusahaan', $companyFilter);
                })
                ->whereYear('tanggal_pembelian', now()->year)
                ->whereMonth('tanggal_pembelian', now()->month)
                ->sum('jumlah_kompensasi') / 1000; // Convert to tons
            
            return view('admin.carbon-credit-purchase.dashboard', compact(
                'recentPurchases', 
                'pendingCompensations', 
                'formattedMonthlyData', 
                'totalCarbon', 
                'totalSpent', 
                'avgPricePerTon',
                'transactionCount',
                'environmentalImpact',
                'topProviders',
                'companyInfo',
                'ytdCarbon',
                'mtdCarbon'
            ));
            
        } catch (\Exception $e) {
            // Log error
            Log::error("Error loading carbon credit dashboard", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'user_role' => Auth::user() ? Auth::user()->role : 'unknown'
            ]);
            
            // Return view with error message
            return view('admin.carbon-credit-purchase.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat dashboard. Silakan coba lagi atau hubungi administrator.');
        }
    }
    

}
