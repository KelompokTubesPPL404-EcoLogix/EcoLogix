<?php

namespace Tests\Feature;

use App\Models\EmisiCarbon;
use App\Models\Staff;
use App\Models\FaktorEmisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

class EmisiCarbonControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_emisi_carbon()
    {
        EmisiCarbon::factory()->count(3)->create();

        $response = $this->getJson('/api/emisi-carbon');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'nama_kegiatan', 'jumlah', 'satuan', 'emisi', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    /** @test */
    public function it_can_create_an_emisi_carbon()
    {
        $staff = Staff::factory()->create(); 
        
        $kategori = 'Energi';
        $subKategori = 'Listrik';

        // Tambahkan faktor emisi dummy agar query berhasil
        FaktorEmisi::create([
            'kode_faktor' => 'FE-AB193',
            'kategori_emisi_karbon' => $kategori,
            'sub_kategori' => $subKategori,
            'nilai_faktor' => 0.85,
            'satuan' => 'kg CO2e/kWh',
        ]);

        $data = [
            'tanggal_emisi' => now()->toDateString(),
            'kategori_emisi_karbon' => $kategori,
            'sub_kategori' => $subKategori,
            'nilai_aktivitas' => 50,
            'deskripsi' => 'Contoh deskripsi',
            'kode_staff' => $staff->kode_staff, 
        ];

        $response = $this->postJson('/api/emisi-carbon', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('emisi_carbon', [
            'kategori_emisi_karbon' => $kategori,
            'sub_kategori' => $subKategori,
            'nilai_aktivitas' => 50,
            'deskripsi' => 'Contoh deskripsi',
            'kode_faktor_emisi' => 'FE-AB193',
        ]);
    }

    /** @test */
    public function it_can_show_an_emisi_carbon()
    {
        // Membuat data EmisiCarbon
        $emisi = EmisiCarbon::factory()->create();

        // Menyusun URL dengan ID yang benar
        $response = $this->getJson("/api/emisi-carbon/{$emisi->kode_emisi_carbon}");

        // Memastikan status 200 dan memeriksa apakah data emisi ada dalam respons
        $response->assertStatus(200)
                ->assertJsonFragment([
                    'kode_emisi_carbon' => $emisi->kode_emisi_carbon, // Pastikan ID ada dalam respons
                    'kategori_emisi_karbon' => $emisi->kategori_emisi_karbon, // Pastikan kategori sesuai
                    'sub_kategori' => $emisi->sub_kategori, // Pastikan sub kategori sesuai
                ]);
    }

    /** @test */
    public function it_can_update_an_emisi_carbon()
    {
        // Membuat data staf
        $staff = Staff::factory()->create();

        // Membuat faktor emisi yang sesuai dengan kategori dan sub kategori
        $kategori = 'Energi';
        $subKategori = 'Listrik';
        FaktorEmisi::create([
            'kode_faktor' => 'FE-AB193',
            'kategori_emisi_karbon' => $kategori,
            'sub_kategori' => $subKategori,
            'nilai_faktor' => 0.85,
            'satuan' => 'kg CO2e/kWh',
        ]);

        // Membuat data EmisiCarbon
        $emisi = EmisiCarbon::factory()->create([
            'kode_staff' => $staff->kode_staff,
            'kategori_emisi_karbon' => $kategori,
            'sub_kategori' => $subKategori,
        ]);

        // Data update
        $updatedData = [
            'tanggal_emisi' => now()->toDateString(),
            'kategori_emisi_karbon' => 'Energi', // Sesuaikan dengan kategori dan subkategori yang valid
            'sub_kategori' => 'Listrik',
            'nilai_aktivitas' => 150.0,
            'deskripsi' => 'Pengujian update',
            'kode_staff' => $staff->kode_staff, // Pastikan kode_staff sesuai
        ];

        // Mengirim permintaan update
        $response = $this->putJson("/api/emisi-carbon/{$emisi->kode_emisi_carbon}", $updatedData);

        // Memastikan status 200 dan respons json fragment sesuai
        $response->assertStatus(200)
                ->assertJsonFragment($updatedData);

        // Memastikan data diperbarui di database
        $this->assertDatabaseHas('emisi_carbon', $updatedData);
    }

    /** @test */
    public function it_can_delete_an_emisi_carbon()
    {
        // Membuat data staf
        $staff = Staff::factory()->create();

        // Membuat faktor emisi yang sesuai dengan kategori dan sub kategori
        $kategori = 'Energi';
        $subKategori = 'Listrik';
        FaktorEmisi::create([
            'kode_faktor' => 'FE-AB193',
            'kategori_emisi_karbon' => $kategori,
            'sub_kategori' => $subKategori,
            'nilai_faktor' => 0.85,
            'satuan' => 'kg CO2e/kWh',
        ]);

        // Membuat data EmisiCarbon
        $emisi = EmisiCarbon::factory()->create([
            'kode_staff' => $staff->kode_staff,
            'kategori_emisi_karbon' => $kategori,
            'sub_kategori' => $subKategori,
        ]);

        // Call the destroy route to delete the EmisiCarbon record
        $response = $this->deleteJson('/api/emisi-carbon/' . $emisi->kode_emisi_carbon);

        // Assert that the response is successful with the correct message
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Faktor Emisi berhasil dihapus!',
        ]);

        // Assert that the record is removed from the database
        $this->assertDatabaseMissing('emisi_carbon', [
            'kode_emisi_carbon' => $emisi->kode_emisi_carbon
        ]);
    }
} 
