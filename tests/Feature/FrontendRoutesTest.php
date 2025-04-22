<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontendRoutesTest extends TestCase
{
    /**
     * Test halaman dashboard bisa diakses.
     */
    public function test_dashboard_page_is_accessible()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test halaman kompensasi (index) bisa diakses.
     */
    public function test_kompensasi_index_page_is_accessible()
    {
        $response = $this->get('/kompensasi');
        $response->assertStatus(200);
    }

    /**
     * Test halaman kompensasi detail bisa diakses.
     */
    public function test_kompensasi_detail_page_is_accessible()
    {
        $response = $this->get('/kompensasi/1'); // ID dummy
        $response->assertStatus(200);
    }

    /**
     * Test halaman kompensasi edit bisa diakses.
     */
    public function test_kompensasi_edit_page_is_accessible()
    {
        $response = $this->get('/kompensasi/1/edit');
        $response->assertStatus(200);
    }

    /**
     * Test halaman report kompensasi bisa diakses.
     */
    public function test_kompensasi_report_page_is_accessible()
    {
        $response = $this->get('/kompensasi/report/pdf');
        $response->assertStatus(200);
    }

    /**
     * Test halaman faktor emisi bisa diakses.
     */
    public function test_faktor_emisi_page_is_accessible()
    {
        $response = $this->get('/faktor-emisi');
        $response->assertStatus(200);
    }

    /**
     * Test halaman penyedia carbon credit bisa diakses.
     */
    public function test_penyedia_page_is_accessible()
    {
        $response = $this->get('/penyedia-carbon-credit');
        $response->assertStatus(200);
    }

    /**
     * Test halaman carbon credit bisa diakses.
     */
    public function test_carbon_credit_page_is_accessible()
    {
        $response = $this->get('/carbon-credit');
        $response->assertStatus(200);
    }

    /**
     * Test halaman profile bisa diakses.
     */
    public function test_profile_page_is_accessible()
    {
        $response = $this->get('/profile');
        $response->assertStatus(200);
    }
}