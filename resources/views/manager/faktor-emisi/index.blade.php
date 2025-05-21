@extends('layouts.manager')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Faktor Emisi</h5>
                    <a href="{{ route('manager.faktor-emisi.create') }}" class="btn btn-primary">Tambah Faktor Emisi</a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kode Faktor</th>
                                    <th>Kategori Emisi Karbon</th>
                                    <th>Sub Kategori</th>
                                    <th>Nilai Faktor</th>
                                    <th>Satuan</th>
                                    <th width="200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($faktorEmisi as $item)
                                <tr>
                                    <td>{{ $item->kode_faktor }}</td>
                                    <td>{{ $item->kategori_emisi_karbon }}</td>
                                    <td>{{ $item->sub_kategori }}</td>
                                    <td>{{ $item->nilai_faktor }}</td>
                                    <td>{{ $item->satuan }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('manager.faktor-emisi.show', $item->kode_faktor) }}" class="btn btn-info btn-sm me-1">Detail</a>
                                            <a href="{{ route('manager.faktor-emisi.edit', $item->kode_faktor) }}" class="btn btn-warning btn-sm me-1">Edit</a>
                                            <form action="{{ route('manager.faktor-emisi.destroy', $item->kode_faktor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data faktor emisi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection