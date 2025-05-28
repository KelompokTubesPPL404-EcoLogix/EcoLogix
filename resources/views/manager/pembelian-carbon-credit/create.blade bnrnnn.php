@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3">
        <div>
            <h1 class="h2 mb-0 text-success fw-bold">
                <i class="bi bi-cart-plus me-2"></i>Tambah Pembelian Carbon Credit
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('manager.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('manager.pembelian-carbon-credit.index') }}">Pembelian Carbon Credit</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Pembelian</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('manager.pembelian-carbon-credit.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow border-0 rounded-3">                <div class="card-header bg-success bg-opacity-10 py-3">
                    <h5 class="card-title mb-0 text-success">
                        <i class="bi bi-cash-coin me-2"></i>Form Pembelian Carbon Credit - PT. PPL asik
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <strong><i class="bi bi-exclamation-triangle me-2"></i>Terjadi kesalahan!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('manager.pembelian-carbon-credit.store') }}" enctype="multipart/form-data">
                        @csrf                        <div class="mb-4 row">
                            <label for="kode_kompensasi" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-upc me-1"></i>Kode Kompensasi <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>                                    <select id="kode_kompensasi" class="form-select @error('kode_kompensasi') is-invalid @enderror" name="kode_kompensasi" required data-bs-toggle="tooltip" title="Pilih kode kompensasi untuk mengambil jumlah kompensasi secara otomatis">
                                        <option value="">-- Pilih Kode Kompensasi --</option>                                       
                                        @foreach($kompensasiList as $kompensasi)
                                            <option value="{{ $kompensasi->kode_kompensasi }}" data-jumlah="{{ $kompensasi->jumlah_kompensasi }}">
                                                {{ $kompensasi->kode_kompensasi }} - {{ number_format($kompensasi->jumlah_kompensasi / 1000, 3) }} ton CO₂e
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
                        </div>                        <div class="mb-4 row">
                            <label for="kode_penyedia" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-building me-1"></i>Penyedia Carbon Credit <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">                                
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>                                    <select id="kode_penyedia" class="form-select @error('kode_penyedia') is-invalid @enderror" name="kode_penyedia" required data-bs-toggle="tooltip" title="Pilih penyedia carbon credit untuk mengambil harga per ton secara otomatis">
                                        <option value="">-- Pilih Penyedia --</option>
                                        @foreach($penyediaList as $penyedia)
                                            <option value="{{ $penyedia->kode_penyedia }}" 
                                                data-harga="{{ $penyedia->harga_per_ton }}"
                                                data-mata-uang="{{ $penyedia->mata_uang }}">
                                                {{ $penyedia->nama_penyedia }} - 
                                                {{ number_format($penyedia->harga_per_ton, 2) }} {{ $penyedia->mata_uang }}/ton CO₂
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
                            <label class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-calculator me-1"></i>Jumlah Kompensasi <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calculator"></i></span>
                                    <input id="jumlah_display" type="text" class="form-control" readonly>
                                    <span class="input-group-text">kg CO₂e</span>
                                </div>
                                <small class="text-muted">Jumlah kompensasi diambil otomatis dari data kompensasi yang dipilih.</small>
                                <div id="jumlah_feedback" class="valid-feedback d-none">
                                    Jumlah kompensasi berhasil diambil.
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-cash-coin me-1"></i>Total Harga <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">                                <div class="input-group">
                                    <span class="input-group-text" id="mata-uang-display">IDR</span>
                                    <input id="total_harga_display" type="text" class="form-control" readonly>
                                </div>                                <small class="text-muted mt-1" id="harga_per_ton_display"></small>
                                <div id="total_feedback" class="valid-feedback d-none">
                                    Total harga berhasil dihitung.
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="bi bi-info-circle"></i> Rumus perhitungan: <span id="formula_display" class="fst-italic"></span>
                                </small>
                            </div>
                        </div>                       
                         <!-- Hidden input fields for form submission -->
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
                        </div>                        <div class="mb-4 row">
                            <label for="bukti_pembayaran" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-file-earmark-image me-1"></i>Bukti Pembayaran <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input id="bukti_pembayaran" type="file" class="form-control @error('bukti_pembayaran') is-invalid @enderror" name="bukti_pembayaran" accept="image/*" required>
                                    <label class="input-group-text" for="bukti_pembayaran"><i class="bi bi-upload"></i></label>
                                    @error('bukti_pembayaran')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="text-muted">Upload bukti pembayaran berupa gambar (JPG, PNG, GIF) dengan ukuran maksimal 2MB.</small>
                                <div class="mt-2">
                                    <img id="preview" src="" alt="Preview Image" class="img-thumbnail d-none" style="max-height: 200px">
                                </div>
                            </div>
                        </div>                        <div class="mb-4 row">
                            <label for="deskripsi" class="col-md-3 col-form-label fw-medium text-success">
                                <i class="bi bi-file-text me-1"></i>Deskripsi <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                    <textarea id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="3" required>{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-9 offset-md-3">                                <button type="submit" class="btn btn-success px-4 me-2">
                                    <i class="bi bi-save me-1"></i> Simpan
                                </button>
                                <a href="{{ route('manager.pembelian-carbon-credit.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Batal
                                </a>
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
    // Debug: Verifikasi bahwa semua elemen form ditemukan
    console.log('Verifikasi elemen form:');
    
    // Select fields
    const kompensasiSelect = document.getElementById('kode_kompensasi');
    console.log('kompensasiSelect found:', !!kompensasiSelect);
    
    const penyediaSelect = document.getElementById('kode_penyedia');
    console.log('penyediaSelect found:', !!penyediaSelect);
    
    // Hidden input fields
    const jumlahInput = document.getElementById('jumlah_kompensasi');
    console.log('jumlahInput found:', !!jumlahInput);
    
    const hargaPerTonInput = document.getElementById('harga_per_ton');
    console.log('hargaPerTonInput found:', !!hargaPerTonInput);
    
    const totalHargaInput = document.getElementById('total_harga');
    console.log('totalHargaInput found:', !!totalHargaInput);
    
    // Display fields
    const jumlahDisplay = document.getElementById('jumlah_display');
    const totalHargaDisplay = document.getElementById('total_harga_display');
    const mataUangDisplay = document.getElementById('mata-uang-display');
    const hargaPerTonDisplay = document.getElementById('harga_per_ton_display');

    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }    function hitungTotalHarga() {
        // Mengambil nilai dari input tersembunyi (pastikan nilai diambil dengan benar)
        const jumlahKg = parseFloat(jumlahInput.value) || 0;
        const hargaPerTon = parseFloat(hargaPerTonInput.value) || 0;
        const mataUang = mataUangDisplay.textContent || 'IDR';
        
        // Konversi kg ke ton (1 ton = 1000 kg)
        const jumlahTon = jumlahKg / 1000;
        
        // Hitung total harga: jumlahTon * hargaPerTon
        const total = jumlahTon * hargaPerTon;
        
        // Update display untuk jumlah kompensasi dengan format angka
        jumlahDisplay.value = formatNumber(jumlahKg);
        
        // Update hidden input untuk nilai total harga yang akan dikirim ke server (2 desimal)
        totalHargaInput.value = total.toFixed(2);
          // Update display untuk total harga dengan format angka yang mudah dibaca
        totalHargaDisplay.value = formatNumber(total.toFixed(2));
        
        // Tampilkan feedback kalkulasi total berhasil
        const totalFeedbackEl = document.getElementById('total_feedback');
        if (totalFeedbackEl && jumlahKg > 0 && hargaPerTon > 0) {
            totalFeedbackEl.textContent = `Total harga berhasil dihitung: ${formatNumber(total.toFixed(2))} ${mataUang}`;
            totalFeedbackEl.classList.remove('d-none');
            setTimeout(() => {
                totalFeedbackEl.classList.add('d-none');
            }, 3000);
        }
        
        // Tampilkan informasi harga per ton
        if (hargaPerTon > 0) {
            hargaPerTonDisplay.textContent = `Harga: ${formatNumber(hargaPerTon)} ${mataUang}/ton CO₂`;
            hargaPerTonDisplay.classList.remove('d-none');
            
            // Update formula display
            document.getElementById('formula_display').textContent = 
                `(${formatNumber(jumlahKg)} kg CO₂e ÷ 1000) × ${formatNumber(hargaPerTon)} ${mataUang}/ton = ${formatNumber(total)} ${mataUang}`;
        } else {
            hargaPerTonDisplay.classList.add('d-none');
            document.getElementById('formula_display').textContent = '';
        }
        
        // Log untuk membantu debug
        console.log({
            jumlahKg: jumlahKg,
            hargaPerTon: hargaPerTon,
            jumlahTon: jumlahTon,
            total: total,
            hiddenInputs: {
                jumlah_kompensasi: jumlahInput.value,
                harga_per_ton: hargaPerTonInput.value,
                total_harga: totalHargaInput.value
            }
        });
    }    kompensasiSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.selectedIndex > 0) {
            // Menggunakan dataset untuk mengakses data-attributes
            const jumlah = selectedOption.dataset.jumlah || '0';
            
            // SIMPAN NILAI KE INPUT TERSEMBUNYI
            jumlahInput.value = jumlah;
            
            // Tampilkan feedback agar user tahu nilai berhasil diambil
            const jumlahFeedbackEl = document.getElementById('jumlah_feedback');
            if (jumlahFeedbackEl) {
                jumlahFeedbackEl.textContent = `Jumlah kompensasi berhasil diambil: ${formatNumber(jumlah)} kg CO₂e`;
                jumlahFeedbackEl.classList.remove('d-none');
                setTimeout(() => {
                    jumlahFeedbackEl.classList.add('d-none');
                }, 3000);
            }
            
            console.log('Nilai jumlah_kompensasi diisi: ' + jumlah);
        } else {
            // Reset nilai jika tidak ada pilihan
            jumlahInput.value = '0';
            jumlahDisplay.value = '';
        }
        
        // Hitung total setelah mengubah nilai
        hitungTotalHarga();
    });penyediaSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.selectedIndex > 0) {
            // Menggunakan dataset untuk mengakses data-attributes
            const hargaPerTon = selectedOption.dataset.harga || '0';
            const mataUang = selectedOption.dataset.mataUang || 'IDR';
            
            // SIMPAN NILAI KE INPUT TERSEMBUNYI
            hargaPerTonInput.value = hargaPerTon;
            mataUangDisplay.textContent = mataUang;
            
            console.log('Nilai harga_per_ton diisi: ' + hargaPerTon);
        } else {
            // Reset nilai jika tidak ada pilihan
            hargaPerTonInput.value = '0';
            mataUangDisplay.textContent = 'IDR';
        }
        
        // Hitung total setelah mengubah nilai
        hitungTotalHarga();
    });
    
    // Handle bukti pembayaran image preview
    const buktiPembayaranInput = document.getElementById('bukti_pembayaran');
    const previewImage = document.getElementById('preview');
    
    buktiPembayaranInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.classList.remove('d-none');
            };
            
            reader.readAsDataURL(this.files[0]);
        }
    });
      // Check for existing values to populate hidden fields
    if (kompensasiSelect.selectedIndex > 0) {
        const selectedOption = kompensasiSelect.options[kompensasiSelect.selectedIndex];
        const jumlah = selectedOption.dataset.jumlah || '0';
        jumlahInput.value = jumlah;
        console.log('On init: nilai jumlah_kompensasi diisi: ' + jumlah);
    } else {
        console.log('On init: tidak ada kompensasi yang dipilih');
    }
    
    if (penyediaSelect.selectedIndex > 0) {
        const selectedOption = penyediaSelect.options[penyediaSelect.selectedIndex];
        const hargaPerTon = selectedOption.dataset.harga || '0';
        const mataUang = selectedOption.dataset.mataUang || 'IDR';
        
        hargaPerTonInput.value = hargaPerTon;
        mataUangDisplay.textContent = mataUang;
        console.log('On init: nilai harga_per_ton diisi: ' + hargaPerTon);
    } else {
        console.log('On init: tidak ada penyedia yang dipilih');
    }
    
    // Run initial calculation
    hitungTotalHarga();
    
    // Log initial state
    console.log('Form initialized with values:', {
        kode_kompensasi: kompensasiSelect.value,
        kode_penyedia: penyediaSelect.value,
        jumlah_kompensasi: jumlahInput.value,
        harga_per_ton: hargaPerTonInput.value,
        total_harga: totalHargaInput.value
    });
});
</script>
@endpush
@endsection 
