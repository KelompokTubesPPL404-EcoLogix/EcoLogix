@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Ubah Status Emisi Karbon') }}</div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5>Detail Emisi Karbon</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Kode Emisi</th>
                                <td>{{ $emisicarbon->kode_emisi_carbon }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>{{ $emisicarbon->kategori_emisi_karbon }}</td>
                            </tr>
                            <tr>
                                <th>Sub Kategori</th>
                                <td>{{ $emisicarbon->sub_kategori }}</td>
                            </tr>
                            <tr>
                                <th>Nilai Aktivitas</th>
                                <td>{{ $emisicarbon->nilai_aktivitas }}</td>
                            </tr>
                            <tr>
                                <th>Faktor Emisi</th>
                                <td>{{ $emisicarbon->faktor_emisi }}</td>
                            </tr>
                            <tr>
                                <th>Kadar Emisi Karbon</th>
                                <td>{{ $emisicarbon->kadar_emisi_karbon }}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>{{ $emisicarbon->deskripsi }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Emisi</th>
                                <td>{{ $emisicarbon->tanggal_emisi->timezone('Asia/Jakarta')->format('d-m-Y') }} WIB</td>
                            </tr>
                            <tr>
                                <th>Staff</th>
                                <td>{{ $emisicarbon->staff->name ?? 'Tidak ada data' }}</td>
                            </tr>
                            <tr>
                                <th>Status Saat Ini</th>
                                <td>
                                    @if($emisicarbon->status == 'pending')
                                        <span class="badge bg-warning">Menunggu Persetujuan</span>
                                    @elseif($emisicarbon->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($emisicarbon->status == 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $emisicarbon->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <form method="POST" action="{{ route('admin.emisicarbon.updateStatus', $emisicarbon->kode_emisi_carbon) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="status">{{ __('Status Baru') }}</label>
                            <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                                <option value="pending" {{ $emisicarbon->status == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                                <option value="approved" {{ $emisicarbon->status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="rejected" {{ $emisicarbon->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>

                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="catatan">{{ __('Catatan (Opsional)') }}</label>
                            <textarea id="catatan" class="form-control @error('catatan') is-invalid @enderror" name="catatan" rows="3">{{ old('catatan') }}</textarea>

                            @error('catatan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Simpan Perubahan') }}
                            </button>
                            <a href="{{ route('admin.emisicarbon.index') }}" class="btn btn-secondary">
                                {{ __('Batal') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection