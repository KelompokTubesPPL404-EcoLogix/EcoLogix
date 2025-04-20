@extends('layouts.app')

@section('title', 'Detail Perusahaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Perusahaan</h3>
        <div class="float-end">
            <a href="{{ route('perusahaan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3"><strong>Kode Perusahaan</strong></div>
            <div class="col-md-9">{{ $perusahaan->kode_perusahaan }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3"><strong>Nama Perusahaan</strong></div>
            <div class="col-md-9">{{ $perusahaan->nama_perusahaan }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3"><strong>Alamat</strong></div>
            <div class="col-md-9">{{ $perusahaan->alamat_perusahaan }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3"><strong>No. Telepon</strong></div>
            <div class="col-md-9">{{ $perusahaan->no_telp_perusahaan }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3"><strong>Email</strong></div>
            <div class="col-md-9">{{ $perusahaan->email_perusahaan }}</div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('perusahaan.edit', $perusahaan->kode_perusahaan) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('perusahaan.destroy', $perusahaan->kode_perusahaan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perusahaan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection