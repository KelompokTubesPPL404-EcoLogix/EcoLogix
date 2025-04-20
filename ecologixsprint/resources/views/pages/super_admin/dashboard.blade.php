@extends('layouts.super_admin')

@section('content')
<div class="container-fluid">
    <main class="px-md-4">
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 text-success">Dashboard Super Admin</h1>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card h-100 border-success">
                    <div class="card-body">
                        <h5 class="card-title text-success">Total SUPER Admin</h5>
                        <h2 class="card-text">{{ $totalUsers }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100 border-success">
                    <div class="card-body">
                        <h5 class="card-title text-success">Total Emisi (Ton)</h5>
                        <h2 class="card-text">{{ number_format($totalEmissionsApprovedTon, 2) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100 border-success">
                    <div class="card-body">
                        <h5 class="card-title text-success">Pengajuan Pending</h5>
                        <h2 class="card-text">{{ $pendingEmissions }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100 border-success">
                    <div class="card-body">
                        <h5 class="card-title text-success">Rata-rata Emisi/User</h5>
                        <h2 class="card-text">{{ number_format($averageEmissionPerUser, 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Emissions Table -->
        <!-- <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Pengajuan Emisi Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Kategori</th>
                                <th>Sub Kategori</th>
                                <th>Kadar Emisi</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentEmissions as $emission)
                            <tr>
                                <td>{{ $emission->nama_user }}</td>
                                <td>{{ $emission->kategori_emisi_karbon }}</td>
                                <td>{{ $emission->sub_kategori }}</td>
                                <td>{{ number_format($emission->kadar_emisi_karbon, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $emission->status == 'approved' ? 'success' : ($emission->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($emission->status) }}
                                    </span>
                                </td>
                                <td>{{ date('d/m/Y', strtotime($emission->created_at)) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> -->
    </main>
</div>
@endsection
