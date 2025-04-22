@extends('layouts.app')

@section('title', 'Dashboard Perusahaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Dashboard Perusahaan</h3>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">Informasi Perusahaan</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $perusahaan->nama_perusahaan }}</h5>
                        <p class="card-text"><strong>Kode:</strong> {{ $perusahaan->kode_perusahaan }}</p>
                        <p class="card-text"><strong>Alamat:</strong> {{ $perusahaan->alamat_perusahaan }}</p>
                        <p class="card-text"><strong>Email:</strong> {{ $perusahaan->email_perusahaan }}</p>
                        <p class="card-text"><strong>No. Telepon:</strong> {{ $perusahaan->no_telp_perusahaan }}</p>
                        <a href="{{ route('perusahaan.profile') }}" class="btn btn-primary">Lihat Profil</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Menu</div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="{{ route('perusahaan.profile') }}" class="list-group-item list-group-item-action">Profil Perusahaan</a>
                            <!-- Tambahkan menu lain sesuai kebutuhan -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection