<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\FaktorEmisi;
use App\Models\EmisiCarbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmisiCarbonControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Siapkan user login
        $this->user = \App\Models\Pengguna::factory()->create();
        $this->actingAs($this->user, 'pengguna');

        // Faktor Emisi dummy
        FaktorEmisi::create([
            'kategori_emisi_karbon' => 'Transportasi',
            'sub_kategori' => 'Mobil',
            'nilai_faktor' => 2.5,
            'satuan' => 'liter'
        ]);
    }

    public function test_index_returns_ok()
    {
        $response = $this->get(route('emisicarbon.index'));
        $response->assertStatus(200);
    }

    public function test_create_returns_ok()
    {
        $response = $this->get(route('emisicarbon.create'));
        $response->assertStatus(200);
    }

    public function test_store_emisi_carbon()
    {
        $response = $this->post(route('emisicarbon.store'), [
            'tanggal_emisi' => now()->toDateString(),
            'kategori_emisi_karbon' => 'Transportasi',
            'sub_kategori' => 'Mobil',
            'nilai_aktivitas' => 10,
            'deskripsi' => 'Perjalanan kantor'
        ]);

        $response->assertRedirect(route('emisicarbon.index'));
        $this->assertDatabaseHas('emisi_carbons', [
            'kategori_emisi_karbon' => 'Transportasi',
            'sub_kategori' => 'Mobil',
            'nilai_aktivitas' => 10,
            'status' => 'pending'
        ]);
    }

    public function test_edit_page_loads()
    {
        $emisi = EmisiCarbon::factory()->create([
            'kode_user' => $this->user->kode_user
        ]);

        $response = $this->get(route('emisicarbon.edit', $emisi->kode_emisi_karbon));
        $response->assertStatus(200);
    }

    public function test_update_emisi_carbon()
    {
        $emisi = EmisiCarbon::factory()->create([
            'kode_user' => $this->user->kode_user,
            'kategori_emisi_karbon' => 'Transportasi',
            'sub_kategori' => 'Mobil'
        ]);

        $response = $this->put(route('emisicarbon.update', $emisi->kode_emisi_karbon), [
            'tanggal_emisi' => now()->toDateString(),
            'kategori_emisi_karbon' => 'Transportasi',
            'sub_kategori' => 'Mobil',
            'nilai_aktivitas' => 5,
            'deskripsi' => 'Update data'
        ]);

        $response->assertRedirect(route('emisicarbon.index'));
        $this->assertDatabaseHas('emisi_carbons', ['nilai_aktivitas' => 5]);
    }

    public function test_destroy_emisi_carbon()
    {
        $emisi = EmisiCarbon::factory()->create([
            'kode_user' => $this->user->kode_user
        ]);

        $response = $this->delete(route('emisicarbon.destroy', $emisi->kode_emisi_karbon));
        $response->assertRedirect(route('emisicarbon.index'));
        $this->assertDatabaseMissing('emisi_carbons', ['kode_emisi_karbon' => $emisi->kode_emisi_karbon]);
    }

    // Admin-only functions (gunakan guard admin)
    public function test_admin_index_report()
    {
        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.emissions.index'));
        $response->assertStatus(200);
    }

    public function test_update_status()
    {
        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $emisi = EmisiCarbon::factory()->create([
            'status' => 'pending'
        ]);

        $response = $this->post(route('emisicarbon.updateStatus', $emisi->kode_emisi_karbon), [
            'status' => 'approved'
        ]);

        $response->assertRedirect(route('admin.emissions.index'));
        $this->assertDatabaseHas('emisi_carbons', ['status' => 'approved']);
    }

    public function test_download_report()
    {
        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        EmisiCarbon::factory()->create([
            'status' => 'approved'
        ]);

        $response = $this->get(route('emisicarbon.downloadReport'));
        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}
