<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Manager;
use App\Models\Pengguna; // Pastikan namespace ini benar
use App\Models\KompensasiEmisi;
use App\Models\PembelianCarbonCredit;
use App\Models\PenyediaCarbonCredit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

// Kelas ini secara keseluruhan menguji fungsionalitas yang terkait
// dengan Pembelian Carbon Credit melalui HTTP requests, yang berarti
// menguji controller yang menangani route-route terkait.
class PembelianCarbonCreditTest extends TestCase // Nama bisa diubah agar lebih eksplisit
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Memastikan storage di-mock untuk test yang melibatkan upload file
        Storage::fake('public');
    }

    /**
     * Menguji Controller (misal: Admin\CarbonCreditController@index)
     * - Memastikan Admin bisa mengakses halaman daftar pembelian carbon credit.
     * - Controller harus mengembalikan response OK (200).
     * - Controller harus merender view 'carbon_credit.index'.
     */
    public function test_admin_can_view_carbon_credit_list()
    {
        $admin = Admin::factory()->create();

        // Bertindak sebagai admin dan mengirim GET request
        $response = $this->actingAs($admin, 'admin')
                         ->get('/admin/carbon_credit'); // Route yang ditangani controller

        // Assertions pada response yang dikembalikan controller
        $response->assertStatus(200);
        $response->assertViewIs('carbon_credit.index'); // Memastikan view yang benar dirender
    }

    /**
     * Menguji Controller (misal: Admin\CarbonCreditController@store)
     * - Memastikan Admin bisa membuat data pembelian baru.
     * - Controller harus memvalidasi data request (meskipun tidak dites secara eksplisit di sini).
     * - Controller harus menyimpan data ke tabel 'pembelian_carbon_credits'.
     * - Controller harus mengupdate status di tabel 'kompensasi_emisi'.
     * - Controller harus menyimpan file bukti pembelian.
     * - Controller harus mengembalikan redirect ke halaman index.
     * - Controller harus menyertakan pesan sukses di session.
     */
    public function test_admin_can_create_carbon_credit_purchase()
    {
        $admin = Admin::factory()->create();
        $penyedia = PenyediaCarbonCredit::factory()->create();
        $kompensasi = KompensasiEmisi::factory()->create([
            'status' => 'pending' // Status awal
        ]);
        $buktiFile = UploadedFile::fake()->image('bukti.jpg');

        // Bertindak sebagai admin dan mengirim POST request dengan data
        $response = $this->actingAs($admin, 'admin')
                         ->post('/admin/carbon_credit', [ // Route yang ditangani controller
                            'kode_kompensasi' => $kompensasi->kode_kompensasi,
                            'kode_penyedia' => $penyedia->kode_penyedia,
                            'jumlah_kompensasi' => 100,
                            'harga_per_ton' => 50000,
                            'total_harga' => 5000000,
                            'tanggal_pembelian_carbon_credit' => '2024-01-20',
                            'deskripsi' => 'Test pembelian carbon credit',
                            'bukti_pembelian' => $buktiFile
                         ]);

        // Assertions pada response controller
        $response->assertRedirect('/admin/carbon_credit');
        $response->assertSessionHas('success');

        // Assertions pada state database setelah controller berjalan
        $pembelian = PembelianCarbonCredit::where('kode_kompensasi', $kompensasi->kode_kompensasi)->first();
        $this->assertNotNull($pembelian); // Pastikan data pembelian dibuat
        $this->assertEquals(100, $pembelian->jumlah_kompensasi);
        $this->assertEquals(5000000, $pembelian->total_harga);
        // Cek apakah file tersimpan (path bisa bervariasi tergantung implementasi controller)
        Storage::disk('public')->assertExists($pembelian->bukti_pembelian);

        $this->assertDatabaseHas('kompensasi_emisi', [
            'kode_kompensasi' => $kompensasi->kode_kompensasi,
            'status' => 'completed' // Verifikasi status telah diupdate oleh controller
        ]);
    }

    /**
     * Menguji Controller (misal: Admin\CarbonCreditController@editStatus)
     * - Memastikan Admin bisa mengakses halaman edit status pembelian.
     * - Controller harus mengambil data pembelian yang benar berdasarkan ID.
     * - Controller harus mengembalikan response OK (200).
     * - Controller harus merender view 'carbon_credit.edit_status'.
     * - Controller harus melewatkan data pembelian ke view.
     */
    public function test_admin_can_edit_carbon_credit_status()
    {
        $admin = Admin::factory()->create();
        $pembelian = PembelianCarbonCredit::factory()->create([
            'kode_admin' => $admin->kode_admin // Pastikan relasi ini ada jika diperlukan controller
        ]);

        // Bertindak sebagai admin dan mengirim GET request ke route edit
        $response = $this->actingAs($admin, 'admin')
                         ->get("/admin/carbon_credit/{$pembelian->kode_pembelian_carbon_credit}/edit-status"); // Route dinamis

        // Assertions pada response controller
        $response->assertStatus(200);
        $response->assertViewIs('carbon_credit.edit_status');
        $response->assertViewHas('pembelian', $pembelian); // Pastikan data dilewatkan ke view
    }

    /**
     * Menguji Controller (misal: Admin\CarbonCreditController@updateStatus)
     * - Memastikan Admin bisa mengupdate status pembelian.
     * - Controller harus memvalidasi data request (misal: status harus valid).
     * - Controller harus mengupdate status di tabel 'pembelian_carbon_credits'.
     * - Controller harus mengembalikan redirect ke halaman index.
     * - Controller harus menyertakan pesan sukses di session.
     */
    public function test_admin_can_update_carbon_credit_status()
    {
        $admin = Admin::factory()->create();
        $pembelian = PembelianCarbonCredit::factory()->create([
            'kode_admin' => $admin->kode_admin, // Pastikan relasi ini ada jika diperlukan controller
            'status' => 'pending' // Status awal
        ]);

        // Bertindak sebagai admin dan mengirim PUT request untuk update
        $response = $this->actingAs($admin, 'admin')
                         ->put("/admin/carbon_credit/{$pembelian->kode_pembelian_carbon_credit}/update-status", [ // Route dinamis
                            'status' => 'approved' // Data baru
                         ]);

        // Assertions pada response controller
        $response->assertRedirect('/admin/carbon_credit');
        $response->assertSessionHas('success');

        // Assertions pada state database setelah controller berjalan
        $this->assertDatabaseHas('pembelian_carbon_credits', [
            'kode_pembelian_carbon_credit' => $pembelian->kode_pembelian_carbon_credit,
            'status' => 'approved' // Verifikasi status telah diupdate
        ]);
    }

    /**
     * Menguji Controller (misal: User\CarbonCreditController@myPurchases)
     * - Memastikan Pengguna (user) bisa melihat daftar pembelian miliknya.
     * - Controller harus mengambil data pembelian HANYA untuk pengguna yang login.
     * - Controller harus mengembalikan response OK (200).
     * - Controller harus merender view 'carbon_credit.my_purchases'.
     * - Controller harus melewatkan data pembelian pengguna ke view.
     */
    public function test_pengguna_can_view_own_carbon_credit_purchases()
    {
        $pengguna = Pengguna::factory()->create();
        // Buat pembelian milik pengguna ini
        $pembelianMilikPengguna = PembelianCarbonCredit::factory()->create([
            'kode_user' => $pengguna->kode_user
        ]);
        // Buat pembelian milik pengguna lain (untuk memastikan tidak tertampil)
        $pembelianLain = PembelianCarbonCredit::factory()->create();

        // Bertindak sebagai pengguna dan mengirim GET request
        $response = $this->actingAs($pengguna, 'pengguna') // Gunakan guard 'pengguna'
                         ->get('/user/carbon_credit/my-purchases'); // Route user

        // Assertions pada response controller
        $response->assertStatus(200);
        $response->assertViewIs('carbon_credit.my_purchases');
        $response->assertSee($pembelianMilikPengguna->kode_pembelian_carbon_credit); // Pastikan data pengguna ada
        $response->assertDontSee($pembelianLain->kode_pembelian_carbon_credit); // Pastikan data lain tidak ada
    }

    /**
     * Menguji Controller (misal: Manager\ReportController@viewCarbonCreditReports)
     * - Memastikan Manager bisa mengakses halaman laporan pembelian carbon credit.
     * - Controller harus mengembalikan response OK (200).
     * - Controller harus merender view 'carbon_credit.reports'.
     * - Controller mungkin perlu melewatkan data agregat atau filter ke view (tidak dites di sini).
     */
    public function test_manager_can_view_carbon_credit_reports()
    {
        $manager = Manager::factory()->create();
        // Buat beberapa data pembelian sebagai contoh
        PembelianCarbonCredit::factory()->count(5)->create();

        // Bertindak sebagai manager dan mengirim GET request
        $response = $this->actingAs($manager, 'manager') // Gunakan guard 'manager'
                         ->get('/manager/carbon_credit/reports'); // Route manager

        // Assertions pada response controller
        $response->assertStatus(200);
        $response->assertViewIs('carbon_credit.reports');
    }

    /**
     * Menguji Controller (misal: Manager\ReportController@downloadCarbonCreditReport)
     * - Memastikan Manager bisa mendownload laporan (misal: PDF).
     * - Controller harus memproses data berdasarkan filter tanggal (start_date, end_date).
     * - Controller harus menghasilkan file (misal: PDF).
     * - Controller harus mengembalikan response OK (200).
     * - Controller harus menyetel header Content-Type yang sesuai (misal: application/pdf).
     * - Controller harus menyetel header Content-Disposition (biasanya, tidak dites secara eksplisit).
     */
    public function test_manager_can_download_selected_report()
    {
        $manager = Manager::factory()->create();
        // Buat data dalam rentang tanggal dan di luar rentang
        PembelianCarbonCredit::factory()->create(['tanggal_pembelian_carbon_credit' => '2024-01-15']);
        PembelianCarbonCredit::factory()->create(['tanggal_pembelian_carbon_credit' => '2024-02-10']);

        // Bertindak sebagai manager dan mengirim GET request dengan query parameters
        $response = $this->actingAs($manager, 'manager') // Gunakan guard 'manager'
                         ->get('/manager/carbon_credit/report', [ // Route download dengan filter
                            'start_date' => '2024-01-01',
                            'end_date' => '2024-01-31'
                         ]);

        // Assertions pada response controller
        $response->assertStatus(200);
        // Asumsi controller menghasilkan PDF
        $response->assertHeader('Content-Type', 'application/pdf');
        // Anda bisa menambahkan assertion lain jika perlu, misal memeriksa
        // header Content-Disposition jika controller mengaturnya.
        // $response->assertHeader('Content-Disposition', 'attachment; filename="report.pdf"');
    }
}