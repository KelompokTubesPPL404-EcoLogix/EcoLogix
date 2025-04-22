<?php

namespace App\Http\Controllers;

use App\Models\FaktorEmisi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaktorEmisiController extends Controller
{
   
    public function index()
    {
        $faktorEmisi = FaktorEmisi::all();
        return view('faktor-emisi.index', compact('faktorEmisi'));
    }

  
    public function create()
    {
        $faktorEmisi = FaktorEmisi::all();
        return view('faktor-emisi.create', compact('faktorEmisi')); 
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'kategori_emisi_karbon' => 'required',
            'sub_kategori' => 'required',
            'nilai_faktor' => 'required|numeric',
            'satuan' =>'required'
        ]);
        $kodeFaktorEmisi = 'FE-' . Str::random(5); 
        FaktorEmisi::create([
            'kode_faktor' => $kodeFaktorEmisi,
            'kategori_emisi_karbon' => $request->kategori_emisi_karbon,
            'sub_kategori' => $request->sub_kategori,
            'nilai_faktor' => $request->nilai_faktor,
           'satuan' => $request->satuan
        ]);
        return redirect()->route('faktor-emisi.index')
            ->with('success', 'Faktor Emisi berhasil ditambahkan!');
    }

    
    public function show($kodeFaktorEmisi)
    {
        $faktorEmisi = FaktorEmisi::findOrFail($kodeFaktorEmisi);
        return view('faktor-emisi.show', compact('faktorEmisi')); 
    }

    
    public function edit($kodeFaktorEmisi)
    {
        $faktorEmisi = FaktorEmisi::findOrFail($kodeFaktorEmisi);
        return view('faktor-emisi.edit', compact('faktorEmisi')); 
    }

    
    public function update(Request $request, $kodeFaktorEmisi)
    {
        $faktorEmisi = FaktorEmisi::findOrFail($kodeFaktorEmisi);   
        $request->validate([
            'kategori_emisi_karbon' =>'required',
           'sub_kategori' =>'required',
            'nilai_faktor' =>'required|numeric',
           'satuan' =>'required'
        ]);
        $faktorEmisi->update([
            'kategori_emisi_karbon' => $request->kategori_emisi_karbon,
          'sub_kategori' => $request->sub_kategori,
            'nilai_faktor' => $request->nilai_faktor,
          'satuan' => $request->satuan
        ]);
        return redirect()->route('faktor-emisi.index')
            ->with('success', 'Faktor Emisi berhasil diupdate!');
    }

   
    public function destroy($kodeFaktorEmisi)
    {
        $faktorEmisi = FaktorEmisi::findOrFail($kodeFaktorEmisi);
        $faktorEmisi->delete();
        return redirect()->route('faktor-emisi.index')
            ->with('success', 'Faktor Emisi berhasil dihapus!');
    }
}
