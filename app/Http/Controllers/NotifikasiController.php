<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotifikasiController extends Controller
{
    /**
     * Membuat notifikasi baru
     *
     * @param string $kategori Kategori notifikasi (emisi_karbon, status_emisi, kompensasi_emisi)
     * @param string $deskripsi Deskripsi notifikasi
     * @param string $kodeAdmin Kode admin yang akan menerima notifikasi
     * @param string $kodeStaff Kode staff yang akan menerima notifikasi
     * @param string $kodeManager Kode manager yang akan menerima notifikasi
     * @return Notifikasi
     */
    public static function buatNotifikasi($kategori, $deskripsi, $kodeAdmin = null, $kodeStaff = null, $kodeManager = null)
    {
        // Generate kode notifikasi unik
        $kodeNotifikasi = 'NOTIF-' . date('YmdHis') . '-' . Str::random(5);
        
        // Buat notifikasi baru
        $notifikasi = new Notifikasi();
        $notifikasi->kode_notifikasi = $kodeNotifikasi;
        $notifikasi->kategori_notifikasi = $kategori;
        $notifikasi->deskripsi = $deskripsi;
        $notifikasi->tanggal_notifikasi = Carbon::now()->toDateString();
        $notifikasi->kode_admin = $kodeAdmin;
        $notifikasi->kode_staff = $kodeStaff;
        $notifikasi->kode_manager = $kodeManager;
        $notifikasi->dibaca = false; // Inisialisasi status dibaca sebagai false (belum dibaca)
        $notifikasi->save();
        
        return $notifikasi;
    }
    
    /**
     * Mendapatkan notifikasi untuk user yang sedang login
     *
     * @return \Illuminate\Http\Response
     */
    public function getNotifikasi()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $notifikasi = [];
        
        // Jika user adalah admin, ambil notifikasi untuk admin
        if ($user->role === 'admin') {
            $notifikasi = Notifikasi::where('kode_admin', $user->kode_user)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        // Jika user adalah staff, ambil notifikasi untuk staff
        if ($user->role === 'staff') {
            $notifikasi = Notifikasi::where('kode_staff', $user->kode_user)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        // Jika user adalah manager, ambil notifikasi untuk manager
        if ($user->role === 'manager') {
            $notifikasi = Notifikasi::where('kode_manager', $user->kode_user)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return response()->json($notifikasi);
    }
    
    /**
     * Menandai semua notifikasi sebagai dibaca untuk user yang sedang login
     *
     * @return \Illuminate\Http\Response
     */
    public function markAsRead()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Tentukan kondisi berdasarkan role user
        $condition = [];
        
        if ($user->role === 'admin') {
            $condition['kode_admin'] = $user->kode_user;
        } elseif ($user->role === 'staff') {
            $condition['kode_staff'] = $user->kode_user;
        } elseif ($user->role === 'manager') {
            $condition['kode_manager'] = $user->kode_user;
        }
        
        // Update semua notifikasi yang belum dibaca
        Notifikasi::where($condition)
            ->where('dibaca', false)
            ->update(['dibaca' => true]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Menandai satu notifikasi sebagai dibaca berdasarkan ID
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function markOneAsRead(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Validasi request
        $request->validate([
            'id' => 'required|exists:notifikasi,kode_notifikasi'
        ]);
        
        // Tentukan kondisi berdasarkan role user
        $condition = [];
        
        if ($user->role === 'admin') {
            $condition['kode_admin'] = $user->kode_user;
        } elseif ($user->role === 'staff') {
            $condition['kode_staff'] = $user->kode_user;
        } elseif ($user->role === 'manager') {
            $condition['kode_manager'] = $user->kode_user;
        }
        
        // Tambahkan kondisi kode_notifikasi
        $condition['kode_notifikasi'] = $request->id;
        
        // Update notifikasi yang dipilih
        $updated = Notifikasi::where($condition)
            ->update(['dibaca' => true]);
        
        if ($updated) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Notifikasi tidak ditemukan atau bukan milik Anda'], 404);
        }
    }
}