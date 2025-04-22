<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardContentTest extends TestCase
{
    /**
     * Tes konten statistik pada dashboard
     */
    public function test_dashboard_contains_carbon_statistics()
    {
        $response = $this->get(route('manager.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Statistics: Total Emission Carbon');
        $response->assertSee('Total Karbon Emisi (Approved)');
        $response->assertSee('Total Emisi (Approved)');
        $response->assertSee('Total Kompensasi');
        $response->assertSee('Emisi Carbon Pending');
        $response->assertSee('Emisi Pending');
        $response->assertSee('Persentase Kompensasi');
    }

    /**
     * Tes keberadaan grafik pada dashboard
     */
    public function test_dashboard_contains_carbon_chart()
    {
        $response = $this->get(route('manager.dashboard'));

        $response->assertStatus(200);
        // Verifikasi keberadaan elemen canvas untuk chart
        $response->assertSee('<canvas id="carbonChart"', false);
        // Verifikasi keberadaan library Chart.js
        $response->assertSee('cdn.jsdelivr.net/npm/chart.js', false);
    }

    /**
     * Tes keberadaan data statistik dan format pada dashboard
     */
    public function test_dashboard_contains_formatted_data()
    {
        $response = $this->get(route('manager.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('ton CO₂e');
        $response->assertSee('ton CO₂');
        $response->assertSee('%');
    }
} 