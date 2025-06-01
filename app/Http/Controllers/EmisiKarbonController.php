<?php

namespace App\Http\Controllers;

use App\Models\EmisiKarbon;
use App\Models\FaktorEmisi; 
use App\Models\User;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class EmisiKarbonController extends Controller
{
    /**
     * Display a listing of the resource untuk staff.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'staff') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $emisiKarbons = EmisiKarbon::where('kode_perusahaan', $user->kode_perusahaan)
            ->with(['staff', 'faktorEmisi'])->latest()->paginate(10);
        return view('staff.emisicarbon.index', compact('emisiKarbons')); // Asumsi ada view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'staff') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // Ambil faktor emisi untuk perusahaan ini
        $allFaktorEmisi = FaktorEmisi::where('kode_perusahaan', $user->kode_perusahaan)->get();
        $kategoriEmisi = [];
        foreach ($allFaktorEmisi as $faktor) {
            $kategoriEmisi[$faktor->kategori_emisi_karbon][] = $faktor;
        }

        // return response()->json(['kategori_emisi' => $kategoriEmisi]); // Contoh jika API
        return view('staff.emisicarbon.create', compact('kategoriEmisi')); // Asumsi ada view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'staff') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        $request->validate([
            'kategori_emisi_karbon' => 'required|string|max:255',
            'sub_kategori' => 'required|string|max:255',
            'nilai_aktivitas' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'tanggal_emisi' => 'required|date',
            'kode_faktor_emisi' => 'required|string|exists:faktor_emisi,kode_faktor',
        ]);
        
        // Verifikasi faktor emisi yang dipilih sesuai dengan kategori, sub kategori, dan perusahaan
        $faktorEmisi = FaktorEmisi::where('kode_faktor', $request->kode_faktor_emisi)
            ->where('kategori_emisi_karbon', $request->kategori_emisi_karbon)
            ->where('sub_kategori', $request->sub_kategori)
            ->where('kode_perusahaan', $user->kode_perusahaan)
            ->first();
            
        if (!$faktorEmisi) {
            return redirect()->back()->with('error', 'Faktor emisi yang dipilih tidak sesuai dengan kategori dan sub kategori yang dipilih.')->withInput();
        }

        // Generate kode_emisi_carbon unik
        $kodeEmisiCarbon = 'EMC-' . strtoupper(Str::random(8));
        while (EmisiKarbon::where('kode_emisi_carbon', $kodeEmisiCarbon)->exists()) {
            $kodeEmisiCarbon = 'EMC-' . strtoupper(Str::random(8));
        }

        try {
            // Ambil nilai faktor emisi untuk digunakan jika trigger database tidak berfungsi
            $nilaiFaktorEmisi = $faktorEmisi->nilai_faktor;
            $nilaiAktivitas = $request->nilai_aktivitas;
            $kadarEmisiKarbon = $nilaiAktivitas * $nilaiFaktorEmisi;
            
           
            Log::info('Mencoba menyimpan emisi karbon dengan data:', [
                'kode_emisi_carbon' => $kodeEmisiCarbon,
                'kategori_emisi_karbon' => $request->kategori_emisi_karbon,
                'sub_kategori' => $request->sub_kategori,
                'nilai_aktivitas' => $nilaiAktivitas,
                'faktor_emisi' => $nilaiFaktorEmisi,
                'kadar_emisi_karbon' => $kadarEmisiKarbon,
                'kode_staff' => $user->kode_user,
                'kode_faktor_emisi' => $request->kode_faktor_emisi,
            ]);
            
         $emisicarbon = new EmisiKarbon();
         $emisicarbon->kode_emisi_carbon = $kodeEmisiCarbon;
         $emisicarbon->kategori_emisi_karbon = $request->kategori_emisi_karbon;
            $emisicarbon->sub_kategori = $request->sub_kategori;
     $emisicarbon->nilai_aktivitas = $nilaiAktivitas;
         $emisicarbon->faktor_emisi = $nilaiFaktorEmisi; 
            $emisicarbon->deskripsi = $request->deskripsi;
            $emisicarbon->status = $request->status ?? 'pending';
  $emisicarbon->tanggal_emisi = $request->tanggal_emisi;
          $emisicarbon->kode_staff = $user->kode_user;
            $emisicarbon->kode_faktor_emisi = $request->kode_faktor_emisi;
           $emisicarbon->kode_perusahaan = $user->kode_perusahaan;
            
            $saved = $emisicarbon->save();
            
            // Pastikan data berhasil disimpan
            if (!$saved) {
                // Log::error('Gagal menyimpan data emisi karbon');
                return redirect()->back()->with('error', 'Gagal menyimpan data emisi karbon. Silakan coba lagi.')->withInput();
            }
            
            // Log::info('Emisi karbon berhasil disimpan dengan ID: ' . $kodeEmisiCarbon);
            
            $emisiKarbon = EmisiKarbon::where('kode_emisi_carbon', $kodeEmisiCarbon)->first();
            if ($emisiKarbon && is_null($emisiKarbon->faktor_emisi)) {
                Log::warning('Trigger database tidak mengisi faktor_emisi, mengisi secara manual');
                $emisicarbon->faktor_emisi = $nilaiFaktorEmisi;
                $emisicarbon->save();
            }
            
            // Kirim notifikasi ke admin
            $admins = User::where('role', 'admin')
                ->where('kode_perusahaan', $user->kode_perusahaan)
                ->get();
            
            foreach ($admins as $admin) {
                $deskripsiNotifikasi = "Staff {$user->nama} telah menginputkan data emisi karbon baru dengan kode {$kodeEmisiCarbon}";
                NotifikasiController::buatNotifikasi(
                    'emisi_karbon',
                    $deskripsiNotifikasi,
                    $admin->kode_user,
                    null,
                    null
                );
            }
        } catch (\Exception $e) {
            // Log::error('Error saat menyimpan emisi karbon: ' . $e->getMessage());
            // Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('staff.emisicarbon.index') 
                         ->with('success', 'Emisi karbon berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(EmisiKarbon $emisicarbon)
    {
        // staff hanya bisa melihat emisi dari perusahaannya
        $user = Auth::user();
        if ($user->role !== 'staff' || $emisicarbon->kode_perusahaan !== $user->kode_perusahaan) {
    
            return redirect()->route('staff.emisicarbon.index')->with('error', 'Anda tidak memiliki izin untuk melihat data ini.');
        }
        return view('staff.emisicarbon.show', compact('emisicarbon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmisiKarbon $emisicarbon)
    {
        $user = Auth::user();
        // Periksa apakah user adalah staff dan apakah data emisi karbon milik perusahaannya
        if (!$user || $user->role !== 'staff') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }
        
        // Log::info('Attempting to edit EmisiKarbon with ID: ' . $emisicarbon->getKey());
        // Log::info('EmisiKarbon attributes: ', $emisicarbon->toArray());
        // Log::info('User company code: \'' . $user->kode_perusahaan . '\'. EmisiKarbon company code: \'' . $emisicarbon->kode_perusahaan . '\'.');

        // Pastikan staff hanya dapat mengedit data dari perusahaannya sendiri
        if ($emisicarbon->kode_perusahaan !== $user->kode_perusahaan) {
            
            // Log::warning('Authorization failed for edit: User company \'' . $user->kode_perusahaan . '\' vs EmisiKarbon company \'' . $emisicarbon->kode_perusahaan . '\' for EmisiKarbon ID: ' . $emisicarbon->getKey());
            return redirect()->route('staff.emisicarbon.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengedit data emisi ini.');
        }
        
        // Staff dapat mengedit data emisi karbon dari perusahaan yang sama

        $allFaktorEmisi = FaktorEmisi::where('kode_perusahaan', $user->kode_perusahaan)->get();
        $kategoriEmisi = [];
        foreach ($allFaktorEmisi as $faktor) {
            $kategoriEmisi[$faktor->kategori_emisi_karbon][] = $faktor;
        }
        return view('staff.emisicarbon.edit', compact('emisicarbon', 'kategoriEmisi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmisiKarbon $emisicarbon)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'staff') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        // Tambahkan logging untuk debug       
        // Log::info('Updating EmisiKarbon. User kode_perusahaan: \'' . $user->kode_perusahaan . '\'. EmisiKarbon kode_perusahaan: \'' . $emisicarbon->kode_perusahaan . '\'.');
        // Log::info('EmisiKarbon ID to update: ' . $emisicarbon->getKey());
        // Log::info('Request data for update: ', $request->all());

        // Pastikan staff hanya dapat mengupdate data dari perusahaannya sendiri
        if ($emisicarbon->kode_perusahaan !== $user->kode_perusahaan) {
            
            // Log::warning('Authorization failed for update: User company \'' . $user->kode_perusahaan . '\' vs EmisiKarbon company \'' . $emisicarbon->kode_perusahaan . '\' for EmisiKarbon ID: ' . $emisicarbon->getKey());
            return redirect()->route('staff.emisicarbon.index')
                         ->with('error', 'Anda tidak memiliki izin untuk mengupdate data emisi ini.');
        }

        $request->validate([
            'kategori_emisi_karbon' => 'required|string|max:255',
            'sub_kategori' => 'required|string|max:255',
            'nilai_aktivitas' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'tanggal_emisi' => 'required|date',
            'kode_faktor_emisi' => 'required|string|exists:faktor_emisi,kode_faktor',
        ]);

        // Verifikasi faktor emisi yang dipilih sesuai dengan kategori, sub kategori, dan perusahaan
        $faktorEmisi = FaktorEmisi::where('kode_faktor', $request->kode_faktor_emisi)
            ->where('kategori_emisi_karbon', $request->kategori_emisi_karbon)
            ->where('sub_kategori', $request->sub_kategori)
            ->where('kode_perusahaan', $user->kode_perusahaan)
            ->first();

        if (!$faktorEmisi) {
            return redirect()->back()->with('error', 'Faktor emisi yang dipilih tidak sesuai dengan kategori dan sub kategori yang dipilih.')->withInput();
        }

        try {
            $nilaiFaktorEmisi = $faktorEmisi->nilai_faktor;
            $nilaiAktivitas = $request->nilai_aktivitas;
            // $kadarEmisiKarbon = $nilaiAktivitas * $nilaiFaktorEmisi; // Kadar emisi dihitung oleh trigger atau virtual column

            
            Log::info('Data to be updated for EmisiKarbon ID ' . $emisicarbon->getKey() . ':', [
                'kategori_emisi_karbon' => $request->kategori_emisi_karbon,
                'sub_kategori' => $request->sub_kategori,
                'nilai_aktivitas' => $nilaiAktivitas,
                'faktor_emisi' => $nilaiFaktorEmisi, 
                'status' => $request->status ?? 'pending',
                'tanggal_emisi' => $request->tanggal_emisi,
                'kode_faktor_emisi' => $request->kode_faktor_emisi,
            ]);

            $emisicarbon->kategori_emisi_karbon = $request->kategori_emisi_karbon;
            $emisicarbon->sub_kategori = $request->sub_kategori;
            $emisicarbon->nilai_aktivitas = $nilaiAktivitas;
            $emisicarbon->faktor_emisi = $nilaiFaktorEmisi; 
            $emisicarbon->deskripsi = $request->deskripsi;
            $emisicarbon->status = $request->status ?? 'pending';
            $emisicarbon->tanggal_emisi = $request->tanggal_emisi;
            $emisicarbon->kode_faktor_emisi = $request->kode_faktor_emisi;
            
            $updated = $emisicarbon->save();

            if (!$updated) {
                
                // Log::error('Gagal mengupdate data emisi karbon untuk ID: ' . $emisicarbon->getKey());
                return redirect()->back()->with('error', 'Gagal mengupdate data emisi karbon. Silakan coba lagi.')->withInput();
            }

            
            // Log::info('Emisi karbon berhasil diupdate untuk ID: ' . $emisicarbon->getKey());

            $emisicarbon->refresh(); 
            // Log::info('Data EmisiKarbon setelah refresh dari database (ID: ' . $emisicarbon->getKey() . '): ', $emisicarbon->toArray());
            if (is_null($emisicarbon->faktor_emisi)) {
                
                // Log::warning('Trigger database tidak mengisi faktor_emisi saat update, memastikan nilai manual tersimpan untuk ID: ' . $emisicarbon->getKey());
                $emisicarbon->faktor_emisi = $nilaiFaktorEmisi;
                $emisicarbon->save();
            }

        } catch (\Exception $e) {
            
            // Log::error('Error saat mengupdate emisi karbon ID ' . $emisicarbon->getKey() . ': ' . $e->getMessage());
            // Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('staff.emisicarbon.index')
                         ->with('success', 'Emisi karbon berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmisiKarbon $emisicarbon)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'staff') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }
        
//     Log::info('Attempting to delete EmisiKarbon with ID: ' . $emisicarbon->getKey());
// Log::info('EmisiKarbon attributes to be deleted: ', $emisicarbon->toArray());
//     Log::info('User company code: \'' . $user->kode_perusahaan . '\'. EmisiKarbon company code: \'' . $emisicarbon->kode_perusahaan . '\'.');

       
     if ($emisicarbon->kode_perusahaan !== $user->kode_perusahaan) {
            
        //  Log::warning('Authorization failed for delete: User company \'' . $user->kode_perusahaan . '\' vs EmisiKarbon company \'' . $emisicarbon->kode_perusahaan . '\' for EmisiKarbon ID: ' . $emisicarbon->getKey());
            return redirect()->route('staff.emisicarbon.index')
                         ->with('error', 'Anda tidak memiliki izin untuk menghapus data emisi ini.');
        }

        try {
            $deleted = $emisicarbon->delete();
            if (!$deleted) {
                
                // Log::error('Gagal menghapus data emisi karbon untuk ID: ' . $emisicarbon->getKey());
                return redirect()->route('staff.emisicarbon.index')
                             ->with('error', 'Gagal menghapus data emisi karbon.');
            }
            
            Log::info('Emisi karbon berhasil dihapus untuk ID: ' . $emisicarbon->getKey());
        } catch (\Exception $e) {
            
        //  Log::error('Error saat menghapus emisi karbon ID ' . $emisicarbon->getKey() . ': ' . $e->getMessage());
        //  Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('staff.emisicarbon.index')
                         ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
        
        return redirect()->route('staff.emisicarbon.index')
                         ->with('success', 'Emisi karbon berhasil dihapus.');
    }

    /**
     * Menampilkan daftar emisi karbon untuk admin.
     */
    public function adminIndex()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $emisiCarbons = EmisiKarbon::where('kode_perusahaan', $user->kode_perusahaan)
            ->with(['staff', 'faktorEmisi'])->latest()->paginate(10);
        return view('admin.emisicarbon.index', compact('emisiCarbons'));
    }

    /**
     * Menampilkan form untuk mengubah status emisi karbon oleh admin.
     */
    public function editStatus(EmisiKarbon $emisicarbon)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // Pastikan admin hanya dapat mengedit status dari perusahaannya sendiri
        if ($emisicarbon->kode_perusahaan !== $user->kode_perusahaan) {
            return redirect()->route('admin.emisicarbon.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengubah status data emisi ini.');
        }

        return view('admin.emisicarbon.edit-status', compact('emisicarbon'));
    }

    /**
     * Menyimpan perubahan status emisi karbon oleh admin.
     */
    public function updateStatus(Request $request, EmisiKarbon $emisicarbon)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        // Pastikan admin hanya dapat mengupdate status dari perusahaannya sendiri
        if ($emisicarbon->kode_perusahaan !== $user->kode_perusahaan) {
            return redirect()->route('admin.emisicarbon.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengubah status data emisi ini.');
        }

        $request->validate([
            'status' => 'required|string|in:pending,approved,rejected',
            'catatan' => 'nullable|string',
        ]);

        try {
            // Simpan status lama untuk notifikasi
            $statusLama = $emisicarbon->status;
            
            $emisicarbon->status = $request->status;
            // Jika diperlukan, tambahkan kolom catatan di tabel emisi_carbon
            // $emisicarbon->catatan = $request->catatan;
            
            $updated = $emisicarbon->save();

            if (!$updated) {
                return redirect()->back()->with('error', 'Gagal mengupdate status emisi karbon. Silakan coba lagi.');
            }

            // Kirim notifikasi ke staff yang menginputkan data
            $staff = User::where('kode_user', $emisicarbon->kode_staff)->first();
            if ($staff) {
                $deskripsiNotifikasi = "Admin {$user->nama} telah mengubah status data emisi karbon {$emisicarbon->kode_emisi_carbon} dari {$statusLama} menjadi {$request->status}";
                NotifikasiController::buatNotifikasi(
                    'status_emisi',
                    $deskripsiNotifikasi,
                    null,
                    $staff->kode_user,
                    null
                );
            }

            Log::info('Status emisi karbon berhasil diupdate untuk ID: ' . $emisicarbon->getKey() . ' menjadi: ' . $request->status);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate status: ' . $e->getMessage());
        }

        return redirect()->route('admin.emisicarbon.index')
                         ->with('success', 'Status emisi karbon berhasil diupdate.');
    }
    
    /**
     * Generate PDF report of emission data
     *
     * @return \Illuminate\Http\Response
     */
    public function report()
    {
        $user = Auth::user();
        
        // Verify user has permission to access this report
        if (!in_array($user->role, ['admin', 'staff', 'manager'])) {
            return redirect()->route('login') ->with('error', 'Anda tidak memiliki izin untuk mengakses laporan ini.');
        }
        
        // Get emission data with calculated fields
        $emisiKarbons = EmisiKarbon::select([
                'emisi_carbon.kode_emisi_carbon',
                'emisi_carbon.kategori_emisi_karbon',
                'emisi_carbon.sub_kategori',
                'emisi_carbon.nilai_aktivitas',
                'emisi_carbon.faktor_emisi',
                'emisi_carbon.kadar_emisi_karbon',
                DB::raw('ROUND(emisi_carbon.kadar_emisi_karbon / 1000, 2) as kadar_emisi_ton'),
                'emisi_carbon.tanggal_emisi',
                'emisi_carbon.status',
                'users.nama as nama_staff'
            ])
            ->leftJoin('users', 'emisi_carbon.kode_staff', '=', 'users.kode_user')
            ->where('emisi_carbon.kode_perusahaan', $user->kode_perusahaan)
            ->orderBy('emisi_carbon.tanggal_emisi', 'desc')
            ->get();
        
        // Calculate total emissions
        $totalEmisi = $emisiKarbons->sum('kadar_emisi_karbon') / 1000; // Convert to tons
        
        // Group emissions by category for summary
        $emisiByKategori = $emisiKarbons->groupBy('kategori_emisi_karbon')
            ->map(function($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('kadar_emisi_karbon') / 1000, // Convert to tons
                ];
            });
        
        // Generate PDF
        $pdf = Pdf::loadView('staff.emisicarbon.report', compact(
            'emisiKarbons', 
            'totalEmisi',
            'emisiByKategori',
            'user'
        ));
        
        return $pdf->stream('laporan_emisi_karbon.pdf');
    }
}
