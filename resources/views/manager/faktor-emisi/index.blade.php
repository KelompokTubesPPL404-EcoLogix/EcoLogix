@extends('layouts.manager')

@section('title', 'Faktor Emisi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Faktor Emisi</h1>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahFaktorEmisiModal">
            <i class="bi bi-plus-circle me-1"></i> Tambah Faktor Emisi
        </button>
    </div>

    <!-- Tabel Faktor Emisi -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">Daftar Faktor Emisi</h6>
            <div class="input-group w-25">
                <input type="text" class="form-control" placeholder="Cari faktor emisi...">
                <button class="btn btn-success" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kategori</th>
                            <th>Nama Kegiatan</th>
                            <th>Nilai Faktor Emisi</th>
                            <th>Satuan</th>
                            <th>Sumber Data</th>
                            <th>Terakhir Diperbarui</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Transportasi</td>
                            <td>Penggunaan Bensin RON 90</td>
                            <td>2.31</td>
                            <td>kg CO<sub>2</sub>e/liter</td>
                            <td>IPCC 2019</td>
                            <td>12 Jan 2023</td>
                            <td>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Transportasi</td>
                            <td>Penggunaan Diesel</td>
                            <td>2.68</td>
                            <td>kg CO<sub>2</sub>e/liter</td>
                            <td>IPCC 2019</td>
                            <td>12 Jan 2023</td>
                            <td>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Energi</td>
                            <td>Penggunaan Listrik PLN</td>
                            <td>0.87</td>
                            <td>kg CO<sub>2</sub>e/kWh</td>
                            <td>DJPPI 2021</td>
                            <td>15 Feb 2023</td>
                            <td>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Pengolahan Limbah</td>
                            <td>Pembuangan Sampah ke TPA</td>
                            <td>0.58</td>
                            <td>kg CO<sub>2</sub>e/kg</td>
                            <td>IPCC 2019</td>
                            <td>20 Mar 2023</td>
                            <td>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Produksi</td>
                            <td>Penggunaan Gas LPG</td>
                            <td>1.61</td>
                            <td>kg CO<sub>2</sub>e/kg</td>
                            <td>IPCC 2019</td>
                            <td>5 Apr 2023</td>
                            <td>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal Tambah Faktor Emisi -->
<div class="modal fade" id="tambahFaktorEmisiModal" tabindex="-1" aria-labelledby="tambahFaktorEmisiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahFaktorEmisiModalLabel">Tambah Faktor Emisi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori" required>
                                <option value="" selected disabled>Pilih Kategori</option>
                                <option value="transportasi">Transportasi</option>
                                <option value="energi">Energi</option>
                                <option value="limbah">Pengolahan Limbah</option>
                                <option value="produksi">Produksi</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="namaKegiatan" class="form-label">Nama Kegiatan</label>
                            <input type="text" class="form-control" id="namaKegiatan" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nilaiFaktorEmisi" class="form-label">Nilai Faktor Emisi</label>
                            <input type="number" step="0.01" class="form-control" id="nilaiFaktorEmisi" required>
                        </div>
                        <div class="col-md-6">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control" id="satuan" placeholder="kg CO2e/unit" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="sumberData" class="form-label">Sumber Data</label>
                        <input type="text" class="form-control" id="sumberData" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi (opsional)</label>
                        <textarea class="form-control" id="deskripsi" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="metodologi" class="form-label">Metodologi Perhitungan (opsional)</label>
                        <textarea class="form-control" id="metodologi" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection