<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Manager;
use App\Models\Pengguna;
use App\Models\SuperAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\PembelianCarbonCredit;
use App\Models\PenyediaCarbonCredit;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FirstTest extends TestCase
{
    use RefreshDatabase;

    public function test_example(): void
    {
        $this->assertTrue(true);
    }


    public function test_can_access_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_access_register_pages()
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
        // Create a pengguna first
        $pengguna = Pengguna::factory()->create([
            'nama_user' => 'Test User',
            'email' => 'pengguna@test.com',
            'no_telepon' => '08080808',
            'password' => bcrypt('password123')
        ]);
        
        $response = $this->post(route('login'), [
            'email' => 'pengguna@test.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticatedAs($pengguna, 'pengguna');
    }

    public function test_manager_can_login_and_access_dashboard()
    {
        // Create a pengguna first
        $manager = Manager::factory()->create([
            'nama_manager' => 'Test User',
            'email' => 'manager@test.com',
            'no_telepon' => '08080808',
            'password' => bcrypt('password123')
        ]);
        
        $response = $this->post(route('login'), [
            'email' => 'manager@test.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('manager.dashboard'));
        $this->assertAuthenticatedAs($manager, 'manager');
    }
    
    public function test_admin_can_login_and_access_dashboard()
    {
        // Create a pengguna first
        $admin = Admin::factory()->create([
            'nama_admin' => 'Test User',
            'email' => 'admin@test.com',
            'no_telepon' => '08080808',
            'password' => bcrypt('password123')
        ]);
        
        $response = $this->post(route('login'), [
            'email' => 'admin@test.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    
}
