<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class DashboardTest extends TestCase
{
    #[Test]
    public function dashboard_page_is_accessible()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
    }
}
