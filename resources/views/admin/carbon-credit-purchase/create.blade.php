@extends('layouts.admin')

@section('title', 'Tambah Pembelian Carbon Credit')

@push('css')
<style>
    .eco-gradient {
        background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%);
    }
    .form-label {
        font-weight: 500;
        color: #2c3e50;
    }
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    .required-label::after {
        content: " *";
        color: #dc3545;
    }
    .form-control:focus, .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
    }
    .btn-back {
        transition: all 0.2s;
    }
    .btn-back:hover {
        transform: translateX(-3px);
    }
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .custom-tooltip {
        position: relative;
        display: inline-block;
        cursor: help;
        color: #6c757d;
    }
    .custom-tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
    }
    .tooltip-text {
        visibility: hidden;
        width: 200px;
        background-color: #343a40;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -100px;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .form-section {
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #28a745;
    }
    .form-section-title {
        color: #28a745;
        font-weight: 600;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <a href="{{ route('admin.carbon-credit-purchase.index') }}" class="btn btn-sm btn-outline-primary btn-back mr-2">
                    <i class="bi bi-arrow-left"></i>
                </a>
                Tambah Pembelian Carbon Credit
            </h1>
            <p class="text-muted">Form untuk menambahkan data pembelian carbon credit</p>
        </div>
    </div>

    <div class="card shadow mb-4 border-0">
        <div class="card-header py-3 eco-gradient">
            <h6 class="m-0 font-weight-bold text-white">Form Pembelian Carbon Credit</h6>
        </div>
        <div class="card-body">
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.carbon-credit-purchase.store') }}" method="POST" enctype="multipart/form-data" id="purchaseForm">
                @csrf
                
                <!-- Kompensasi & Penyedia Section -->
                <div class="form-section">
                    <h5 class="form-section-title">
                        <i class="bi bi-clipboard-data me-2"></i>Informasi Kompensasi & Penyedia
                    </h5>
                    <div class="row g-3">
                        <!-- Pilih Kompensasi Emisi -->
                        <div class="col-md-6 mb-3">
                            <label for="kode_kompensasi" class="form-label required-label">Pilih Kompensasi Emisi</label>
                            <select name="kode_kompensasi" id="kode_kompensasi" class="form-select" required>
                                <option value="" selected>-- Pilih Kompensasi Emisi --</option>
                                @foreach($compensations as $kompensasi)
                                <option value="{{ $kompensasi->kode_kompensasi }}" data-jumlah="{{ $kompensasi->jumlah_kompensasi }}" {{ old('kode_kompensasi') == $kompensasi->kode_kompensasi ? 'selected' : '' }}>
                                    {{ $kompensasi->kode_kompensasi }} - {{ number_format($kompensasi->jumlah_kompensasi/1000, 2) }} ton ({{ $kompensasi->tanggal_kompensasi }})
                                    {{-- Nilai jumlah_kompensasi dibagi 1000 untuk konversi dari kg ke ton --}}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">Pilih data kompensasi emisi yang akan dibeli carbon credit-nya.</div>
                        </div>

                        <!-- Pilih Penyedia Carbon Credit -->
                        <div class="col-md-6 mb-3">
                            <label for="kode_penyedia" class="form-label required-label">Pilih Penyedia Carbon Credit</label>
                            <select name="kode_penyedia" id="kode_penyedia" class="form-select" required>
                                <option value="" selected>-- Pilih Penyedia Carbon Credit --</option>
                                @foreach($providers as $penyedia)
                                <option value="{{ $penyedia->kode_penyedia }}" {{ old('kode_penyedia') == $penyedia->kode_penyedia ? 'selected' : '' }}>
                                    {{ $penyedia->nama_penyedia }} - {{ $penyedia->mata_uang }} {{ number_format($penyedia->harga_per_ton, 2) }}/ton
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">Pilih penyedia carbon credit.</div>
                        </div>
                    </div>
                </div>

                <!-- Detail Transaksi Section -->
                <div class="form-section">
                    <h5 class="form-section-title">
                        <i class="bi bi-calculator me-2"></i>Detail Transaksi
                    </h5>
                    <div class="row g-3">
                        <!-- Jumlah Kompensasi -->
                        <div class="col-md-4 mb-3">
                            <label for="jumlah_kompensasi" class="form-label required-label">Jumlah Kompensasi</label>
                            <div class="input-group">
                                <input type="number" name="jumlah_kompensasi" id="jumlah_kompensasi" class="form-control" step="0.01" min="0.01" value="{{ number_format($kompensasi->jumlah_kompensasi/1000, 2) }}" readonly required>
                                <span class="input-group-text">ton</span>
                            </div>
                            <div class="form-text text-muted">Jumlah kompensasi emisi dalam ton (diambil otomatis dari data kompensasi).</div>
                        </div>

                        <!-- Harga Per Ton -->
                        <div class="col-md-4 mb-3">
                            <label for="harga_per_ton" class="form-label required-label">Harga Per Ton</label>
                            <div class="input-group">
                                <span class="input-group-text currency-symbol">Rp</span>
                                <input type="number" name="harga_per_ton" id="harga_per_ton" class="form-control" step="0.01" min="0.01" value="{{ old('harga_per_ton', '0.00') }}" readonly required>
                            </div>
                            <div class="form-text text-muted">Harga per ton carbon credit (diambil otomatis dari penyedia).</div>
                        </div>

                        <!-- Total Harga -->
                        <div class="col-md-4 mb-3">
                            <label for="total_harga" class="form-label required-label">Total Harga</label>
                            <div class="input-group">
                                <span class="input-group-text currency-symbol">Rp</span>
                                <input type="number" name="total_harga" id="total_harga" class="form-control" step="0.01" min="0.01" value="{{ old('total_harga', '0.00') }}" readonly required>
                            </div>
                            <div class="form-text text-muted">Total harga pembelian carbon credit (dihitung otomatis).</div>
                        </div>

                        <!-- Tanggal Pembelian -->
                        <div class="col-md-4 mb-3">
                            <label for="tanggal_pembelian" class="form-label required-label">Tanggal Pembelian</label>
                            <input type="date" name="tanggal_pembelian" id="tanggal_pembelian" class="form-control" value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" required>
                            <div class="form-text text-muted">Tanggal pembelian carbon credit.</div>
                        </div>
                    </div>
                </div>
                
                <!-- Dokumentasi Section -->
                <div class="form-section">
                    <h5 class="form-section-title">
                        <i class="bi bi-file-earmark-text me-2"></i>Dokumentasi
                    </h5>
                    <div class="row g-3">
                        <!-- Bukti Pembayaran -->
                        <div class="col-md-6 mb-3">
                            <label for="bukti_pembayaran" class="form-label required-label">Bukti Pembayaran</label>
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" accept="image/*" required>
                            <div class="form-text text-muted">Upload bukti pembayaran pembelian carbon credit (JPG, PNG, maksimal 2MB).</div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-md-6 mb-3">
                            <label for="deskripsi" class="form-label required-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required>{{ old('deskripsi') }}</textarea>
                            <div class="form-text text-muted">Deskripsi singkat mengenai pembelian carbon credit ini.</div>
                        </div>
                        
                        <!-- Preview Image Container -->
                        <div class="col-12 mb-3 d-none" id="previewContainer">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Preview Bukti Pembayaran</h6>
                                </div>
                                <div class="card-body text-center">
                                    <img id="imagePreview" src="#" alt="Preview" class="img-fluid" style="max-height: 300px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Environmental Impact Summary -->
                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-success">
                            <i class="bi bi-tree"></i> Dampak Lingkungan
                        </h5>
                        <p class="card-text">Dengan pembelian carbon credit ini, Anda akan berkontribusi pada:</p>
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="h2 text-success" id="treesEquivalent">0</div>
                                <p class="text-muted">Pohon</p>
                            </div>
                            <div class="col-md-4">
                                <div class="h2 text-success" id="energySavings">0</div>
                                <p class="text-muted">kWh Energi</p>
                            </div>
                            <div class="col-md-4">
                                <div class="h2 text-success" id="emissionReduction">0.00</div>
                                <p class="text-muted">Ton CO₂</p>
                            </div>
                        </div>
                        <p class="small text-muted mt-2 mb-0">* Estimasi berdasarkan rata-rata industri untuk dampak carbon offset.</p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12 text-end">
                        <a href="{{ route('admin.carbon-credit-purchase.index') }}" class="btn btn-secondary me-2">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Initialize form elements as readonly
        $('#jumlah_kompensasi').prop('readonly', true);
        $('#harga_per_ton').prop('readonly', true);
        $('#total_harga').prop('readonly', true);
        
        // Add event listeners for the select fields
        $('#kode_kompensasi').on('change', updateJumlahKompensasi);
        $('#kode_penyedia').on('change', updateHargaPerTon);
        
        // Check if values are already selected (e.g., on form validation error)
        if ($('#kode_kompensasi').val()) {
            updateJumlahKompensasi();
        }
        
        if ($('#kode_penyedia').val()) {
            updateHargaPerTon();
        }

        // Function to calculate total price
        function calculateTotal() {
            var jumlahKompensasi = parseFloat($('#jumlah_kompensasi').val()) || 0;
            var hargaPerTon = parseFloat($('#harga_per_ton').val()) || 0;
            var totalHarga = jumlahKompensasi * hargaPerTon;
            $('#total_harga').val(totalHarga.toFixed(2));
            
            // Update environmental impact estimates
            updateEnvironmentalImpact(jumlahKompensasi);
        }
        
        // Function to update environmental impact estimates
        function updateEnvironmentalImpact(tons) {
            const treesPerTon = 45;  // Approximately 45 trees per ton of CO2
            const kwhPerTon = 2400;  // Approximately 2400 kWh per ton of CO2
            
            $('#treesEquivalent').text(Math.round(tons * treesPerTon));
            $('#energySavings').text(Math.round(tons * kwhPerTon));
            $('#emissionReduction').text(tons.toFixed(2));
        }

        // Function to reset form when no selection
        function resetForm() {
            $('#jumlah_kompensasi').val('0.00');
            $('#harga_per_ton').val('0.00');
            $('#total_harga').val('0.00');
            $('.currency-symbol').text('Rp');
            updateEnvironmentalImpact(0);
        }

        // Function to update jumlah kompensasi when kompensasi is selected
        function updateJumlahKompensasi() {
            console.log('Fungsi updateJumlahKompensasi dipanggil.'); // Debugging Point 1

            var selectedOption = $('#kode_kompensasi').find(':selected');
            var kodeKompensasiYangDipilih = selectedOption.val(); // Untuk debugging
            var jumlahKompensasiMentah = selectedOption.data('jumlah'); // Ambil nilai mentah dari data-jumlah

            console.log('Kode Kompensasi yang dipilih:', kodeKompensasiYangDipilih); // Debugging Point 2
            console.log('Nilai data-jumlah yang diambil:', jumlahKompensasiMentah); // Debugging Point 3

            // Konversi ke float, bagi 1000, dan format
            if (jumlahKompensasiMentah !== undefined && jumlahKompensasiMentah !== null) { // Pastikan nilai ada
                const jumlahKompensasiTon = parseFloat(jumlahKompensasiMentah) / 1000;
                $('#jumlah_kompensasi').val(jumlahKompensasiTon.toFixed(2));
                console.log('jumlah_kompensasi diupdate menjadi:', jumlahKompensasiTon.toFixed(2)); // Debugging Point 4
            } else {
                // Ini blok yang Anda ubah menjadi 10, pastikan dieksekusi jika data-jumlah tidak ada
                $('#jumlah_kompensasi').val('10.00'); // Atau nilai default yang Anda inginkan
                console.log('jumlah_kompensasi diset ke default 10.00 (data-jumlah tidak ditemukan atau undefined/null).'); // Debugging Point 5
            }
            calculateTotal(); // Selalu panggil calculateTotal setelah update jumlah
        }


        // Function to update harga per ton when penyedia is selected
        function updateHargaPerTon() {
            var kodePenyedia = $('#kode_penyedia').val();
            
            if (kodePenyedia && kodePenyedia !== '') {
                $.ajax({
                    url: "{{ route('admin.carbon-credit-purchase.get-form-data') }}",
                    type: "GET",
                    data: {
                        kode_penyedia: kodePenyedia
                    },
                    success: function(response) {
                        if (response.harga_per_ton !== null && response.harga_per_ton !== undefined) {
                            // Convert to float and format with 2 decimal places
                            const hargaPerTon = parseFloat(response.harga_per_ton);
                            $('#harga_per_ton').val(hargaPerTon.toFixed(2));
                        }
                        if (response.mata_uang !== null && response.mata_uang !== undefined) {
                            $('.currency-symbol').text(response.mata_uang);
                        }
                        calculateTotal(); // Recalculate total after updating harga
                    },
                    error: function() {
                        console.log('Error fetching penyedia data');
                        $('#harga_per_ton').val('0.00');
                        $('.currency-symbol').text('Rp');
                        calculateTotal();
                    }
                });
            } else {
                $('#harga_per_ton').val('0.00');
                $('.currency-symbol').text('Rp');
                calculateTotal();
            }
        }
        
        // Preview uploaded image
        $('#bukti_pembayaran').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result);
                    $('#previewContainer').removeClass('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                $('#previewContainer').addClass('d-none');
            }
        });

        // Event handlers are already set at the top of the script
        
        // Form validation
        $('#purchaseForm').submit(function(e) {
            let isValid = true;
            const requiredFields = ['kode_kompensasi', 'kode_penyedia', 'jumlah_kompensasi', 
                                   'harga_per_ton', 'total_harga', 'tanggal_pembelian', 
                                   'deskripsi'];
            
            // Check if all required fields are filled
            requiredFields.forEach(field => {
                const input = $(`#${field}`);
                if (!input.val()) {
                    input.addClass('is-invalid');
                    isValid = false;
                } else {
                    input.removeClass('is-invalid');
                }
            });
            
            // Check if bukti_pembayaran is selected
            if (!$('#bukti_pembayaran').val() && !$('#bukti_pembayaran').hasClass('optional')) {
                $('#bukti_pembayaran').addClass('is-invalid');
                isValid = false;
            } else {
                $('#bukti_pembayaran').removeClass('is-invalid');
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                $('html, body').animate({
                    scrollTop: $('.is-invalid:first').offset().top - 100
                }, 500);
            }
        });

        // Initialize form only if there are no existing values
        if (!$('#kode_kompensasi').val() && !$('#kode_penyedia').val()) {
            resetForm();
        }
    });
</script>
@endpush
