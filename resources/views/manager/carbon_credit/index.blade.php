@extends('layouts.manager')

@section('title', 'Ringkasan Pembelian Carbon Credit')

@section('styles')
<style>
  .card {
    border-radius: 8px;
    transition: all 0.3s ease;
  }
  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }
  .bg-gradient-success {
    background: linear-gradient(90deg, #28a745, #218838);
  }
  .table-hover tbody tr:hover {
    background-color: #f5f5f5;
  }
  .btn i {
    font-size: 0.875rem;
  }
</style>
@endsection

@section('content')
<div class="container py-5">
  <div class="row">
    <div class="col-md-12">
      <!-- Summary Cards -->
      <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-gradient-success text-white">
          <h5 class="mb-0 fw-bold">Ringkasan Pembelian Carbon Credit</h5>
        </div>
        <div class="card-body">
          <div class="row text-center">
            <div class="col-md-3">
              <div class="card border-primary">
                <div class="card-body">
                  <h6 class="text-primary mb-3">Total Pembelian</h6>
                  <p class="h3 text-primary" id="totalPembelian">0</p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card border-success">
                <div class="card-body">
                  <h6 class="text-success mb-3">Total Kompensasi</h6>
                  <p class="h3 text-success" id="totalKompensasi">0</p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card border-info">
                <div class="card-body">
                  <h6 class="text-info mb-3">Kompensasi Selesai</h6>
                  <p class="h3 text-info" id="completed">0</p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card border-warning">
                <div class="card-body">
                  <h6 class="text-warning mb-3">Kompensasi Pending</h6>
                  <p class="h3 text-warning" id="pending">0</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Table -->
      <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold">Daftar Pembelian Carbon Credit</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
              <thead class="table-success">
                <tr>
                  <th>Kode</th>
                  <th>Kategori</th>
                  <th>Sub Kategori</th>
                  <th>Jumlah</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="creditTableBody">
                <!-- rows populated via JS -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Modals container -->
      <div id="modalsContainer"></div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  const data = [
    {
      kode_pembelian: 'CC001',
      kode_kompensasi: 'KM001',
      kategori: 'Energi',
      sub_kategori: 'Listrik',
      jumlah_kompensasi: 12.345,
      tanggal_pembelian: '2025-04-20',
      status_kompensasi: 'completed',
      deskripsi: 'Pembelian kompensasi untuk konsumsi listrik bulanan.',
      bukti_pembelian: 'https://example.com/bukti1.pdf'
    },
    {
      kode_pembelian: 'CC002',
      kode_kompensasi: 'KM002',
      kategori: 'Transportasi',
      sub_kategori: 'Mobil',
      jumlah_kompensasi: 8.2,
      tanggal_pembelian: '2025-04-18',
      status_kompensasi: 'pending',
      deskripsi: '',
      bukti_pembelian: null
    }
  ];

  const tableBody = document.getElementById('creditTableBody');
  const modalsContainer = document.getElementById('modalsContainer');

  let totalKompensasi = 0;
  let completed = 0;
  let pending = 0;

  data.forEach((credit, i) => {
    totalKompensasi += credit.jumlah_kompensasi;
    if (credit.status_kompensasi === 'completed') completed++;
    else pending++;

    tableBody.innerHTML += `
      <tr>
        <td>${credit.kode_pembelian}</td>
        <td>${credit.kategori}</td>
        <td>${credit.sub_kategori}</td>
        <td class="text-end">${credit.jumlah_kompensasi.toFixed(1)}</td>
        <td>${new Date(credit.tanggal_pembelian).toLocaleDateString('id-ID')}</td>
        <td class="text-center">
          <span class="badge bg-${credit.status_kompensasi === 'completed' ? 'success' : 'warning'}">
            ${credit.status_kompensasi}
          </span>
        </td>
        <td class="text-center">
          <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal${i}">
            <i class="bi bi-eye"></i>
          </button>
        </td>
      </tr>
    `;

    modalsContainer.innerHTML += `
      <div class="modal fade" id="modal${i}" tabindex="-1">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white">
              <h5 class="modal-title fw-bold">Detail Pembelian</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row">
              <div class="col-md-6">
                <p><strong>Kode Pembelian:</strong><br>${credit.kode_pembelian}</p>
                <p><strong>Kode Kompensasi:</strong><br>${credit.kode_kompensasi}</p>
                <p><strong>Kategori:</strong><br>${credit.kategori}</p>
                <p><strong>Sub Kategori:</strong><br>${credit.sub_kategori}</p>
              </div>
              <div class="col-md-6">
                <p><strong>Jumlah Kompensasi:</strong><br>${credit.jumlah_kompensasi.toFixed(3)} ton</p>
                <p><strong>Tanggal Pembelian:</strong><br>${new Date(credit.tanggal_pembelian).toLocaleDateString('id-ID')}</p>
                <p><strong>Status:</strong> 
                  <span class="badge bg-${credit.status_kompensasi === 'completed' ? 'success' : 'warning'}">${credit.status_kompensasi}</span>
                </p>
              </div>
              <div class="col-12 mt-3">
                <p><strong>Deskripsi:</strong><br>${credit.deskripsi || 'Tidak ada deskripsi'}</p>
                ${credit.bukti_pembelian ? `<a href="${credit.bukti_pembelian}" target="_blank" class="btn btn-success"><i class="bi bi-file-download"></i> Bukti Pembelian</a>` : ''}
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
      </div>
    `;
  });

  document.getElementById('totalPembelian').textContent = data.length;
  document.getElementById('totalKompensasi').textContent = totalKompensasi.toFixed(1);
  document.getElementById('completed').textContent = completed;
  document.getElementById('pending').textContent = pending;
</script>
@endsection