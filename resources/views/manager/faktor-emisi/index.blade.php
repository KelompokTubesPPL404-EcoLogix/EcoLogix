<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manajemen Faktor Emisi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    .bg-gradient-success {
      background: linear-gradient(90deg, #28a745, #218838);
    }
    .bg-gradient-danger {
      background: linear-gradient(90deg, #dc3545, #c82333);
    }
    .btn-light {
      background: #ffffff;
      border: 2px solid #ffffff;
      transition: all 0.2s ease;
    }
    .btn-light:hover {
      background: #f8f9fa;
      transform: translateY(-2px);
    }
    .btn-warning {
      color: #fff;
      background-color: #ffc107;
      border-color: #ffc107;
    }
    .btn-warning:hover {
      background-color: #e0a800;
    }
    .modal-content {
      border-radius: 15px;
      border: none;
    }
    .modal-header {
      border-radius: 15px 15px 0 0;
    }
    .form-control:focus {
      border-color: #28a745;
      box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="card shadow-lg border-0">
      <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Manajemen Faktor Emisi</h5>
        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createFaktorEmisi">
          <i class="bi bi-plus-circle"></i> Tambah Faktor Emisi
        </button>
      </div>
      <div class="card-body">
        <div id="alert-placeholder"></div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-success">
              <tr>
                <th class="text-center">No</th>
                <th>Kategori</th>
                <th>Sub Kategori</th>
                <th class="text-center">Nilai Faktor</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody id="faktorTableBody"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah -->
  <div class="modal fade" id="createFaktorEmisi" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-gradient-success text-white">
          <h5 class="modal-title fw-bold">Tambah Faktor Emisi</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <form id="createForm">
            <div class="mb-4">
              <label class="form-label">Kategori Emisi</label>
              <input type="text" class="form-control" name="kategori" required />
            </div>
            <div class="mb-4">
              <label class="form-label">Sub Kategori</label>
              <input type="text" class="form-control" name="subKategori" required />
            </div>
            <div class="mb-4">
              <label class="form-label">Nilai Faktor</label>
              <input type="number" step="0.0001" class="form-control" name="nilai" required />
            </div>
            <div class="mb-4">
              <label class="form-label">Satuan</label>
              <input type="text" class="form-control" name="satuan" required />
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-success px-4">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Edit -->
  <div class="modal fade" id="editFaktorEmisi" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-gradient-success text-white">
          <h5 class="modal-title fw-bold">Edit Faktor Emisi</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <form id="editForm">
            <input type="hidden" name="id" />
            <div class="mb-3">
              <label class="form-label">Kategori Emisi</label>
              <input type="text" class="form-control" name="kategori" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Sub Kategori</label>
              <input type="text" class="form-control" name="subKategori" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Nilai Faktor</label>
              <input type="number" step="0.0001" class="form-control" name="nilai" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Satuan</label>
              <input type="text" class="form-control" name="satuan" required />
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-success px-4">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Delete -->
  <div class="modal fade" id="deleteFaktorEmisi" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-gradient-danger text-white">
          <h5 class="modal-title fw-bold">Hapus Faktor Emisi</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <p class="mb-0">Apakah Anda yakin ingin menghapus faktor emisi ini?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-danger px-4" id="confirmDelete">Hapus</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let data = [
      { id: 1, kategori: "Transportasi", subKategori: "Mobil", nilai: 0.25, satuan: "kg CO₂/km" },
      { id: 2, kategori: "Listrik", subKategori: "PLN", nilai: 0.68, satuan: "kg CO₂/kWh" }
    ];

    let editingId = null;

    const renderTable = () => {
      const tbody = document.getElementById("faktorTableBody");
      tbody.innerHTML = "";
      if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center">Tidak ada data faktor emisi</td></tr>`;
        return;
      }

      data.forEach((item, index) => {
        tbody.innerHTML += `
          <tr>
            <td class="text-center">${index + 1}</td>
            <td>${item.kategori}</td>
            <td>${item.subKategori}</td>
            <td class="text-center">${item.nilai}</td>
            <td class="text-center">${item.satuan}</td>
            <td class="text-center">
              <button class="btn btn-sm btn-warning" onclick="openEditModal(${item.id})"><i class="bi bi-pencil"></i> Edit</button>
              <button class="btn btn-sm btn-danger" onclick="openDeleteModal(${item.id})"><i class="bi bi-trash"></i> Hapus</button>
            </td>
          </tr>
        `;
      });
    };

    document.getElementById("createForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const form = new FormData(this);
      data.push({
        id: Date.now(),
        kategori: form.get("kategori"),
        subKategori: form.get("subKategori"),
        nilai: parseFloat(form.get("nilai")),
        satuan: form.get("satuan"),
      });
      renderTable();
      this.reset();
      bootstrap.Modal.getInstance(document.getElementById("createFaktorEmisi")).hide();
    });

    const openEditModal = (id) => {
      editingId = id;
      const item = data.find((d) => d.id === id);
      const form = document.getElementById("editForm");
      form.kategori.value = item.kategori;
      form.subKategori.value = item.subKategori;
      form.nilai.value = item.nilai;
      form.satuan.value = item.satuan;
      bootstrap.Modal.getOrCreateInstance(document.getElementById("editFaktorEmisi")).show();
    };

    document.getElementById("editForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const form = new FormData(this);
      const index = data.findIndex((d) => d.id === editingId);
      data[index] = {
        ...data[index],
        kategori: form.get("kategori"),
        subKategori: form.get("subKategori"),
        nilai: parseFloat(form.get("nilai")),
        satuan: form.get("satuan"),
      };
      renderTable();
      bootstrap.Modal.getInstance(document.getElementById("editFaktorEmisi")).hide();
    });

    let deleteId = null;
    const openDeleteModal = (id) => {
      deleteId = id;
      bootstrap.Modal.getOrCreateInstance(document.getElementById("deleteFaktorEmisi")).show();
    };

    document.getElementById("confirmDelete").addEventListener("click", function () {
      data = data.filter((d) => d.id !== deleteId);
      renderTable();
      bootstrap.Modal.getInstance(document.getElementById("deleteFaktorEmisi")).hide();
    });

    renderTable();
  </script>
</body>
</html>