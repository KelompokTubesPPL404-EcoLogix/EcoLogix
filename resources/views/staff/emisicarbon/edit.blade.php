@extends('layouts.staff')

@section('title', 'Edit Data Emisi Karbon')

@push('css')
    <style>
        .eco-gradient {
            background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%);
        }
        .eco-card {
            border-left: 4px solid #28a745;
            transition: all 0.3s ease;
        }
        .eco-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15);
        }
        .eco-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 3px solid #28a745;
        }
        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        }
        .input-icon-group {
            position: relative;
        }
        .input-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #28a745;
        }
        .input-with-icon {
            padding-left: 35px;
        }
        .btn-eco-primary {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-eco-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Enhanced Eco Header Section -->
    <div class="eco-header p-4 rounded-3 mb-4">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-2 text-success fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>Edit Data Emisi Karbon
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>Perbarui data emisi karbon yang telah diinput sebelumnya
                </p>
            </div>
            <div class="d-flex align-items-center">
                <a href="{{ route('staff.emisicarbon.index') }}" class="btn btn-outline-secondary shadow-sm px-4">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="eco-card card border-0 shadow-lg mb-4 rounded-3 overflow-hidden">
        <div class="card-header eco-gradient text py-3" style="color: #198754;">
            <h6 class="m-0 fw-bold">
                <i class="bi bi-pencil-fill me-2" style="color: #198754;"></i>Form Edit Emisi Karbon {{ $emisicarbon->kode_emisi_carbon }}
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('staff.emisicarbon.update', $emisicarbon->kode_emisi_carbon) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_emisi">
                                <i class="bi bi-calendar-date text-success me-1"></i>Tanggal Emisi
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-calendar3 text-success"></i>
                                </span>
                                <input type="date" class="form-control @error('tanggal_emisi') is-invalid @enderror" id="tanggal_emisi" name="tanggal_emisi" value="{{ old('tanggal_emisi', $emisicarbon->tanggal_emisi) }}" required>
                            </div>
                            @error('tanggal_emisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tanggal kejadian emisi karbon</small>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kategori_emisi_karbon">
                                <i class="bi bi-tags text-success me-1"></i>Kategori Emisi
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-tag text-success"></i>
                                </span>
                                <select class="form-select @error('kategori_emisi_karbon') is-invalid @enderror" id="kategori_emisi_karbon" name="kategori_emisi_karbon" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoriEmisi as $kategori => $faktorList)
                                    <option value="{{ $kategori }}" {{ old('kategori_emisi_karbon', $emisicarbon->kategori_emisi_karbon) == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('kategori_emisi_karbon')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pilih kategori utama emisi karbon</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sub_kategori">
                                <i class="bi bi-tag text-success me-1"></i>Sub Kategori
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-tags text-success"></i>
                                </span>
                                <select class="form-select @error('sub_kategori') is-invalid @enderror" id="sub_kategori" name="sub_kategori" required>
                                    <option value="">-- Pilih Sub Kategori --</option>
                                    @if(old('kategori_emisi_karbon', $emisicarbon->kategori_emisi_karbon))
                                        @foreach($kategoriEmisi[old('kategori_emisi_karbon', $emisicarbon->kategori_emisi_karbon)] as $faktor)
                                        <option value="{{ $faktor->sub_kategori }}" {{ old('sub_kategori', $emisicarbon->sub_kategori) == $faktor->sub_kategori ? 'selected' : '' }}>
                                            {{ $faktor->sub_kategori }} ({{ $faktor->satuan }})
                                        </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            @error('sub_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pilih sub kategori untuk detil lebih spesifik</small>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nilai_aktivitas">
                                <i class="bi bi-activity text-success me-1"></i>Nilai Aktivitas
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-calculator text-success"></i>
                                </span>
                                <input type="number" step="0.01" min="0" class="form-control @error('nilai_aktivitas') is-invalid @enderror" id="nilai_aktivitas" name="nilai_aktivitas" value="{{ old('nilai_aktivitas', $emisicarbon->nilai_aktivitas) }}" placeholder="0.00" required>
                                <span class="input-group-text bg-success text-white" id="satuan_aktivitas">
                                    @if($emisicarbon->faktorEmisi)
                                        {{ $emisicarbon->faktorEmisi->satuan }}
                                    @else
                                        Satuan
                                    @endif
                                </span>
                            </div>
                            @error('nilai_aktivitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Jumlah atau volume aktivitas yang menyebabkan emisi</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estimasi_emisi">
                                <i class="bi bi-graph-up-arrow text-success me-1"></i>Estimasi Emisi Karbon
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-cloud text-success"></i>
                                </span>
                                <input type="text" class="form-control bg-light" id="estimasi_emisi" readonly value="{{ $emisicarbon->konversi && isset($emisicarbon->konversi['hasil_konversi']) ? number_format($emisicarbon->konversi['hasil_konversi'], 2, '.', '') : ($emisicarbon->faktorEmisi && is_numeric($emisicarbon->nilai_aktivitas) && is_numeric($emisicarbon->faktorEmisi->nilai_faktor) ? number_format($emisicarbon->nilai_aktivitas * $emisicarbon->faktorEmisi->nilai_faktor, 2, '.', '') : '0.00') }}">
                                <span class="input-group-text bg-success text-white">kg CO₂e</span>
                            </div>
                            <small class="text-muted">Estimasi berdasarkan faktor emisi yang dipilih</small>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="deskripsi">
                        <i class="bi bi-card-text text-success me-1"></i>Deskripsi / Keterangan
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-chat-square-text text-success"></i>
                        </span>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi', $emisicarbon->deskripsi) }}</textarea>
                    </div>
                    @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Keterangan tambahan tentang aktivitas emisi ini</small>
                </div>

                <!-- Hidden input untuk kode_faktor_emisi -->
                <input type="hidden" id="kode_faktor_emisi" name="kode_faktor_emisi" value="{{ old('kode_faktor_emisi', $emisicarbon->kode_faktor_emisi) }}">
                @error('kode_faktor_emisi')
                <div class="text-danger small">{{ $message }}</div>
                @enderror

                <hr class="my-4">
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-outline-secondary px-4 me-md-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                    <button type="submit" class="btn btn-eco-primary px-4">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Data faktor emisi dari controller
    const kategoriEmisi = @json($kategoriEmisi);
    let faktorEmisiData = {};

    // Populate faktorEmisiData from JSON
    for (const kategori in kategoriEmisi) {
        kategoriEmisi[kategori].forEach(faktor => {
            faktorEmisiData[`${kategori}-${faktor.sub_kategori}`] = {
                nilai_faktor: faktor.nilai_faktor,
                satuan: faktor.satuan,
                kode_faktor: faktor.kode_faktor
            };
        });
    }
    
    // Pastikan kode_faktor_emisi terisi dengan benar saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const currentKategori = document.getElementById('kategori_emisi_karbon').value;
        const currentSubKategori = document.getElementById('sub_kategori').value;
        
        if (currentKategori && currentSubKategori) {
            updateSatuanAndEstimasi();
        }
    });

    // Ketika kategori berubah, update opsi sub kategori
    document.getElementById('kategori_emisi_karbon').addEventListener('change', function() {
        const kategori = this.value;
        const subKategoriSelect = document.getElementById('sub_kategori');
        
        // Reset sub kategori
        subKategoriSelect.innerHTML = '<option value="">-- Pilih Sub Kategori --</option>';
        
        if (kategori) {
            // Populate sub kategoris
            if (kategoriEmisi[kategori]) {
                kategoriEmisi[kategori].forEach(faktor => {
                    const option = document.createElement('option');
                    option.value = faktor.sub_kategori;
                    option.textContent = `${faktor.sub_kategori} (${faktor.satuan})`;
                    subKategoriSelect.appendChild(option);
                });
            }
        }
        
        // Reset satuan and estimasi
        updateSatuanAndEstimasi();
    });

    // Ketika sub kategori berubah, update satuan dan estimasi
    document.getElementById('sub_kategori').addEventListener('change', function() {
        updateSatuanAndEstimasi();
    });

    // Ketika nilai aktivitas berubah, update estimasi
    document.getElementById('nilai_aktivitas').addEventListener('input', function() {
        updateSatuanAndEstimasi();
    });

    function updateSatuanAndEstimasi() {
        const kategori = document.getElementById('kategori_emisi_karbon').value;
        const subKategori = document.getElementById('sub_kategori').value;
        const nilaiAktivitas = parseFloat(document.getElementById('nilai_aktivitas').value) || 0;
        
        const key = `${kategori}-${subKategori}`;
        const satuanElement = document.getElementById('satuan_aktivitas');
        const estimasiElement = document.getElementById('estimasi_emisi');
        const kodeFaktorEmisiInput = document.getElementById('kode_faktor_emisi');
        
        if (faktorEmisiData[key]) {
            const { nilai_faktor, satuan } = faktorEmisiData[key];
            satuanElement.textContent = satuan;
            
            // Calculate emission
            const estimasi = nilaiAktivitas * nilai_faktor;
            estimasiElement.value = estimasi.toFixed(2);
            
            // Update kode_faktor_emisi
            // Cari kode_faktor yang sesuai dengan kategori dan sub_kategori yang dipilih
            if (kategoriEmisi[kategori]) {
                const selectedFaktor = kategoriEmisi[kategori].find(f => f.sub_kategori === subKategori);
                if (selectedFaktor) {
                    kodeFaktorEmisiInput.value = selectedFaktor.kode_faktor;
                }
            }
        } else {
            satuanElement.textContent = 'Satuan';
            estimasiElement.value = '0.00';
        }
    }
</script>
@endsection
