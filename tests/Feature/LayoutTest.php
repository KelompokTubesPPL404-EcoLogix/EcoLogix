<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayoutTest extends TestCase
{
    /**
     * Memastikan views punya elemen dasar
     */
    public function test_manager_layout_contains_important_elements()
    {
        // Mendapatkan rute yang menggunakan layout manager
        $response = $this->get(route('manager.dashboard'));
        
        $response->assertStatus(200);
        
        // Cek elemen utama pada layout
        $response->assertSee('EcoLogix');
        $response->assertSee('Dashboard', false);
        
        // Tes komponen card
        $response->assertSee('card', false);
        $response->assertSee('Statistics: Total Emission Carbon', false);
    }
    
    /**
     * Verifikasi konten dasar ada di halaman kompensasi
     */
    public function test_kompensasi_page_has_basic_elements()
    {
        $response = $this->get(route('manager.kompensasi.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('manager.kompensasi.index');
    }
    
    /**
     * Verifikasi konten dasar ada di halaman faktor emisi
     */
    public function test_faktor_emisi_page_has_basic_elements()
    {
        $response = $this->get(route('manager.faktor-emisi.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('manager.faktor-emisi.index');
    }
} 