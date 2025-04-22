<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewRenderTest extends TestCase
{
    /**
     * Tes rendering dashboard manager
     */
    public function test_manager_dashboard_view_can_be_rendered()
    {
        $response = $this->get(route('manager.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('manager.dashboard');
        $response->assertSee('Dashboard - EcoLogix');
    }

    /**
     * Tes rendering halaman kompensasi
     */
    public function test_manager_kompensasi_index_view_can_be_rendered()
    {
        $response = $this->get(route('manager.kompensasi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('manager.kompensasi.index');
    }

    /**
     * Tes rendering detail kompensasi
     */
    public function test_manager_kompensasi_detail_view_can_be_rendered()
    {
        $dummyId = 1; // ID uji coba
        
        $response = $this->get(route('manager.kompensasi.show', ['id' => $dummyId]));

        $response->assertStatus(200);
        $response->assertViewIs('manager.kompensasi.show');
        $response->assertViewHas('id', $dummyId);
    }

    /**
     * Tes rendering edit kompensasi
     */
    public function test_manager_kompensasi_edit_view_can_be_rendered()
    {
        $dummyId = 1; // ID uji coba
        
        $response = $this->get(route('manager.kompensasi.edit.', ['id' => $dummyId]));

        $response->assertStatus(200);
        $response->assertViewIs('manager.kompensasi.edit');
        $response->assertViewHas('id', $dummyId);
    }

    /**
     * Tes rendering report kompensasi
     */
    public function test_manager_kompensasi_report_view_can_be_rendered()
    {
        $response = $this->get(route('manager.kompensasi.report'));

        $response->assertStatus(200);
        $response->assertViewIs('manager.kompensasi.report');
    }

    /**
     * Tes rendering faktor emisi
     */
    public function test_manager_faktor_emisi_view_can_be_rendered()
    {
        $response = $this->get(route('manager.faktor-emisi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('manager.faktor-emisi.index');
    }

    /**
     * Tes rendering penyedia carbon credit
     */
    public function test_manager_penyedia_carbon_credit_view_can_be_rendered()
    {
        $response = $this->get(route('manager.penyedia.index'));

        $response->assertStatus(200);
        $response->assertViewIs('manager.penyedia.index');
    }

    /**
     * Tes rendering carbon credit
     */
    public function test_manager_carbon_credit_view_can_be_rendered()
    {
        $response = $this->get(route('manager.carbon_credit.index'));

        $response->assertStatus(200);
        $response->assertViewIs('manager.carbon_credit.index');
    }

    /**
     * Tes rendering profile manager
     */
    public function test_manager_profile_view_can_be_rendered()
    {
        $response = $this->get(route('manager.profile'));

        $response->assertStatus(200);
        $response->assertViewIs('manager.profile');
    }
} 