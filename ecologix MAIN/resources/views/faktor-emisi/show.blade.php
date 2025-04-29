@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Faktor Emisi</h5>
                    <a href="{{ route('faktor-emisi.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Kode Faktor</th>
                                <td>{{ $faktorEmisi->kode_faktor }}</td>
                            </tr>
                            <tr>
                                <th>Kategori Emisi Karbon</th>
                                <td>{{ $faktorEmisi->kategori_emisi_karbon }}</td>
                            </tr>
                            <tr>
                                <th>Sub Kategori</th>
                                <td>{{ $faktorEmisi->sub_kategori }}</td>
                            </tr>
                            <tr>
                                <th>Nilai Faktor</th>
                                <td>{{ $faktorEmisi->nilai_faktor }}</td>
                            </tr>
                            <tr>
                                <th>Satuan</th>
                                <td>{{ $faktorEmisi->satuan }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="mt-3 d-flex">
                        <a href="{{ route('faktor-emisi.edit', $faktorEmisi->kode_faktor) }}" class="btn btn-warning me-2">Edit</a>
                        <form action="{{ route('faktor-emisi.destroy', $faktorEmisi->kode_faktor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection