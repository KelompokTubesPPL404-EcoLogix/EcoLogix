@extends('layouts.staff')

@section('title', 'Edit Data Emisi Karbon')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Data Emisi Karbon</h1>
        <a href="{{ route('staff.emisicarbon.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">Form Edit Emisi Karbon</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.emisicarbon.update', $emisicarbon->kode_emisi_carbon) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_emisi">Tanggal Emisi</label>
                            <input type="date" class="form-control @error('tanggal_emisi') is-invalid @enderror" id="tanggal_emisi" name="tanggal_emisi" value="{{ old('tanggal_emisi', $emisicarbon->tanggal_emisi) }}" required>
                            @error('tanggal_emisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kategori_emisi_karbon">Kategori Emisi</label>
                            <select class="form-select @error('kategori_emisi_karbon') is-invalid @enderror" id="kategori_emisi_karbon" name="kategori_emisi_karbon" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoriEmisi as $kategori => $faktorList)
                                <option value="{{ $kategori }}" {{ old('kategori_emisi_karbon', $emisicarbon->kategori_emisi_karbon) == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                                @endforeach
                            </select>
                            @error('kategori_emisi_karbon')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sub_kategori">Sub Kategori</label>
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
                            @error('sub_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nilai_aktivitas">Nilai Aktivitas</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control @error('nilai_aktivitas') is-invalid @enderror" id="nilai_aktivitas" name="nilai_aktivitas" value="{{ old('nilai_aktivitas', $emisicarbon->nilai_aktivitas) }}" placeholder="0.00" required>
                                <span class="input-group-text" id="satuan_aktivitas">
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
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estimasi_emisi">Estimasi Emisi Karbon</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="estimasi_emisi" readonly value="{{ $emisicarbon->konversi && isset($emisicarbon->konversi['hasil_konversi']) ? number_format($emisicarbon->konversi['hasil_konversi'], 2, '.', '') : ($emisicarbon->faktorEmisi && is_numeric($emisicarbon->nilai_aktivitas) && is_numeric($emisicarbon->faktorEmisi->nilai_faktor) ? number_format($emisicarbon->nilai_aktivitas * $emisicarbon->faktorEmisi->nilai_faktor, 2, '.', '') : '0.00') }}">
                                <span class="input-group-text">kg CO₂e</span>
                            </div>
                            <small class="text-muted">Estimasi berdasarkan faktor emisi yang dipilih</small>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="deskripsi">Deskripsi / Keterangan</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi', $emisicarbon->deskripsi) }}</textarea>
                    @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                        <!-- Hidden input untuk kode_faktor_emisi -->
                <input type="hidden" id="kode_faktor_emisi" name="kode_faktor_emisi" value="{{ old('kode_faktor_emisi', $emisicarbon->kode_faktor_emisi) }}">
                @error('kode_faktor_emisi')
                <div class="text-danger small">{{ $message }}</div>
                @enderror

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-light me-md-2">Reset</button>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
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
