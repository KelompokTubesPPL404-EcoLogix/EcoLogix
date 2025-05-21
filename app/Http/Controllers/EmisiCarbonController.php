<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\FaktorEmisi;
use App\Models\EmisiCarbon;

class EmisiCarbonController extends Controller
{
    private function konversiEmisi($kategori, $subKategori, $nilaiAktivitas)
    {
        $faktorEmisi = FaktorEmisi::where('kategori_emisi_karbon', $kategori)
            ->where('sub_kategori', $subKategori)
            ->first();

        if ($faktorEmisi) {
            $hasil = $nilaiAktivitas * $faktorEmisi->nilai_faktor;

            return [
                'nilai_aktivitas' => $nilaiAktivitas,
                'faktor_emisi' => $faktorEmisi->nilai_faktor,
                'hasil_konversi' => $hasil,
                'satuan_aktivitas' => $faktorEmisi->satuan,
                'satuan_emisi' => 'kg CO₂e'
            ];
        }

        return [
            'nilai_aktivitas' => $nilaiAktivitas,
            'faktor_emisi' => 0,
            'hasil_konversi' => 0,
            'satuan_aktivitas' => '',
            'satuan_emisi' => 'kg CO₂e'
        ];
    }

    public function index()
    {
        $kodeUser = 'DUMMY-STAFF'; // sementara

        $emisiCarbons = EmisiCarbon::with('faktorEmisi')
            ->where('kode_staff', $kodeUser)
            ->orderByDesc('tanggal_emisi')
            ->limit(10)
            ->get();

        foreach ($emisiCarbons as $emisi) {
            $emisi->konversi = $this->konversiEmisi(
                $emisi->kategori_emisi_karbon,
                $emisi->sub_kategori,
                $emisi->nilai_aktivitas
            );
        }

        return response()->json(['data' => $emisiCarbons]);
        // return view('emisicarbon.index', compact('emisiCarbons'));
    }

    public function create()
    {
        $faktorEmisis = FaktorEmisi::all();
        $kategoriEmisi = $faktorEmisis->groupBy('kategori_emisi_karbon');

        return response()->json(['data' => $kategoriEmisi]);
        // return view('emisicarbon.create', compact('kategoriEmisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_emisi' => 'required|date',
            'kategori_emisi_karbon' => 'required|string',
            'sub_kategori' => 'required|string',
            'nilai_aktivitas' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'kode_staff' => 'required|string'
        ]);

        $kodeEmisi = 'EMC-' . Str::random(6);

        $faktorEmisi = FaktorEmisi::where('kategori_emisi_karbon', $request->kategori_emisi_karbon)
            ->where('sub_kategori', $request->sub_kategori)
            ->first();

        if (!$faktorEmisi) {
            return response()->json(['error' => 'Faktor emisi tidak ditemukan'], 400);
        }

        EmisiCarbon::create([
            'kode_emisi_carbon' => $kodeEmisi,
            'kategori_emisi_karbon' => $request->kategori_emisi_karbon,
            'sub_kategori' => $request->sub_kategori,
            'nilai_aktivitas' => $request->nilai_aktivitas,
            'faktor_emisi' => $faktorEmisi->nilai_faktor,
            'kode_faktor_emisi' => $faktorEmisi->kode_faktor,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending',
            'kode_staff' => $request->kode_staff,
            'tanggal_emisi' => $request->tanggal_emisi,
        ]);

        return response()->json(['success' => 'Data emisi karbon berhasil disimpan dan menunggu persetujuan'], 201);
        // return redirect()->route('emisicarbon.index')
        //     ->with('success', 'Data emisi karbon berhasil disimpan dan menunggu persetujuan');
    }

    public function show($id)
    {
        $emisi = EmisiCarbon::where('kode_emisi_carbon', $id)->first();

        if (!$emisi) {
            return response()->json(['error' => 'Emisi Carbon not found'], 404);
        }

        return response()->json(['data' => $emisi], 200);
    }

    public function edit($kode_emisi_carbon)
    {
        $kodeUser = 'DUMMY-STAFF'; // sementara

        $emisiCarbon = EmisiCarbon::where('kode_emisi_carbon', $kode_emisi_carbon)
            ->where('kode_staff', $kodeUser)
            ->firstOrFail();

        $faktorEmisis = FaktorEmisi::all();
        $kategoriEmisi = $faktorEmisis->groupBy('kategori_emisi_karbon');

        return response()->json(compact('emisiCarbon', 'kategoriEmisi'));
        // return view('emisicarbon.edit', compact('emisiCarbon', 'kategoriEmisi'));
    }

    public function update(Request $request, $kode_emisi_carbon)
    {
        $request->validate([
            'tanggal_emisi' => 'required|date',
            'kategori_emisi_karbon' => 'required|string',
            'sub_kategori' => 'required|string',
            'nilai_aktivitas' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'kode_staff' => 'required|string'
        ]);

        $faktorEmisi = FaktorEmisi::where('kategori_emisi_karbon', $request->kategori_emisi_karbon)
            ->where('sub_kategori', $request->sub_kategori)
            ->first();

        if (!$faktorEmisi) {
            return response()->json(['error' => 'Faktor emisi tidak ditemukan'], 400);
        }

        $emisiCarbon = EmisiCarbon::where('kode_emisi_carbon', $kode_emisi_carbon)
            ->where('kode_staff', $request->kode_staff)
            ->firstOrFail();

        $emisiCarbon->update([
            'tanggal_emisi' => $request->tanggal_emisi,
            'kategori_emisi_karbon' => $request->kategori_emisi_karbon,
            'sub_kategori' => $request->sub_kategori,
            'nilai_aktivitas' => $request->nilai_aktivitas,
            'faktor_emisi' => $faktorEmisi->nilai_faktor,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending',
        ]);

        return response()->json($emisiCarbon, 200);
        // return redirect()->route('emisicarbon.index')
        //     ->with('success', 'Data emisi karbon berhasil diperbarui.');
    }

    public function destroy($kode_emisi_carbon)
    {
        $emisiCarbon = EmisiCarbon::findOrFail($kode_emisi_carbon);
        $emisiCarbon->delete();
        return response()->json([
            'message' => 'Faktor Emisi berhasil dihapus!',
        ], 200);
        //return redirect()->route('faktor-emisi.index')
        //    ->with('success', 'Faktor Emisi berhasil dihapus!');
    }

    public function editStatus($kode_emisi_carbon)
    {
        $emisiCarbon = EmisiCarbon::with('faktorEmisi')
            ->where('kode_emisi_carbon', $kode_emisi_carbon)
            ->firstOrFail();

        return response()->json(compact('emisiCarbon'));
        // return view('emisicarbon.edit_status', compact('emisiCarbon'));
    }

    public function updateStatus(Request $request, $kode_emisi_carbon)
    {
        $request->validate([
            'status' => 'required|in:approved,pending,rejected',
        ]);

        $kodeAdmin = 'DUMMY-ADMIN'; // sementara

        $emisiCarbon = EmisiCarbon::where('kode_emisi_carbon', $kode_emisi_carbon)
            ->firstOrFail();

        $emisiCarbon->update([
            'status' => $request->status,
            'kode_admin' => $kodeAdmin,
        ]);

        return response()->json(['success' => 'Status emisi karbon berhasil diperbarui.']);
        // return redirect()->route('admin.emissions.index')
        //     ->with('success', 'Status emisi karbon berhasil diperbarui.');
    }
}
