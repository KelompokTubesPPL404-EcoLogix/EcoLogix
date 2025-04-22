<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontendTest extends TestCase
{
    
public function test_routes_and_views()
{
    // Test root route (/) redirect ke dashboard
    $this->get('/')->assertStatus(200)->assertViewIs('dashboard');

    // Test route /dashboard
    $this->get('/dashboard')->assertStatus(200)->assertViewIs('dashboard');

    // Test route /history
    $this->get('/history')->assertStatus(200)->assertViewIs('history');
}

/** @test */
public function test_view_contents()
{
    // Dashboard harus mengandung elemen penting
    $response = $this->get('/dashboard');
    $response->assertSee('Emission Carbon');
    $response->assertSee('Input Emisi Karbon');
    $response->assertSee('Total Carbon Emision');

    // History harus mengandung tabel
    $response = $this->get('/history');
    $response->assertSee('All Input Emission History');
    $response->assertSee('No');
    $response->assertSee('Date');
}

/** @test */
public function test_modal_form_elements()
{
    $response = $this->get('/dashboard');

    // Pastikan modal dan form fields ada
    $response->assertSee('id="emisiModal"', false); // false = raw HTML
    $response->assertSee('id="tanggal"', false);
    $response->assertSee('id="kategori"', false);
    $response->assertSee('id="sub_kategori"', false);
}

/** @test */
public function test_api_endpoints()
{
    // POST /emissions (simulasi create data)
    $response = $this->postJson('/emissions', [
        'tanggal' => '2025-01-01',
        'kategori' => 'sampah',
        'nilai_aktivitas' => 0.5
    ]);
    $response->assertStatus(200)->assertJson(['success' => true]);

    // DELETE /emissions/{id} (simulasi hapus data)
    $response = $this->deleteJson('/emissions/1');
    $response->assertStatus(200)->assertJson(['success' => true]);
}

/** @test */
public function test_blade_components()
{
    $response = $this->get('/dashboard');
    
    // Periksa komponen utama saja
    $response->assertSee('EcoLogix Dashboard', false);
    $response->assertSee('id="emissionChart"', false); // Chart utama
    $response->assertSee('Input Emisi Karbon', false); // Tombol penting
    $response->assertSee('Total Carbon Emision', false); // Card penting
}

/** @test */
public function test_table_data()
{
    $response = $this->get('/history');

    // Cek 5 baris pertama
    for ($i = 1; $i <= 5; $i++) {
        $response->assertSee($i); // Kolom "No"
        $response->assertSee('01/' . str_pad($i, 2, '0', STR_PAD_LEFT) . '/25'); // Tanggal
        $response->assertSee('0.' . str_pad($i, 2, '0', STR_PAD_LEFT) . ' kg Co₂e'); // Emission
    }
}

}