<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class NotificationsTest extends TestCase
{
    #[Test]
    public function notifications_page_is_accessible()
    {
        $response = $this->get('/admin/notifications');
        $response->assertStatus(200);
    }
}
