<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Kompensasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Kompensasi</h5>
                        <a href="/kompensasi" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Informasi Kompensasi -->
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2">Informasi Kompensasi</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="200">Kode Kompensasi</td>
                                        <td>: KMP001</td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Kompensasi</td>
                                        <td>: 5.00 ton CO₂e</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Kompensasi</td>
                                        <td>: 22/04/2025</td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>: <span class="badge bg-warning">Pending</span></td>
                                    </tr>
                                    <tr>
                                        <td>Created At</td>
                                        <td>: 21/04/2025 10:15:30</td>
                                    </tr>
                                    <tr>
                                        <td>Updated At</td>
                                        <td>: 22/04/2025 08:12:45</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Informasi Emisi Karbon -->
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2">Informasi Emisi Karbon</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="200">Kode Emisi</td>
                                        <td>: EM001</td>
                                    </tr>
                                    <tr>
                                        <td>Kategori</td>
                                        <td>: Transportasi</td>
                                    </tr>
                                    <tr>
                                        <td>Sub Kategori</td>
                                        <td>: Mobil</td>
                                    </tr>
                                    <tr>
                                        <td>Kadar Emisi</td>
                                        <td>: 8.75 ton CO₂e</td>
                                    </tr>
                                    <tr>
                                        <td>Nilai Aktivitas</td>
                                        <td>: 120</td>
                                    </tr>
                                    <tr>
                                        <td>Faktor Emisi</td>
                                        <td>: 0.073</td>
                                    </tr>
                                    <tr>
                                        <td>Deskripsi</td>
                                        <td>: Perjalanan dinas menggunakan kendaraan pribadi selama bulan April</td>
                                    </tr>
                                </table>
                            </div>
                        </div> <!-- row -->
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col -->
        </div> <!-- row -->
    </div> <!-- container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>