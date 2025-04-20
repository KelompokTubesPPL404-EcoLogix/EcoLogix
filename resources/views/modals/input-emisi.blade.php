<!-- File: resources/views/modals/input-emisi.blade.php -->
<div id="emisiModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black opacity-40 transition-opacity" id="modalOverlay"></div>
        
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg mx-auto z-10">
            <!-- Modal header -->
            <div class="flex justify-end p-4">
                <button type="button" class="text-gray-400 hover:text-gray-500" id="closeModal">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Modal body -->
            <div class="px-6 pb-6">
                <form id="emisiForm">
                    <!-- Tanggal -->
                    <div class="mb-4">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" class="w-full py-3 px-4 bg-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <!-- Kategori emisi -->
                    <div class="mb-4">
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori emisi</label>
                        <select id="kategori" name="kategori" class="w-full py-3 px-4 bg-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="" disabled selected>Masukkan Kategori</option>
                            <option value="sampah">Sampah</option>
                            <option value="transportasi">Transportasi</option>
                            <option value="energi">Energi</option>
                            <option value="air">Air</option>
                        </select>
                    </div>
                    
                    <!-- Sub kategori -->
                    <div class="mb-4">
                        <label for="sub_kategori" class="block text-sm font-medium text-gray-700 mb-1">Sub kategori</label>
                        <select id="sub_kategori" name="sub_kategori" class="w-full py-3 px-4 bg-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="" disabled selected>Masukkan sub kategori</option>
                            <!-- Options will be populated by JavaScript based on selected kategori -->
                        </select>
                    </div>
                    
                    <!-- Nilai Aktivitas -->
                    <div class="mb-4">
                        <label for="nilai_aktivitas" class="block text-sm font-medium text-gray-700 mb-1">Nilai Aktivitas</label>
                        <input type="number" step="0.01" id="nilai_aktivitas" name="nilai_aktivitas" placeholder="Masukkan sub kategori" class="w-full py-3 px-4 bg-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <!-- Deskripsi -->
                    <div class="mb-6">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" class="w-full py-3 px-4 bg-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                    </div>
                    
                    <!-- Submit button -->
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-md transition-colors">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal elements
        const modal = document.getElementById('emisiModal');
        const closeBtn = document.getElementById('closeModal');
        const modalOverlay = document.getElementById('modalOverlay');
        const openModalBtn = document.querySelector('button.bg-green-600'); // The "Input Emisi Karbon" button
        
        // Form elements
        const kategoriSelect = document.getElementById('kategori');
        const subKategoriSelect = document.getElementById('sub_kategori');
        const emisiForm = document.getElementById('emisiForm');
        
        // Sub kategori options based on selected kategori
        const subKategoriOptions = {
            'sampah': ['limbah', 'daur ulang', 'kompos'],
            'transportasi': ['mobil', 'motor', 'pesawat', 'kereta'],
            'energi': ['listrik', 'gas', 'bahan bakar'],
            'air': ['pemakaian', 'pengolahan']
        };
        
        // Open modal function
        function openModal() {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        }
        
        // Close modal function
        function closeModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        }
        
        // Populate sub kategori dropdown based on selected kategori
        function updateSubKategori() {
            const selectedKategori = kategoriSelect.value;
            
            // Clear current options
            subKategoriSelect.innerHTML = '<option value="" disabled selected>Masukkan sub kategori</option>';
            
            // Add new options based on selected kategori
            if (selectedKategori in subKategoriOptions) {
                subKategoriOptions[selectedKategori].forEach(subKat => {
                    const option = document.createElement('option');
                    option.value = subKat;
                    option.textContent = subKat;
                    subKategoriSelect.appendChild(option);
                });
            }
        }
        
        // Form submission handler
        function handleFormSubmit(e) {
            e.preventDefault();
            
            const formData = {
                id: document.querySelector('.edit-btn.active')?.getAttribute('data-id'),
                tanggal: document.getElementById('tanggal').value,
                kategori: document.getElementById('kategori').value,
                sub_kategori: document.getElementById('sub_kategori').value,
                nilai_aktivitas: document.getElementById('nilai_aktivitas').value,
                deskripsi: document.getElementById('deskripsi').value
            };
            
            if (formData.id) {
                console.log('Update data dengan ID:', formData.id, 'Data:', formData);
                alert('Data emisi karbon berhasil diupdate!');
            } else {
                console.log('Buat data baru:', formData);
                alert('Data emisi karbon berhasil disubmit!');
            }
            
            closeModal();
        }
        
        // Event listeners
        if (openModalBtn) {
            openModalBtn.addEventListener('click', openModal);
        }
        
        closeBtn.addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', closeModal);
        
        kategoriSelect.addEventListener('change', updateSubKategori);
        emisiForm.addEventListener('submit', handleFormSubmit);
        
        // Close modal when Escape key is pressed
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });
</script>