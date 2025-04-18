<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Perusahaan;
use App\Models\SuperAdmin;

class PerusahaanControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test mendapatkan daftar perusahaan.
     */
    public function test_index_displays_perusahaan_list(): void
    {
        Perusahaan::factory()->count(2)->create();
        $response = $this->get('/perusahaan');
        $response->assertStatus(200);
    }

    /**
     * Test menampilkan form create perusahaan.
     */
    public function test_create_displays_form(): void
    {
        // Buat super admin terlebih dahulu karena dibutuhkan di form create
        SuperAdmin::factory()->create();
        $response = $this->get('/perusahaan/create');
        $response->assertStatus(200);
    }

    /**
     * Test menyimpan perusahaan baru.
     */
    public function test_store_creates_new_perusahaan(): void
    {
        $superAdmin = SuperAdmin::factory()->create();
        
        $perusahaanData = [
            'nama_perusahaan' => $this->faker->company,
            'alamat_perusahaan' => $this->faker->address,
            'no_telp_perusahaan' => $this->faker->phoneNumber,
            'email_perusahaan' => $this->faker->unique()->companyEmail,
            'password_perusahaan' => 'password123',
            'kode_super_admin' => $superAdmin->kode_super_admin,
        ];

        $response = $this->post('/perusahaan', $perusahaanData);
        $response->assertRedirect('/perusahaan');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('perusahaan', [
            'nama_perusahaan' => $perusahaanData['nama_perusahaan'],
            'email_perusahaan' => $perusahaanData['email_perusahaan'],
        ]);
    }

    /**
     * Test menampilkan detail perusahaan.
     */
    public function test_show_displays_perusahaan_details(): void
    {
        $perusahaan = Perusahaan::factory()->create();
        $response = $this->get('/perusahaan/' . $perusahaan->kode_perusahaan);
        $response->assertStatus(200);
    }

    /**
     * Test menampilkan form edit perusahaan.
     */
    public function test_edit_displays_edit_form(): void
    {
        $perusahaan = Perusahaan::factory()->create();
        $response = $this->get('/perusahaan/' . $perusahaan->kode_perusahaan . '/edit');
        $response->assertStatus(200);
    }

    /**
     * Test mengupdate perusahaan.
     */
    public function test_update_modifies_perusahaan(): void
    {
        $perusahaan = Perusahaan::factory()->create();
        $updatedData = [
            'nama_perusahaan' => $this->faker->company,
            'alamat_perusahaan' => $this->faker->address,
            'no_telp_perusahaan' => $this->faker->phoneNumber,
            'email_perusahaan' => $this->faker->unique()->companyEmail,
            'kode_super_admin' => $perusahaan->kode_super_admin,
        ];

        $response = $this->put('/perusahaan/' . $perusahaan->kode_perusahaan, $updatedData);
        $response->assertRedirect('/perusahaan');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('perusahaan', [
            'kode_perusahaan' => $perusahaan->kode_perusahaan,
            'nama_perusahaan' => $updatedData['nama_perusahaan'],
        ]);
    }

    /**
     * Test menghapus perusahaan.
     */
    public function test_destroy_deletes_perusahaan(): void
    {
        $perusahaan = Perusahaan::factory()->create();
        $response = $this->delete('/perusahaan/' . $perusahaan->kode_perusahaan);
        $response->assertRedirect('/perusahaan');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('perusahaan', [
            'kode_perusahaan' => $perusahaan->kode_perusahaan
        ]);
    }
}