@extends('layouts.manager')

@section('title', 'Edit Pembelian Carbon Credit')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-pencil-square me-2"></i>Edit Pembelian Carbon Credit
            <small class="text-muted fs-6">{{ $pembelian->kode_pembelian_carbon_credit }}</small>
        </h1>
        <a href="{{ route('manager.pembelian-carbon-credit.index') }}" class="btn btn-outline-success shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pembelian
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Ada beberapa masalah dengan input Anda.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 bg-gradient-success-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-success">
                            <i class="bi bi-pencil-square me-2"></i>Form Edit Pembelian Carbon Credit
                        </h6>
                        <span class="badge bg-success">
                            <i class="bi bi-building-gear me-1"></i>{{ Auth::user()->perusahaan->kode_perusahaan }} - {{ Auth::user()->perusahaan->nama_perusahaan }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('manager.pembelian-carbon-credit.update', $pembelian->kode_pembelian_carbon_credit) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4 row">
                            <label for="kode_kompensasi" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-upc me-1"></i>Kode Kompensasi <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                    <select id="kode_kompensasi" class="form-select @error('kode_kompensasi') is-invalid @enderror" name="kode_kompensasi" required>
                                        @foreach($kompensasiList as $kompensasi)
                                            <option value="{{ $kompensasi->kode_kompensasi }}" {{ $pembelian->kode_kompensasi == $kompensasi->kode_kompensasi ? 'selected' : '' }} 
                                                data-jumlah="{{ $kompensasi->jumlah_kompensasi }}">
                                                {{ $kompensasi->kode_kompensasi }} - {{ number_format($kompensasi->jumlah_kompensasi, 2) }} ton
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kode_kompensasi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="kode_penyedia" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-building me-1"></i>Penyedia Carbon Credit <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building-fill"></i></span>
                                    <select id="kode_penyedia" class="form-select @error('kode_penyedia') is-invalid @enderror" name="kode_penyedia" required>
                                        @foreach($penyediaList as $penyedia)
                                            <option value="{{ $penyedia->kode_penyedia }}" {{ $pembelian->kode_penyedia == $penyedia->kode_penyedia ? 'selected' : '' }}
                                                data-harga="{{ $penyedia->harga_per_ton }}">
                                                {{ $penyedia->kode_penyedia }} - {{ $penyedia->nama_penyedia }} ({{ number_format($penyedia->harga_per_ton, 0) }}/ton)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kode_penyedia')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>                        <div class="mb-4 row">
                            <label for="jumlah_kompensasi" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-calculator me-1"></i>Jumlah Kompensasi <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calculator"></i></span>
                                    <input id="jumlah_kompensasi" type="number" class="form-control @error('jumlah_kompensasi') is-invalid @enderror" name="jumlah_kompensasi" value="{{ old('jumlah_kompensasi', $pembelian->jumlah_kompensasi) }}" step="0.01" readonly>
                                    <span class="input-group-text">ton</span>
                                    @error('jumlah_kompensasi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="text-muted">Jumlah kompensasi diambil dari data kompensasi yang dipilih. Jika tidak terisi otomatis, klik tombol "Refresh Data".</small>
                                <!-- Hidden input for harga_per_ton -->
                                <input type="hidden" id="harga_per_ton" name="harga_per_ton" value="{{ old('harga_per_ton', $pembelian->harga_per_ton) }}">
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="total_harga" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-cash-coin me-1"></i>Total Harga <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-cash-coin"></i></span>
                                    <input id="total_harga" type="number" class="form-control @error('total_harga') is-invalid @enderror" name="total_harga" value="{{ old('total_harga', $pembelian->total_harga) }}" readonly>
                                    @error('total_harga')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="text-muted">Total harga dihitung dari jumlah kompensasi × harga per ton. Jika tidak terhitung otomatis, klik tombol "Refresh Data".</small>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="tanggal_pembelian" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-calendar-date me-1"></i>Tanggal Pembelian <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                                    <input id="tanggal_pembelian" type="date" class="form-control @error('tanggal_pembelian') is-invalid @enderror" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', date('Y-m-d', strtotime($pembelian->tanggal_pembelian))) }}" required>
                                    @error('tanggal_pembelian')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="bukti_pembayaran" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-receipt me-1"></i>Bukti Pembayaran
                            </label>
                            <div class="col-md-9">
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="bi bi-receipt"></i></span>
                                    <input id="bukti_pembayaran" type="file" class="form-control @error('bukti_pembayaran') is-invalid @enderror" name="bukti_pembayaran" accept="image/*">
                                    @error('bukti_pembayaran')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                @if($pembelian->bukti_pembayaran)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $pembelian->bukti_pembayaran) }}" class="img-thumbnail" style="max-height: 200px;" alt="Bukti Pembayaran Saat Ini">
                                        <p class="text-muted mt-1 mb-0">Bukti pembayaran saat ini. Upload baru untuk mengganti.</p>
                                    </div>
                                @endif
                                <div id="preview-container" class="mt-2 d-none">
                                    <h6>Preview bukti pembayaran baru:</h6>
                                    <img id="preview" class="img-thumbnail" style="max-height: 200px;" alt="Preview Bukti Pembayaran">
                                </div>
                                <small class="text-muted">Upload gambar bukti pembayaran (JPG, PNG, GIF, max 2MB)</small>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="deskripsi" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-card-text me-1"></i>Deskripsi <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                    <textarea id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="4" placeholder="Deskripsi pembelian carbon credit" required>{{ old('deskripsi', $pembelian->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row pt-3 border-top">
                            <div class="col-md-9 offset-md-3">                                <button type="submit" class="btn btn-success me-2 px-4" id="submitBtn">
                                    <i class="bi bi-check-circle me-1"></i>Perbarui Data Pembelian
                                </button>
                                <a href="{{ route('manager.pembelian-carbon-credit.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Batal
                                </a>
                                <button type="button" id="refreshData" class="btn btn-outline-info ms-2">
                                    <i class="bi bi-arrow-repeat me-1"></i>Refresh Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>    $(document).ready(function() {
        // Fungsi untuk menghitung total harga
        function hitungTotalHarga() {
            const jumlahKompensasi = parseFloat($('#jumlah_kompensasi').val()) || 0;
            const hargaPerTon = parseFloat($('#harga_per_ton').val()) || parseFloat($('#kode_penyedia option:selected').data('harga')) || 0;
            const totalHarga = jumlahKompensasi * hargaPerTon;
            $('#total_harga').val(totalHarga.toFixed(2));
        }
        
        // Fungsi untuk mengambil data form lewat AJAX
        function getFormData() {
            const kode_kompensasi = $('#kode_kompensasi').val();
            const kode_penyedia = $('#kode_penyedia').val();
            
            if (!kode_kompensasi && !kode_penyedia) return;
            
            // Tampilkan loading indicator jika diperlukan
            
            // Panggil API untuk mendapatkan data
            fetch(`{{ route('manager.pembelian-carbon-credit.get-form-data') }}?kode_kompensasi=${kode_kompensasi}&kode_penyedia=${kode_penyedia}`)
                .then(response => response.json())
                .then(data => {                    // Update nilai form berdasarkan respons API
                    if (data.jumlah_kompensasi) {
                        $('#jumlah_kompensasi').val(data.jumlah_kompensasi);
                    }
                    
                    if (data.harga_per_ton) {
                        // Update data attribute pada option yang dipilih
                        $('#kode_penyedia option:selected').data('harga', data.harga_per_ton);
                        $('#harga_per_ton').val(data.harga_per_ton);
                    }
                    
                    // Hitung ulang total harga
                    hitungTotalHarga();
                })
                .catch(error => {
                    console.error('Error fetching form data:', error);
                });
        }

        // Fungsi untuk mengisi jumlah kompensasi berdasarkan pilihan kode kompensasi
        $('#kode_kompensasi').change(function() {
            // Cara lama dengan data attribute sebagai fallback
            const jumlahKompensasi = $(this).find('option:selected').data('jumlah') || '';
            $('#jumlah_kompensasi').val(jumlahKompensasi);
            
            // Cara baru dengan AJAX
            getFormData();
            
            hitungTotalHarga();
        });

        // Fungsi untuk menghitung ulang total harga ketika penyedia dipilih
        $('#kode_penyedia').change(function() {
            // Cara baru dengan AJAX
            getFormData();
            
            hitungTotalHarga();
        });

        // Inisialisasi nilai saat halaman dimuat
        hitungTotalHarga();
        
        // Add refresh button functionality
        $('#refreshData').click(function() {
            getFormData();
        });

        // Preview gambar yang diupload
        $('#bukti_pembayaran').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                    $('#preview-container').removeClass('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                $('#preview-container').addClass('d-none');
            }
        });
    });
</script>
@endpush
