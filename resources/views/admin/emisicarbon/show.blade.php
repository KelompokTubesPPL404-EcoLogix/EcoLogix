@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Emisi Karbon</span>
                    <a href="{{ route('admin.emisicarbon.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="30%">Kode Emisi Karbon</th>
                                    <td>{{ $emisiCarbon->kode_emisi_karbon }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $emisiCarbon->kategori }}</td>
                                </tr>
                                <tr>
                                    <th>Sumber Emisi</th>
                                    <td>{{ $emisiCarbon->sumber_emisi }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Konsumsi</th>
                                    <td>{{ number_format($emisiCarbon->jumlah_konsumsi, 2) }} {{ $emisiCarbon->satuan }}</td>
                                </tr>
                                <tr>
                                    <th>Faktor Emisi</th>
                                    <td>{{ $emisiCarbon->faktorEmisi->nilai_faktor }} {{ $emisiCarbon->faktorEmisi->satuan }}</td>
                                </tr>
                                <tr>
                                    <th>Total Emisi</th>
                                    <td>{{ number_format($emisiCarbon->total_emisi, 2) }} ton CO<sub>2</sub>e</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Emisi</th>
                                    <td>{{ $emisiCarbon->tanggal_emisi->format('d-m-Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($emisiCarbon->status == 'draft')
                                            <span class="badge badge-secondary">Draft</span>
                                        @elseif($emisiCarbon->status == 'submitted')
                                            <span class="badge badge-primary">Submitted</span>
                                        @elseif($emisiCarbon->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($emisiCarbon->status == 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Catatan</th>
                                    <td>{{ $emisiCarbon->catatan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat Oleh</th>
                                    <td>{{ $emisiCarbon->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td>{{ $emisiCarbon->created_at->format('d-m-Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Terakhir Diperbarui</th>
                                    <td>{{ $emisiCarbon->updated_at->format('d-m-Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        @if($emisiCarbon->status == 'submitted')
                            <a href="{{ route('admin.emisicarbon.editStatus', $emisiCarbon->kode_emisi_karbon) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Ubah Status
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection