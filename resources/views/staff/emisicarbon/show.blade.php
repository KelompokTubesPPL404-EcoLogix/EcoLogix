@extends('layouts.staff')

@section('content')
<div class="container">
    <h1>Detail Emisi Karbon</h1>

    @if(isset($emisiCarbon) && $emisiCarbon)
        <div class="card">
            <div class="card-header">
                Kode Emisi: {{ $emisiCarbon->kode_emisi_karbon }}
            </div>
            <div class="card-body">
                <h5 class="card-title">Tanggal Emisi: {{ $emisiCarbon->tanggal_emisi ? \Carbon\Carbon::parse($emisiCarbon->tanggal_emisi)->format('d M Y') : 'N/A' }}</h5>
                <p class="card-text"><strong>Kategori Emisi:</strong> {{ $emisiCarbon->kategori_emisi_karbon ?? 'N/A' }}</p>
                <p class="card-text"><strong>Sub Kategori:</strong> {{ $emisiCarbon->sub_kategori ?? 'N/A' }}</p>
                <p class="card-text"><strong>Deskripsi:</strong> {{ $emisiCarbon->deskripsi ?? 'N/A' }}</p>
                <p class="card-text"><strong>Faktor Emisi:</strong> {{ $emisiCarbon->faktorEmisi ? ($emisiCarbon->faktorEmisi->nama_kegiatan ?? $emisiCarbon->faktorEmisi->sub_kategori) : 'N/A' }} ({{ $emisiCarbon->faktorEmisi ? number_format($emisiCarbon->faktorEmisi->nilai_faktor, 2) : '' }} {{ $emisiCarbon->faktorEmisi ? $emisiCarbon->faktorEmisi->satuan : '' }})</p>
                <p class="card-text"><strong>Nilai Aktivitas:</strong> {{ number_format($emisiCarbon->nilai_aktivitas, 2) ?? 'N/A' }} {{ $emisiCarbon->faktorEmisi ? $emisiCarbon->faktorEmisi->satuan : '' }}</p>
                <p class="card-text"><strong>Total Emisi:</strong> {{ number_format($emisiCarbon->kadar_emisi_karbon, 2) ?? 'N/A' }} kg CO₂e</p>
                <p class="card-text"><strong>Status:</strong>
                    @if($emisiCarbon->status == 'approved')
                    <span class="badge bg-success text-white">Disetujui</span>
                    @elseif($emisiCarbon->status == 'rejected')
                    <span class="badge bg-danger text-white">Ditolak</span>
                    @else
                    <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                </p>

            </div>
            <div class="card-footer">
                <a href="{{ route('staff.emisicarbon.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
                @if($emisiCarbon->status != 'approved')
                    <a href="{{ route('staff.emisicarbon.edit', $emisiCarbon->kode_emisi_karbon) }}" class="btn btn-warning">Edit</a>
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Data emisi karbon tidak ditemukan.
        </div>
        <a href="{{ route('staff.emisicarbon.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
    @endif
</div>
@endsection