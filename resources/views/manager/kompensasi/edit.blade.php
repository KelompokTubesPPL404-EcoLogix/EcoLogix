@extends('layouts.manager')

@section('title', 'Edit Kompensasi')

@section('content')
<div class="container py-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Edit Kompensasi</h5>
          <a href="{{ route('manager.kompensasi.index') }}" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
          </a>
        </div>
        <div class="card-body">
          <form id="formEditKompensasi">
            <div class="row">
              <!-- Informasi Emisi -->
              <div class="col-md-6">
                <h6 class="border-bottom pb-2">Informasi Emisi</h6>
                <table class="table table-borderless">
                  <tr>
                    <td width="200">Kode Emisi</td>
                    <td>: <span id="kodeEmisi">EM123456</span></td>
                  </tr>
                  <tr>
                    <td>Kategori</td>
                    <td>: <span id="kategori">Transportasi</span></td>
                  </tr>
                  <tr>
                    <td>Sub Kategori</td>
                    <td>: <span id="subKategori">Darat</span></td>
                  </tr>
                  <tr>
                    <td>Kadar Emisi</td>
                    <td>: <span id="kadarEmisi">5.25 ton CO₂e</span></td>
                  </tr>
                </table>
              </div>

              <!-- Form Edit -->
              <div class="col-md-6">
                <h6 class="border-bottom pb-2">Edit Kompensasi</h6>
                <div class="mb-3">
                  <label for="jumlahKompensasi" class="form-label">Jumlah Kompensasi (ton CO₂e)</label>
                  <input type="number" class="form-control" id="jumlahKompensasi" name="jumlahKompensasi" step="0.01" min="0.01" value="2.00" required />
                  <div class="invalid-feedback">Jumlah kompensasi tidak boleh kosong.</div>
                </div>
                <button type="submit" class="btn btn-primary">Update Kompensasi</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.getElementById("formEditKompensasi").addEventListener("submit", function (e) {
    e.preventDefault();
    const jumlah = document.getElementById("jumlahKompensasi").value;
    if (!jumlah || jumlah <= 0) {
      document.getElementById("jumlahKompensasi").classList.add("is-invalid");
      return;
    }
    alert("Kompensasi berhasil diperbarui dengan nilai: " + jumlah + " ton CO₂e");
    // Di sini kamu bisa kirim data ke backend via fetch/AJAX jika pakai API
  });
</script>
@endsection