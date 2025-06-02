<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KompensasiEmisiCarbon;
use App\Models\EmisiKarbon;
use App\Models\User;
use App\Http\Controllers\NotifikasiController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KompensasiEmisiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data emisi approved dengan relasi kompensasi, hitung total kompensasi dan sisa emisi
        $emisiApproved = EmisiKarbon::select([
                'emisi_carbon.kode_emisi_carbon as kode_emisi_carbon',
                'kategori_emisi_karbon',
                'sub_kategori',
                'nilai_aktivitas',
                'faktor_emisi',
                'kadar_emisi_karbon',
                'deskripsi',
                'status',
                'tanggal_emisi',
                DB::raw('(kadar_emisi_karbon / 1000) as emisi_ton'),
                DB::raw('COALESCE(SUM(kompensasi_emisi_carbon.jumlah_kompensasi),0) / 1000 as kompensasi_ton'),
                DB::raw('((kadar_emisi_karbon - COALESCE(SUM(kompensasi_emisi_carbon.jumlah_kompensasi),0)) / 1000) as sisa_emisi_ton')
            ])
            ->leftJoin('kompensasi_emisi_carbon', 'emisi_carbon.kode_emisi_carbon', '=', 'kompensasi_emisi_carbon.kode_emisi_carbon')
            ->where('status', 'approved')
            ->groupBy(
                'kode_emisi_carbon',
                'kategori_emisi_karbon',
                'sub_kategori',
                'nilai_aktivitas',
                'faktor_emisi',
                'kadar_emisi_karbon',
                'deskripsi',
                'status',
                'tanggal_emisi'
            )
            ->get();

        // Query riwayat kompensasi dengan filter
        $riwayatQuery = KompensasiEmisiCarbon::select([
                'kompensasi_emisi_carbon.kode_kompensasi',
                'kompensasi_emisi_carbon.kode_emisi_carbon',
                DB::raw('ROUND(kompensasi_emisi_carbon.jumlah_kompensasi / 1000, 2) as jumlah_ton'),
                'kompensasi_emisi_carbon.tanggal_kompensasi',
                'kompensasi_emisi_carbon.status_kompensasi as status',
                DB::raw('COALESCE(emisi_carbon.kategori_emisi_karbon, "-") as kategori_emisi'),
                DB::raw('COALESCE(emisi_carbon.sub_kategori, "-") as sub_kategori'),
            ])
            ->leftJoin('emisi_carbon', 'kompensasi_emisi_carbon.kode_emisi_carbon', '=', 'emisi_carbon.kode_emisi_carbon')
            ->orderByDesc('kompensasi_emisi_carbon.created_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $riwayatQuery->where(function($q) use ($search) {
                $q->where('kompensasi_emisi_carbon.kode_kompensasi', 'like', "%$search%")
                  ->orWhere('kompensasi_emisi_carbon.kode_emisi_carbon', 'like', "%$search%")
                  ->orWhere('emisi_carbon.kategori_emisi_karbon', 'like', "%$search%")
                  ->orWhere('emisi_carbon.sub_kategori', 'like', "%$search%");
            });
        }
        if ($request->filled('start_date')) {
            $riwayatQuery->whereDate('kompensasi_emisi_carbon.tanggal_kompensasi', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $riwayatQuery->whereDate('kompensasi_emisi_carbon.tanggal_kompensasi', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $riwayatQuery->where('kompensasi_emisi_carbon.status_kompensasi', $request->status);
        }
        if ($request->filled('kategori')) {
            $riwayatQuery->where('emisi_carbon.kategori_emisi_karbon', $request->kategori);
        }

        $riwayatKompensasi = $riwayatQuery->get();

        // Agregasi kategori emisi
        $kategoriEmisi = $emisiApproved->groupBy('kategori_emisi_karbon')->map(function($items) {
            return [
                'kategori' => $items->first()->kategori_emisi_karbon,
                'total' => $items->sum('emisi_ton'),
                'terkompensasi' => $items->sum('kompensasi_ton'),
                'sisa' => $items->sum('sisa_emisi_ton')
            ];
        });

        // Kategori distinct
        $kategoris = EmisiKarbon::select('kategori_emisi_karbon')
                    ->distinct()
                    ->whereNotNull('kategori_emisi_karbon')
                    ->orderBy('kategori_emisi_karbon')
                    ->get();

        return view('manager.kompensasi.index', compact(
            'emisiApproved',
            'riwayatKompensasi',
            'kategoriEmisi',
            'kategoris'
        ));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_emisi_carbon' => 'required|exists:emisi_carbon,kode_emisi_carbon',
            'jumlah_kompensasi' => 'required|numeric|min:0.001', // Ubah validasi menjadi ton
            'tanggal_kompensasi' => 'required|date',
        ]);

        // Ambil data emisi karbon
        $emisi = EmisiKarbon::where('kode_emisi_carbon', $request->kode_emisi_carbon)->first();
        
        if (!$emisi) {
            return redirect()->back()->with('error', 'Data emisi karbon tidak ditemukan.');
        }

        // Jumlah kompensasi langsung dalam ton
        $jumlahKompensasiTon = $request->jumlah_kompensasi;

        // Buat kode kompensasi (K + timestamp)
        $kodeKompensasi = 'K' . Carbon::now()->format('YmdHis');

        $manager = Auth::user();
        // Simpan data kompensasi
        $kompensasi = new KompensasiEmisiCarbon();
        $kompensasi->kode_kompensasi = $kodeKompensasi;
            $kompensasi->kode_emisi_carbon = $request->kode_emisi_carbon;
            $kompensasi->jumlah_kompensasi = $jumlahKompensasiTon * 1000; // Simpan dalam kg di database
            $kompensasi->tanggal_kompensasi = $request->tanggal_kompensasi;
            $kompensasi->status_kompensasi = 'Belum Terkompensasi'; // Default pending, menunggu approval admin
            $kompensasi->kode_manager = $manager->kode_user;
            $kompensasi->kode_perusahaan = $manager->kode_perusahaan;
            $kompensasi->save();
            
            // Kirim notifikasi ke admin
            $admins = User::where('role', 'admin')
                ->where('kode_perusahaan', $manager->kode_perusahaan)
                ->get();
            
            foreach ($admins as $admin) {
                $deskripsiNotifikasi = "Manager {$manager->nama} telah menginputkan data kompensasi emisi karbon baru dengan kode {$kodeKompensasi}";
                NotifikasiController::buatNotifikasi(
                    'kompensasi_emisi',
                    $deskripsiNotifikasi,
                    $admin->kode_user,
                    null,
                    null
                );
            }

        return redirect()->back()->with('success', 'Data kompensasi berhasil disimpan dan menunggu persetujuan.');
    }

    public function show($kodeKompensasi)
    {
        $kompensasi = KompensasiEmisiCarbon::select([
                'kompensasi_emisi_carbon.*',
                DB::raw('ROUND(kompensasi_emisi_carbon.jumlah_kompensasi / 1000, 2) as jumlah_ton'),
                'emisi_carbon.*',
                DB::raw('ROUND(emisi_carbon.kadar_emisi_karbon / 1000, 2) as kadar_emisi_ton'),
                DB::raw('COALESCE(emisi_carbon.kategori_emisi_karbon, "-") as kategori_emisi'),
                DB::raw('COALESCE(emisi_carbon.sub_kategori, "-") as sub_kategori'),
            ])
            ->leftJoin('emisi_carbon', 'kompensasi_emisi_carbon.kode_emisi_carbon', '=', 'emisi_carbon.kode_emisi_carbon')
            ->where('kompensasi_emisi_carbon.kode_kompensasi', $kodeKompensasi)
            ->first();

        if (!$kompensasi) {
            abort(404);
        }

        return view('manager.kompensasi.show', compact('kompensasi'));
    }

    public function update(Request $request, $kodeKompensasi)
    {
        $request->validate([
            'jumlah_kompensasi' => 'required|numeric|min:0.001', // Ubah validasi menjadi ton
        ]);

        $manager = Auth::user();
        DB::beginTransaction();

        try {
            $jumlahKompensasiTon = $request->jumlah_kompensasi;

            $updated = KompensasiEmisiCarbon::where('kode_kompensasi', $kodeKompensasi)
                ->where('status_kompensasi', 'Belum Terkompensasi')
                ->update([
                    'jumlah_kompensasi' => $jumlahKompensasiTon * 1000, // Simpan dalam kg di database
                    'updated_at' => now(),
                    'kode_manager' => $manager->kode_user,
                ]);

            if (!$updated) {
                DB::rollBack();
                return back()->with('error', 'Gagal mengupdate data kompensasi');
            }

            DB::commit();

            return redirect()->route('manager.manager.kompensasi.index')
                ->with('success', 'Data kompensasi berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($kodeKompensasi)
    {
        $deleted = KompensasiEmisiCarbon::where('kode_kompensasi', $kodeKompensasi)
            ->where('status_kompensasi', 'Belum Terkompensasi')
            ->delete();

        if (!$deleted) {
            return back()->with('error', 'Gagal menghapus data kompensasi');
        }

        return redirect()->route('manager.kompensasi.index')
            ->with('success', 'Data kompensasi berhasil dihapus');
    }

    public function edit($kodeKompensasi)
    {
        $kompensasi = KompensasiEmisiCarbon::select([
                'kompensasi_emisi_carbon.*',
                DB::raw('ROUND(kompensasi_emisi_carbon.jumlah_kompensasi / 1000, 2) as jumlah_ton'),
                'emisi_carbon.*',
                DB::raw('ROUND(emisi_carbon.kadar_emisi_karbon / 1000, 2) as kadar_emisi_ton'),
                DB::raw('COALESCE(emisi_carbon.kategori_emisi_karbon, "-") as kategori_emisi'),
                DB::raw('COALESCE(emisi_carbon.sub_kategori, "-") as sub_kategori'),
            ])
            ->leftJoin('emisi_carbon', 'kompensasi_emisi_carbon.kode_emisi_carbon', '=', 'emisi_carbon.kode_emisi_carbon')
            ->where('kompensasi_emisi_carbon.kode_kompensasi', $kodeKompensasi)
            ->where('kompensasi_emisi_carbon.status_kompensasi', 'Belum Terkompensasi')
            ->first();

        if (!$kompensasi) {
            abort(404);
        }

        return view('manager.kompensasi.edit', compact('kompensasi'));
    }

    public function report()
    {
        $kompensasi = KompensasiEmisiCarbon::select([
                'kompensasi_emisi_carbon.kode_kompensasi',
                'kompensasi_emisi_carbon.kode_emisi_carbon',
                DB::raw('ROUND(kompensasi_emisi_carbon.jumlah_kompensasi / 1000, 2) as jumlah_ton'),
                'kompensasi_emisi_carbon.tanggal_kompensasi',
                'kompensasi_emisi_carbon.status_kompensasi as status',
                DB::raw('COALESCE(emisi_carbon.kategori_emisi_karbon, "-") as kategori_emisi'),
                DB::raw('COALESCE(emisi_carbon.sub_kategori, "-") as sub_kategori'),
            ])
            ->leftJoin('emisi_carbon', 'kompensasi_emisi_carbon.kode_emisi_carbon', '=', 'emisi_carbon.kode_emisi_carbon')
            ->orderByDesc('kompensasi_emisi_carbon.created_at')
            ->get();

        $pdf = Pdf::loadView('kompensasi.report', compact('kompensasi'));
        return $pdf->stream('laporan_kompensasi.pdf');
    }
}