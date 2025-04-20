<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\FaktorEmisi;

class FaktorEmisiControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test mendapatkan daftar faktor emisi.
     */
    public function test_index_displays_faktor_emisi_list(): void
    {
        FaktorEmisi::factory()->count(2)->create();
        $response = $this->get('/faktor-emisi');
        $response->assertStatus(200);
    }

    /**
     * Test menampilkan form create faktor emisi.
     */
    public function test_create_displays_form(): void
    {
        $response = $this->get('/faktor-emisi/create');
        $response->assertStatus(200);
    }

    /**
     * Test menyimpan faktor emisi baru.
     */
    public function test_store_creates_new_faktor_emisi(): void
    {
        $faktorEmisiData = [
            'kategori_emisi_karbon' => $this->faker->word,
            'sub_kategori' => $this->faker->word,
            'nilai_faktor' => $this->faker->randomFloat(2, 0, 100),
            'satuan' => $this->faker->word
        ];

        $response = $this->post('/faktor-emisi', $faktorEmisiData);
        $response->assertRedirect('/faktor-emisi');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('faktor_emisi', [
            'kategori_emisi_karbon' => $faktorEmisiData['kategori_emisi_karbon'],
            'sub_kategori' => $faktorEmisiData['sub_kategori'],
        ]);
    }

    /**
     * Test menampilkan detail faktor emisi.
     */
    public function test_show_displays_faktor_emisi_details(): void
    {
        $faktorEmisi = FaktorEmisi::factory()->create();
        $response = $this->get('/faktor-emisi/' . $faktorEmisi->kode_faktor);
        $response->assertStatus(200);
    }

    /**
     * Test menampilkan form edit faktor emisi.
     */
    public function test_edit_displays_edit_form(): void
    {
        $faktorEmisi = FaktorEmisi::factory()->create();
        $response = $this->get('/faktor-emisi/' . $faktorEmisi->kode_faktor . '/edit');
        $response->assertStatus(200);
    }

    /**
     * Test mengupdate faktor emisi.
     */
    public function test_update_modifies_faktor_emisi(): void
    {
        $faktorEmisi = FaktorEmisi::factory()->create();
        $updatedData = [
            'kategori_emisi_karbon' => $this->faker->word,
            'sub_kategori' => $this->faker->word,
            'nilai_faktor' => $this->faker->randomFloat(2, 0, 100),
            'satuan' => $this->faker->word
        ];

        $response = $this->put('/faktor-emisi/' . $faktorEmisi->kode_faktor, $updatedData);
        $response->assertRedirect('/faktor-emisi');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('faktor_emisi', [
            'kode_faktor' => $faktorEmisi->kode_faktor,
            'kategori_emisi_karbon' => $updatedData['kategori_emisi_karbon'],
        ]);
    }

    /**
     * Test menghapus faktor emisi.
     */
    public function test_destroy_deletes_faktor_emisi(): void
    {
        $faktorEmisi = FaktorEmisi::factory()->create();
        $response = $this->delete('/faktor-emisi/' . $faktorEmisi->kode_faktor);
        $response->assertRedirect('/faktor-emisi');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('faktor_emisi', [
            'kode_faktor' => $faktorEmisi->kode_faktor
        ]);
    }
}
