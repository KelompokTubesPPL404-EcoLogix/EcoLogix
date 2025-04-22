<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CreditTest extends TestCase
{
    #[Test]
    public function credit_page_is_accessible()
    {
        $response = $this->get('/admin/credits');
        $response->assertStatus(200);
    }
}

