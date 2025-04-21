<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Manager;
use App\Models\Pengguna;
use App\Models\SuperAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_can_access_register_pages()
    {
        // Test register pengguna page
        $response = $this->get('/register/pengguna');
        $response->assertStatus(200);

        // Test register manager page
        $response = $this->get('/register/manager');
        $response->assertStatus(200);

        // Test register admin page
        $response = $this->get('/register/admin');
        $response->assertStatus(200);
    }

    public function test_pengguna_can_login_and_access_dashboard()
    {
        $pengguna = Pengguna::factory()->create([
            'email' => 'pengguna@test.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login/pengguna', [
            'email' => 'pengguna@test.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboard/pengguna');
        $this->assertAuthenticatedAs($pengguna, 'pengguna');
    }

    public function test_manager_can_login_and_access_dashboard()
    {
        $manager = Manager::factory()->create([
            'email' => 'manager@test.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login/manager', [
            'email' => 'manager@test.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboard/manager');
        $this->assertAuthenticatedAs($manager, 'manager');
    }

    public function test_admin_can_login_and_access_dashboard()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login/admin', [
            'email' => 'admin@test.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboard/admin');
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_super_admin_can_login_and_access_dashboard()
    {
        $superAdmin = SuperAdmin::factory()->create([
            'email' => 'superadmin@test.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login/super-admin', [
            'email' => 'superadmin@test.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboard/super-admin');
        $this->assertAuthenticatedAs($superAdmin, 'super_admin');
    }
}