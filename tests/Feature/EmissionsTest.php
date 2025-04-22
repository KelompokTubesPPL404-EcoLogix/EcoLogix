<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EmissionsTest extends TestCase
{
    #[Test]
    public function emissions_page_is_accessible()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
    }
}
