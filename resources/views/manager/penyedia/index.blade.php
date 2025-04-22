@extends('layouts.manager')

@section('title', 'Manajemen Penyedia Carbon Credit')

@section('styles')
<style>
  .btn-group .btn { padding: 0.5rem 0.75rem; font-size: 0.875rem; }
  .btn-group .btn i { font-size: 0.875rem; }
  table th, table td { vertical-align: middle; }
  .table-hover tbody tr:hover { background-color: #f5f5f5; }
  .btn-light { background: #ffffff; border: 2px solid #ffffff; transition: all 0.2s ease; }
  .btn-light:hover { background: #f8f9fa; transform: translateY(-2px); }
  .bg-gradient-success { background: linear-gradient(90deg, #28a745, #218838); }
  .bg-gradient-danger { background: linear-gradient(90deg, #dc3545, #c82333); }
  .btn-warning { color: #fff; background-color: #ffc107; border-color: #ffc107; }
  .btn-warning:hover { color: #fff; background-color: #e0a800; border-color: #d39e00; }
  .btn-sm { margin: 0 2px; }
  .modal-content { border-radius: 15px; border: none; }
  .modal-header { border-radius: 15px 15px 0 0; }
  .form-label { font-size: 1rem; font-weight: 600; color: #495057; }
  .form-control { border-radius: 8px; padding: 0.6rem 1rem; }
  .form-control:focus { border-color: #28a745; box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25); }
  .btn-close-white { filter: brightness(0) invert(1); }
</style>
@endsection

@section('content')
<div class="container py-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold">Manajemen Penyedia Carbon Credit</h5>
          <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-circle"></i> Tambah Penyedia
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
              <thead class="table-success">
                <tr>
                  <th class="text-center">No</th>
                  <th>Kode</th>
                  <th>Nama Penyedia</th>
                  <th class="text-center">Harga/ton</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody id="penyediaTableBody">
                <tr>
                  <td class="text-center">1</td>
                  <td>CRB001</td>
                  <td>Carbon Indo</td>
                  <td class="text-center">150.000 IDR</td>
                  <td class="text-center"><span class="badge bg-success">Aktif</span></td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal">
                      <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                      <i class="bi bi-trash"></i> Hapus
                    </button>
                  </td>
                </tr>
                <!-- Data tambahan lainnya bisa ditambahkan di sini -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success text-white">
        <h5 class="modal-title fw-bold">Tambah Penyedia Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-4">
          <label class="form-label">Nama Penyedia</label>
          <input type="text" class="form-control" required>
        </div>
        <div class="mb-4">
          <label class="form-label">Deskripsi</label>
          <textarea class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-4">
          <label class="form-label">Harga per ton CO₂</label>
          <input type="number" class="form-control" step="0.01" required>
        </div>
        <div class="mb-4">
          <label class="form-label">Mata Uang</label>
          <select class="form-select" required>
            <option value="IDR">IDR</option>
            <option value="USD">USD</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success px-4">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit dan Delete hanya contoh -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success text-white">
        <h5 class="modal-title fw-bold">Edit Penyedia</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <!-- Konten form edit -->
        <p>Edit form di sini</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success px-4">Update</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-gradient-danger text-white">
        <h5 class="modal-title fw-bold">Hapus Penyedia</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <p class="mb-0">Apakah Anda yakin ingin menghapus penyedia carbon credit ini?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger px-4">Hapus</button>
      </div>
    </div>
  </div>
</div>
@endsection