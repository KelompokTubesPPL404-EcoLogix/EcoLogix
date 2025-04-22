<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteViewMappingTest extends TestCase
{
    /**
     * Tes untuk memverifikasi pemetaan rute ke tampilan
     */
    public function test_route_to_view_mapping()
    {
        // Definisi pasangan rute dan nama tampilan
        $routeViewPairs = [
            'manager.dashboard' => 'manager.dashboard',
            'manager.kompensasi.index' => 'manager.kompensasi.index',
            'manager.faktor-emisi.index' => 'manager.faktor-emisi.index',
            'manager.penyedia.index' => 'manager.penyedia.index',
            'manager.carbon_credit.index' => 'manager.carbon_credit.index',
            'manager.profile' => 'manager.profile',
        ];

        // Tes setiap pasangan rute dan tampilan
        foreach ($routeViewPairs as $routeName => $viewName) {
            $response = $this->get(route($routeName));
            $response->assertStatus(200);
            $response->assertViewIs($viewName);
        }
    }

    /**
     * Tes untuk rute dengan parameter
     */
    public function test_parameterized_routes()
    {
        $testId = 1;
        
        // Tes rute dengan parameter ID
        $response = $this->get(route('manager.kompensasi.show', ['id' => $testId]));
        $response->assertStatus(200);
        $response->assertViewIs('manager.kompensasi.show');
        $response->assertViewHas('id', $testId);

        // Tes rute edit
        $response = $this->get(route('manager.kompensasi.edit.', ['id' => $testId]));
        $response->assertStatus(200);
        $response->assertViewIs('manager.kompensasi.edit');
        $response->assertViewHas('id', $testId);
    }

    /**
     * Tes untuk rute report
     */
    public function test_report_route()
    {
        $response = $this->get(route('manager.kompensasi.report'));
        $response->assertStatus(200);
        $response->assertViewIs('manager.kompensasi.report');
    }
} 