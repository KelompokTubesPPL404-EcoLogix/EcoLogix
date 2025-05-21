@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Penyedia Carbon Credit</span>
                    <a href="{{ route('manager.penyedia-carbon-credit.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="30%">Kode Penyedia</th>
                                    <td>{{ $penyedia->kode_penyedia }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Penyedia</th>
                                    <td>{{ $penyedia->nama_penyedia }}</td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>{{ $penyedia->deskripsi }}</td>
                                </tr>
                                <tr>
                                    <th>Harga per Ton</th>
                                    <td>{{ number_format($penyedia->harga_per_ton, 2) }} {{ $penyedia->mata_uang }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($penyedia->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td>{{ $penyedia->created_at->format('d-m-Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Terakhir Diperbarui</th>
                                    <td>{{ $penyedia->updated_at->format('d-m-Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 d-flex">
                        <a href="{{ route('manager.penyedia-carbon-credit.edit', $penyedia->kode_penyedia) }}" class="btn btn-warning mr-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('manager.penyedia-carbon-credit.destroy', $penyedia->kode_penyedia) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penyedia ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection