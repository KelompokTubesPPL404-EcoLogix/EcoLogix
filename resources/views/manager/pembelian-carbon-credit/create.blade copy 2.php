@extends('layouts.manager')

@section('title', 'Tambah Pembelian Carbon Credit')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-cart-plus me-2"></i>Tambah Pembelian Carbon Credit
            <small class="text-muted fs-6">untuk {{ Auth::user()->perusahaan->nama_perusahaan }}</small>
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
                            <i class="bi bi-cart-plus me-2"></i>Form Pembelian Carbon Credit
                        </h6>
                        <span class="badge bg-success">
                            <i class="bi bi-building-gear me-1"></i>{{ Auth::user()->perusahaan->kode_perusahaan }} - {{ Auth::user()->perusahaan->nama_perusahaan }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('manager.pembelian-carbon-credit.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4 row">
                            <label for="kode_kompensasi" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-upc me-1"></i>Kode Kompensasi <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                    <select id="kode_kompensasi" class="form-select @error('kode_kompensasi') is-invalid @enderror" name="kode_kompensasi" required>
                                        <option value="">-- Pilih Kode Kompensasi --</option>                                       
                                        @foreach($kompensasiList as $kompensasi)
                                            <option value="{{ $kompensasi->kode_kompensasi }}" data-jumlah="{{ $kompensasi->jumlah_kompensasi }}">
                                                {{ $kompensasi->kode_kompensasi }} - {{ number_format($kompensasi->jumlah_kompensasi, 3) }} ton CO₂e
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
                                    <span class="input-group-text"><i class="bi bi-building me-1"></i></span>
                                    <select id="kode_penyedia" class="form-select @error('kode_penyedia') is-invalid @enderror" name="kode_penyedia" required>
                                        <option value="">-- Pilih Penyedia --</option>
                                        @foreach($penyediaList as $penyedia)
                                            <option value="{{ $penyedia->kode_penyedia }}" data-harga="{{ $penyedia->harga_per_ton }}" data-mata-uang="{{ $penyedia->mata_uang }}">
                                                {{ $penyedia->kode_penyedia }} - {{ $penyedia->nama_penyedia }} ({{ $penyedia->mata_uang }} {{ number_format($penyedia->harga_per_ton, 0) }}/ton CO₂)
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
                        </div>                        
                        
                        <div class="mb-4 row">
                            <label class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-calculator me-1"></i>Jumlah Kompensasi <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calculator"></i></span>
                                    <input id="jumlah_kompensasi_display" type="text" class="form-control" readonly>
                                    <span class="input-group-text">kg CO₂e</span>
                                </div>
                                <small class="text-muted">Jumlah kompensasi diambil dari data kompensasi yang dipilih. Jika tidak terisi otomatis, klik tombol "Refresh Data".</small>
                            </div>
                        </div>
                        
                        <div class="mb-4 row">
                            <label class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-cash-coin me-1"></i>Total Harga <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-cash-coin"></i></span>
                                    <input id="total_harga_display" type="text" class="form-control" readonly>
                                    <span class="input-group-text" id="mata-uang-display"></span>
                                </div>
                                <small class="text-muted">Total harga dihitung dari jumlah kompensasi × harga per ton. Jika tidak terhitung otomatis, klik tombol "Refresh Data".</small>
                                <div id="harga_per_ton_display" class="mt-1 text-info fw-medium"></div>
                            </div>
                        </div>

                        <!-- Hidden inputs untuk form submission -->
                        <input type="text" name="jumlah_kompensasi" id="jumlah_kompensasi">
                        <input type="text" id="harga_per_ton" name="harga_per_ton">
                        <input type="text" name="total_harga" id="total_harga">

                        <div class="mb-4 row">
                            <label for="tanggal_pembelian" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-calendar-date me-1"></i>Tanggal Pembelian <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                                    <input id="tanggal_pembelian" type="date" class="form-control @error('tanggal_pembelian') is-invalid @enderror" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" required>
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
                                <i class="bi bi-receipt me-1"></i>Bukti Pembayaran <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="bi bi-receipt"></i></span>
                                    <input id="bukti_pembayaran" type="file" class="form-control @error('bukti_pembayaran') is-invalid @enderror" name="bukti_pembayaran" accept="image/*" required>
                                    @error('bukti_pembayaran')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div id="preview-container" class="mt-2 d-none">
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
                                    <textarea id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="4" placeholder="Deskripsi pembelian carbon credit" required>{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row pt-3 border-top">
                            <div class="col-md-9 offset-md-3">                        <button type="submit" class="btn btn-success me-2 px-4" id="submitBtn">
                                    <i class="bi bi-check-circle me-1"></i>Simpan Pembelian
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kompensasiSelect = document.getElementById('kode_kompensasi');
    const penyediaSelect = document.getElementById('kode_penyedia');
    const jumlahInput = document.getElementById('jumlah_kompensasi');
    const jumlahDisplay = document.getElementById('jumlah_kompensasi_display');
    const hargaPerTonInput = document.getElementById('harga_per_ton');
    const totalHargaInput = document.getElementById('total_harga');
    const totalHargaDisplay = document.getElementById('total_harga_display');
    const mataUangDisplay = document.getElementById('mata-uang-display');
    const hargaPerTonDisplay = document.getElementById('harga_per_ton_display');

    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function hitungTotalHarga() {
        const jumlahKg = parseFloat(jumlahInput.value) || 0;
        const hargaPerTon = parseFloat(hargaPerTonInput.value) || 0;
        const mataUang = mataUangDisplay.textContent;
        
        // Konversi kg ke ton (1 ton = 1000 kg)
        const jumlahTon = jumlahKg / 1000;
        const total = jumlahTon * hargaPerTon;
        
        // Update displays
        jumlahDisplay.value = formatNumber(jumlahKg);
        totalHargaInput.value = total.toFixed(2);
        totalHargaDisplay.value = formatNumber(total);
        
        // Tampilkan informasi harga per ton
        hargaPerTonDisplay.textContent = `Harga: ${formatNumber(hargaPerTon)} ${mataUang}/ton CO₂`;
    }

    kompensasiSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const jumlah = selectedOption.dataset.jumlah || '0';
        jumlahInput.value = jumlah;
        hitungTotalHarga();
    });

    penyediaSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const hargaPerTon = selectedOption.dataset.harga || '0';
        const mataUang = selectedOption.dataset.mataUang || 'IDR';
        
        hargaPerTonInput.value = hargaPerTon;
        mataUangDisplay.textContent = mataUang;
        hitungTotalHarga();
    });
});
</script>
@endpush
@endsection