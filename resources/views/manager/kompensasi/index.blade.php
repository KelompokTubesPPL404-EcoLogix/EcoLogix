@extends('layouts.manager')

@section('title', 'Ringkasan & Riwayat Kompensasi Emisi')

@section('content')
<div class="container py-5">
  <div class="row">
    <div class="col-md-12">

      <!-- Ringkasan Emisi -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0">Ringkasan Emisi Carbon</h5>
        </div>
        <div class="card-body">
          <div class="row text-center">
            <div class="col-md-4">
              <div class="border rounded p-3">
                <h6>Total Emisi (Approved)</h6>
                <h4 class="text-success mb-0">123.45 <small>ton CO₂e</small></h4>
              </div>
            </div>
            <div class="col-md-4">
              <div class="border rounded p-3">
                <h6>Total Terkompensasi</h6>
                <h4 class="text-primary mb-0">100.00 <small>ton CO₂e</small></h4>
              </div>
            </div>
            <div class="col-md-4">
              <div class="border rounded p-3">
                <h6>Sisa Emisi</h6>
                <h4 class="text-warning mb-0">23.45 <small>ton CO₂e</small></h4>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Detail Emisi per Kategori -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
          <h5 class="mb-0">Detail Emisi per Kategori</h5>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered">
            <thead class="table-warning">
              <tr>
                <th>Kategori</th>
                <th>Total Emisi</th>
                <th>Terkompensasi</th>
                <th>Sisa</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Transportasi</td>
                <td class="text-end">70.00 ton CO₂e</td>
                <td class="text-end">60.00 ton CO₂e</td>
                <td class="text-end">10.00 ton CO₂e</td>
              </tr>
              <tr>
                <td>Energi</td>
                <td class="text-end">53.45 ton CO₂e</td>
                <td class="text-end">40.00 ton CO₂e</td>
                <td class="text-end">13.45 ton CO₂e</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Form Kompensasi -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0">Form Kompensasi Emisi</h5>
        </div>
        <div class="card-body">
          <form id="formKompensasi">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Pilih Emisi</label>
                <select name="kode_emisi_karbon" class="form-select" required>
                  <option value="">Pilih Emisi</option>
                  <option value="EM001">Transportasi - Mobil (Sisa: 10.0000 ton CO₂e)</option>
                  <option value="EM002">Energi - Listrik (Sisa: 13.4500 ton CO₂e)</option>
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label>Jumlah Kompensasi</label>
                <div class="input-group">
                  <input type="number" name="jumlah_kompensasi" class="form-control" step="0.001" min="0.001" required>
                  <span class="input-group-text">ton CO₂e</span>
                </div>
              </div>
              <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">Kompensasi</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Riwayat Kompensasi -->
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Riwayat Kompensasi</h5>
          <div>
            <a href="{{ route('manager.kompensasi.report') }}" class="btn btn-light btn-sm me-2"><i class="bi bi-printer"></i> Print</a>
            <button type="button" class="btn btn-light btn-sm" onclick="toggleFilter()"><i class="bi bi-funnel"></i> Filter</button>
          </div>
        </div>
        <div class="card-body">

          <!-- Filter Form -->
          <form class="row g-3 mb-3" id="filterForm" style="display: none;">
            <div class="col-md-3">
              <label>Search</label>
              <input type="text" class="form-control" placeholder="Search kode/kategori...">
            </div>
            <div class="col-md-3">
              <label>Start Date</label>
              <input type="date" class="form-control">
            </div>
            <div class="col-md-3">
              <label>End Date</label>
              <input type="date" class="form-control">
            </div>
            <div class="col-md-2">
              <label>Status</label>
              <select class="form-select">
                <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
              </select>
            </div>
            <div class="col-md-2">
              <label>Kategori</label>
              <select class="form-select">
                <option value="">All</option>
                <option value="Transportasi">Transportasi</option>
                <option value="Energi">Energi</option>
              </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <button class="btn btn-primary me-2" type="submit">Apply</button>
              <button class="btn btn-secondary" type="reset">Reset</button>
            </div>
          </form>

          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
              <thead class="table-success">
                <tr>
                  <th>No</th>
                  <th>Kode Kompensasi</th>
                  <th>Kode Emisi</th>
                  <th>Kategori</th>
                  <th>Sub Kategori</th>
                  <th>Jumlah (ton)</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center">1</td>
                  <td>KMP001</td>
                  <td>EM001</td>
                  <td>Transportasi</td>
                  <td>Mobil</td>
                  <td class="text-end">5.0000</td>
                  <td class="text-center">21/04/2025</td>
                  <td class="text-center"><span class="badge bg-warning">Pending</span></td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="{{ route('manager.kompensasi.show', 'KMP001') }}" class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                      <a href="{{ route('manager.kompensasi.edit.', 'KMP001') }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                      <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data?')"><i class="bi bi-trash"></i></button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="text-center">2</td>
                  <td>KMP002</td>
                  <td>EM002</td>
                  <td>Energi</td>
                  <td>Listrik</td>
                  <td class="text-end">10.0000</td>
                  <td class="text-center">20/04/2025</td>
                  <td class="text-center"><span class="badge bg-success">Completed</span></td>
                  <td class="text-center">
                    <a href="{{ route('manager.kompensasi.show', 'KMP002') }}" class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                  </td>
                </tr>
                <tr>
                  <td class="text-center">3</td>
                  <td>KMP003</td>
                  <td>EM003</td>
                  <td>Transportasi</td>
                  <td>Motor</td>
                  <td class="text-end">2.5000</td>
                  <td class="text-center">19/04/2025</td>
                  <td class="text-center"><span class="badge bg-success">Completed</span></td>
                  <td class="text-center">
                    <a href="{{ route('manager.kompensasi.show', 'KMP003') }}" class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
              <li class="page-item"><a class="page-link" href="#">Previous</a></li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // Toggle filter panel
  function toggleFilter() {
    const filterForm = document.getElementById('filterForm');
    filterForm.style.display = filterForm.style.display === 'none' ? 'flex' : 'none';
  }

  // Submit form handler
  document.getElementById('formKompensasi').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const emisi = formData.get('kode_emisi_karbon');
    const jumlah = formData.get('jumlah_kompensasi');
    
    if (emisi && jumlah > 0) {
      // Handle submission - you would send an AJAX request here
      alert(`Kompensasi berhasil dibuat untuk ${emisi} dengan jumlah ${jumlah} ton CO₂e`);
      this.reset();
    } else {
      alert('Silakan lengkapi semua data dengan benar');
    }
  });
</script>
@endsection