<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ReportTest extends TestCase
{
    #[Test]
    public function Report_page_is_accessible()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
    }
}
