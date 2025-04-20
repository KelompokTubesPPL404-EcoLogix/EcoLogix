@extends('layouts.app')

@section('title', 'Profil Perusahaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Profil Perusahaan</h3>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header">Informasi Perusahaan</div>
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
                            <a href="{{ route('perusahaan.edit', $perusahaan->kode_perusahaan) }}" class="btn btn-primary">Edit Profil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection