<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardContentTest extends TestCase
{
    /**
     * Tes konten statistik pada dashboard
     */
    public function testDashboardContainsCarbonData()
    {
        $response = $this->get(route('manager.dashboard'));

        $response->assertStatus(200);
    
        $response->assertSee('Total Emisi');
        $response->assertSee('Emisi Dikompensasi');
        $response->assertSee('Persentase Kompensasi');
        $response->assertSee('Carbon Credit Tersedia');
    }

    /**
     * Tes keberadaan grafik pada dashboard
     */
    public function testDashboardContainsCarbonChart()
    {
        $response = $this->get(route('manager.dashboard'));

        $response->assertStatus(200);
    
        $response->assertSee('<canvas id="emisiChart"', false);
        $response->assertSee('<canvas id="sumberEmisiChart"', false);
        $response->assertSee('cdn.jsdelivr.net/npm/chart.js', false);
    }

    /**
     * Tes keberadaan data statistik dan format pada dashboard
     */
    public function testDashboardContainsCarbonUnit()
    {
        $response = $this->get(route('manager.dashboard'));

        $response->assertStatus(200);
    // Tambahkan parameter false untuk raw HTML check
        $response->assertSee('Ton CO<sub>2</sub>e', false);
        $response->assertSee('Ton CO<sub>2</sub>', false);
        $response->assertSee('%');
    }
} 