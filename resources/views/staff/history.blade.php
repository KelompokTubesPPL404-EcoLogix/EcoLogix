@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-xl shadow overflow-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">All Input Emission History</h2>
        <a href="{{ url('/dashboard') }}" class="text-green-600 text-sm flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Dashboard
        </a>
    </div>
    
    <table class="w-full text-sm">
        <thead class="text-left text-gray-600 border-b">
            <tr>
                <th class="pb-3">No</th>
                <th class="pb-3">Date</th>
                <th class="pb-3">Emision Category</th>
                <th class="pb-3">Sub Category</th>
                <th class="pb-3">Activity Score</th>
                <th class="pb-3">Emission Levels</th>
                <th class="pb-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 1; $i <= 20; $i++) <!-- Contoh data, nanti diganti dengan data dari database -->
            <tr class="border-b last:border-none hover:bg-gray-50">
                <td class="py-3">{{ $i }}</td>
                <td class="py-3">01/{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}/25</td>
                <td class="py-3">sampah</td>
                <td class="py-3">limbah</td>
                <td class="py-3">0.{{ str_pad($i, 2, '0', STR_PAD_LEFT) }} kg</td>
                <td class="py-3">0.{{ str_pad($i, 2, '0', STR_PAD_LEFT) }} kg Co₂e</td>
                <td class="py-3">
                    <div class="flex space-x-2">
                        <button class="text-blue-600 hover:text-blue-800 edit-btn" data-id="{{ $i }}" 
                                data-tanggal="2025-01-{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                data-kategori="sampah"
                                data-subkategori="limbah"
                                data-nilai="0.{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                data-deskripsi="Deskripsi contoh untuk data {{ $i }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                        </button>
                        <button class="text-red-600 hover:text-red-800 delete-btn" data-id="{{ $i }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            @endfor
        </tbody>
    </table>
</div>

<!-- Include Modal Edit (menggunakan modal yang sama dengan input) -->
@include('modals.input-emisi')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('emisiModal');
    const modalTitle = document.createElement('h3');
    modalTitle.className = 'text-lg font-semibold mb-4';
    modalTitle.textContent = 'Edit Emission Data';
    
    // Temukan form di modal
    const form = document.getElementById('emisiForm');
    
    // Sisipkan judul modal
    form.parentNode.insertBefore(modalTitle, form);
    
    // Handle edit button
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Isi form dengan data dari atribut data
            document.getElementById('tanggal').value = this.getAttribute('data-tanggal');
            document.getElementById('kategori').value = this.getAttribute('data-kategori');
            document.getElementById('nilai_aktivitas').value = this.getAttribute('data-nilai');
            document.getElementById('deskripsi').value = this.getAttribute('data-deskripsi');
            
            // Trigger change event untuk update sub kategori
            const kategoriSelect = document.getElementById('kategori');
            const event = new Event('change');
            kategoriSelect.dispatchEvent(event);
            
            // Set sub kategori setelah dropdown terupdate
            setTimeout(() => {
                document.getElementById('sub_kategori').value = this.getAttribute('data-subkategori');
            }, 100);
            
            // Tampilkan modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Ubah teks tombol submit
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Update Data';
        });
    });
    
    // Handle delete button
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if(confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                alert('Data dengan ID: ' + id + ' akan dihapus');
                // Di sini nanti bisa diisi dengan AJAX untuk hapus data
            }
        });
    });
});
</script>
@endsection