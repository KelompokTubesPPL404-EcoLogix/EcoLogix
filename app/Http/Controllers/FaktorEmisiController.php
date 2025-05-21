<?php

namespace App\Http\Controllers;

use App\Models\FaktorEmisi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class FaktorEmisiController extends Controller
{
    // Helper function to determine view path based on user role
    private function getViewPrefix()
    {
        if(Auth::user()->role === 'admin') {
            return 'admin.faktor-emisi';
        } else if(Auth::user()->role === 'manager') {
            return 'manager.faktor-emisi';
        }
    }
    
    // Helper function to determine route name based on user role
    private function getRoutePrefix()
    {
        if(Auth::user()->role === 'admin') {
            return 'admin.faktor-emisi';
        } else if(Auth::user()->role === 'manager') {
            return 'manager.faktor-emisi';
        }
    }
   
    public function index()
    {
        $user = Auth::user();
        $faktorEmisi = FaktorEmisi::where('kode_perusahaan', $user->kode_perusahaan)->get();
        return view($this->getViewPrefix().'.index', compact('faktorEmisi'));
    }

  
    public function create()
    {
        $faktorEmisi = FaktorEmisi::all();
        return view($this->getViewPrefix().'.create', compact('faktorEmisi')); 
    }

   
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Hanya manager yang dapat membuat faktor emisi
        if ($user->role !== 'manager') {
            return redirect()->back()->with('error', 'Hanya manager yang dapat membuat faktor emisi.');
        }
        
        $request->validate([
            'kategori_emisi_karbon' => 'required',
            'sub_kategori' => 'required',
            'nilai_faktor' => 'required|numeric',
            'satuan' =>'required'
        ]);
        
        // Periksa apakah kombinasi sub_kategori dan kode_perusahaan sudah ada
        $exists = FaktorEmisi::where('sub_kategori', $request->sub_kategori)
            ->where('kode_perusahaan', $user->kode_perusahaan)
            ->exists();
            
        if ($exists) {
            return redirect()->back()->with('error', 'Faktor emisi dengan sub kategori tersebut sudah ada untuk perusahaan ini.')
                ->withInput();
        }
        
        $kodeFaktorEmisi = 'FE-' . Str::random(5); 
        FaktorEmisi::create([
            'kode_faktor' => $kodeFaktorEmisi,
            'kategori_emisi_karbon' => $request->kategori_emisi_karbon,
            'sub_kategori' => $request->sub_kategori,
            'nilai_faktor' => $request->nilai_faktor,
            'satuan' => $request->satuan,
            'kode_perusahaan' => $user->kode_perusahaan
        ]);
        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Faktor Emisi berhasil ditambahkan!');
    }

    
    public function show($kodeFaktorEmisi)
    {
        $user = Auth::user();
        $faktorEmisi = FaktorEmisi::where('kode_faktor', $kodeFaktorEmisi)
            ->where('kode_perusahaan', $user->kode_perusahaan)
            ->firstOrFail();
        return view($this->getViewPrefix().'.show', compact('faktorEmisi')); 
    }

    
    public function edit($kodeFaktorEmisi)
    {
        $user = Auth::user();
        
        // Hanya manager yang dapat mengedit faktor emisi
        if ($user->role !== 'manager') {
            return redirect()->back()->with('error', 'Hanya manager yang dapat mengedit faktor emisi.');
        }
        
        $faktorEmisi = FaktorEmisi::where('kode_faktor', $kodeFaktorEmisi)
            ->where('kode_perusahaan', $user->kode_perusahaan)
            ->firstOrFail();
        return view($this->getViewPrefix().'.edit', compact('faktorEmisi')); 
    }

    
    public function update(Request $request, $kodeFaktorEmisi)
    {
        $user = Auth::user();
        
        // Hanya manager yang dapat memperbarui faktor emisi
        if ($user->role !== 'manager') {
            return redirect()->back()->with('error', 'Hanya manager yang dapat memperbarui faktor emisi.');
        }
        
        $faktorEmisi = FaktorEmisi::where('kode_faktor', $kodeFaktorEmisi)
            ->where('kode_perusahaan', $user->kode_perusahaan)
            ->firstOrFail();   
            
        $request->validate([
            'kategori_emisi_karbon' =>'required',
            'sub_kategori' =>'required',
            'nilai_faktor' =>'required|numeric',
            'satuan' =>'required'
        ]);
        
        // Periksa apakah kombinasi sub_kategori dan kode_perusahaan sudah ada (kecuali untuk faktor emisi ini sendiri)
        $exists = FaktorEmisi::where('sub_kategori', $request->sub_kategori)
            ->where('kode_perusahaan', $user->kode_perusahaan)
            ->where('kode_faktor', '!=', $kodeFaktorEmisi)
            ->exists();
            
        if ($exists) {
            return redirect()->back()->with('error', 'Faktor emisi dengan sub kategori tersebut sudah ada untuk perusahaan ini.')
                ->withInput();
        }
        
        $faktorEmisi->update([
            'kategori_emisi_karbon' => $request->kategori_emisi_karbon,
            'sub_kategori' => $request->sub_kategori,
            'nilai_faktor' => $request->nilai_faktor,
            'satuan' => $request->satuan
        ]);
        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Faktor Emisi berhasil diupdate!');
    }

   
    public function destroy($kodeFaktorEmisi)
    {
        $user = Auth::user();
        
        // Hanya manager yang dapat menghapus faktor emisi
        if ($user->role !== 'manager') {
            return redirect()->back()->with('error', 'Hanya manager yang dapat menghapus faktor emisi.');
        }
        
        $faktorEmisi = FaktorEmisi::where('kode_faktor', $kodeFaktorEmisi)
            ->where('kode_perusahaan', $user->kode_perusahaan)
            ->firstOrFail();
            
        // Periksa apakah faktor emisi sedang digunakan oleh emisi karbon
        if ($faktorEmisi->emisiCarbon()->count() > 0) {
            return redirect()->route($this->getRoutePrefix().'.index')
                ->with('error', 'Faktor Emisi tidak dapat dihapus karena sedang digunakan oleh data Emisi Karbon!');
        }
        
        $faktorEmisi->delete();
        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Faktor Emisi berhasil dihapus!');
    }
}
