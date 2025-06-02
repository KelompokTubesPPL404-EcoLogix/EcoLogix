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
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .btn-back {
        transition: all 0.2s;
    }
    .btn-back:hover {
        transform: translateX(-3px);
    }
    .input-group-text {
        background-color: #e9ecef;
        border-color: #ced4da;
    }
    .currency-display {
        font-weight: bold;
        color: #28a745;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <a href="{{ route('admin.carbon-credit-purchase.index') }}" class="btn btn-sm btn-outline-primary btn-back mr-2">
                <i class="bi bi-arrow-left"></i>
            </a>
            Tambah Pembelian Carbon Credit
        </h1>
    </div>

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 eco-gradient">
            <h6 class="m-0 font-weight-bold text-white">Form Pembelian Carbon Credit</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.carbon-credit-purchase.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kode_kompensasi" class="form-label">Kompensasi Emisi Carbon <span class="text-danger">*</span></label>
                            <select class="form-control @error('kode_kompensasi') is-invalid @enderror" id="kode_kompensasi" name="kode_kompensasi" required>
                                <option value="">Pilih Kompensasi Emisi</option>
                                @foreach($compensations as $compensation)
                                    <option value="{{ $compensation->kode_kompensasi }}" data-jumlah-kompensasi="{{ $compensation->jumlah_kompensasi_ton }}" {{ old('kode_kompensasi') == $compensation->kode_kompensasi ? 'selected' : '' }}>
                                        {{ $compensation->kode_kompensasi }} - {{ number_format($compensation->jumlah_kompensasi_ton, 3) }} ton CO₂e ({{ \Carbon\Carbon::parse($compensation->tanggal_kompensasi)->format('d M Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_kompensasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kode_penyedia" class="form-label">Penyedia Carbon Credit <span class="text-danger">*</span></label>
                            <select class="form-control @error('kode_penyedia') is-invalid @enderror" id="kode_penyedia" name="kode_penyedia" required>
                                <option value="">Pilih Penyedia</option>
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->kode_penyedia }}" {{ old('kode_penyedia') == $provider->kode_penyedia ? 'selected' : '' }}>
                                        {{ $provider->nama_penyedia }} ({{ number_format($provider->harga_per_ton, 0, ',', '.') }} {{ $provider->mata_uang }}/ton)
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_penyedia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jumlah_kompensasi" class="form-label">Jumlah Carbon Credit (ton CO₂e) <span class="text-danger">*</span></label>
                            <input type="number" step="0.001" class="form-control @error('jumlah_kompensasi') is-invalid @enderror" id="jumlah_kompensasi" name="jumlah_kompensasi" value="{{ old('jumlah_kompensasi') }}" readonly>
                            @error('jumlah_kompensasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="harga_per_ton" class="form-label">Harga Per Ton <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="currency_symbol">Rp</span>
                                <input type="number" step="0.01" class="form-control @error('harga_per_ton') is-invalid @enderror" id="harga_per_ton" name="harga_per_ton" value="{{ old('harga_per_ton') }}" required>
                            </div>
                            @error('harga_per_ton')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="total_harga" class="form-label">Total Harga <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="total_currency_symbol">Rp</span>
                                <input type="number" step="0.01" class="form-control @error('total_harga') is-invalid @enderror" id="total_harga" name="total_harga" value="{{ old('total_harga') }}" readonly required>
                            </div>
                            @error('total_harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_pembelian') is-invalid @enderror" id="tanggal_pembelian" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" required>
                            @error('tanggal_pembelian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran (JPG, PNG, JPEG - Max 2MB) <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('bukti_pembayaran') is-invalid @enderror" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/jpeg,image/png,image/jpg" required>
                    @error('bukti_pembayaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Simpan Pembelian</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Function to update total price
        function updateTotalHarga() {
            let jumlah = parseFloat($('#jumlah_kompensasi').val());
            let hargaPerTon = parseFloat($('#harga_per_ton').val());
            
            if (!isNaN(jumlah) && !isNaN(hargaPerTon)) {
                let total = jumlah * hargaPerTon;
                $('#total_harga').val(total.toFixed(2));
            } else {
                $('#total_harga').val('');
            }
        }

        // Event listener for compensation selection change
        $('#kode_kompensasi').change(function() {
            let kode_kompensasi = $(this).val();
            if (kode_kompensasi) {
                $.ajax({
                    url: '{{ route("admin.carbon-credit-purchase.get-form-data") }}',
                    type: 'GET',
                    data: { kode_kompensasi: kode_kompensasi },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#jumlah_kompensasi').val(response.jumlah_kompensasi.toFixed(3));
                            updateTotalHarga();
                        } else {
                            alert(response.message);
                            $('#jumlah_kompensasi').val('');
                            $('#kode_kompensasi').val(''); // Clear selection on error
                            updateTotalHarga();
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat mengambil data kompensasi.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                        $('#jumlah_kompensasi').val('');
                        $('#kode_kompensasi').val(''); // Clear selection on error
                        updateTotalHarga();
                    }
                });
            } else {
                $('#jumlah_kompensasi').val('');
                updateTotalHarga();
            }
        });

        // Event listener for provider selection change
        $('#kode_penyedia').change(function() {
            let kode_penyedia = $(this).val();
            if (kode_penyedia) {
                $.ajax({
                    url: '{{ route("admin.carbon-credit-purchase.get-form-data") }}',
                    type: 'GET',
                    data: { kode_penyedia: kode_penyedia },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#harga_per_ton').val(response.harga_per_ton);
                            // Update currency symbol based on provider's currency
                            let currencySymbol = 'Rp'; // Default to IDR
                            if (response.mata_uang === 'USD') {
                                currencySymbol = '$';
                            } else if (response.mata_uang === 'EUR') {
                                currencySymbol = '€';
                            } else if (response.mata_uang === 'IDR') {
                                currencySymbol = 'Rp';
                            }
                            $('#currency_symbol').text(currencySymbol);
                            $('#total_currency_symbol').text(currencySymbol);
                            updateTotalHarga();
                        } else {
                            alert(response.message);
                            $('#harga_per_ton').val('');
                            $('#kode_penyedia').val(''); // Clear selection on error
                            $('#currency_symbol').text('Rp'); // Reset to default
                            $('#total_currency_symbol').text('Rp'); // Reset to default
                            updateTotalHarga();
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat mengambil data penyedia.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                        $('#harga_per_ton').val('');
                        $('#kode_penyedia').val(''); // Clear selection on error
                        $('#currency_symbol').text('Rp'); // Reset to default
                        $('#total_currency_symbol').text('Rp'); // Reset to default
                        updateTotalHarga();
                    }
                });
            } else {
                $('#harga_per_ton').val('');
                $('#currency_symbol').text('Rp'); // Reset to default
                $('#total_currency_symbol').text('Rp'); // Reset to default
                updateTotalHarga();
            }
        });

        // Event listener for harga_per_ton input change
        $('#harga_per_ton').on('input', updateTotalHarga);

        // Initial calculation if values are pre-filled (e.g., after validation error)
        updateTotalHarga();
    });
</script>
@endpush