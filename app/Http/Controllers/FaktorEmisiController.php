<?php

namespace App\Http\Controllers;

use App\Models\FaktorEmisi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaktorEmisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faktorEmisi = FaktorEmisi::all();
        return view('faktor-emisi.index', compact('faktorEmisi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $faktorEmisi = FaktorEmisi::all();
        return view('faktor-emisi.create', compact('faktorEmisi')); // Mengirimkan data faktorEmisi ke view create.blade.php untuk diisikan oleh formulir formulir dat
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_emisi_karbon' => 'required',
            'sub_kategori' => 'required',
            'nilai_faktor' => 'required|numeric',
            'satuan' =>'required'
        ]);
        $kodeFaktorEmisi = 'FE-' . Str::random(5); // Generate kode faktor emisi secara acak
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

    /**
     * Display the specified resource.
     */
    public function show($kodeFaktorEmisi)
    {
        $faktorEmisi = FaktorEmisi::findOrFail($kodeFaktorEmisi);
        return view('faktor-emisi.show', compact('faktorEmisi')); // Mengirimkan data faktorEmisi ke view show.blade.php untuk ditampilkan detail inf
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kodeFaktorEmisi)
    {
        $faktorEmisi = FaktorEmisi::findOrFail($kodeFaktorEmisi);
        return view('faktor-emisi.edit', compact('faktorEmisi')); // Mengirimkan data faktorEmisi ke view edit.blade.php untuk diisikan oleh formulir formulir dat
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kodeFaktorEmisi)
    {
        $faktorEmisi = FaktorEmisi::findOrFail($kodeFaktorEmisi);
        $faktorEmisi->delete();
        return redirect()->route('faktor-emisi.index')
            ->with('success', 'Faktor Emisi berhasil dihapus!');
    }
}
