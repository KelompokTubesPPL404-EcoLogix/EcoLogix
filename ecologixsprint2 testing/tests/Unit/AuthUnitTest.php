<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Admin;
use App\Models\Manager;
use App\Models\Pengguna;
use App\Models\SuperAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Mockery;

class AuthUnitTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function test_password_hashing()
    {
        $password = 'password123';
        $hashedPassword = bcrypt($password);
        $this->assertTrue(Hash::check($password, $hashedPassword));
    }

    public function test_pengguna_credentials_validation()
    {
        $pengguna = Pengguna::factory()->make([
            'email' => 'pengguna@test.com',
            'password' => bcrypt('password123')
        ]);

        $this->assertNotEmpty($pengguna->email);
        $this->assertNotEmpty($pengguna->password);
        $this->assertMatchesRegularExpression('/^[^@]+@[^@]+\.[^@]+$/', $pengguna->email);
    }

    public function test_manager_credentials_validation()
    {
        $manager = Manager::factory()->make([
            'email' => 'manager@test.com',
            'password' => bcrypt('password123')
        ]);

        $this->assertNotEmpty($manager->email);
        $this->assertNotEmpty($manager->password);
        $this->assertMatchesRegularExpression('/^[^@]+@[^@]+\.[^@]+$/', $manager->email);
    }

    public function test_admin_credentials_validation()
    {
        $admin = Admin::factory()->make([
            'email' => 'admin@test.com',
            'password' => bcrypt('password123')
        ]);

        $this->assertNotEmpty($admin->email);
        $this->assertNotEmpty($admin->password);
        $this->assertMatchesRegularExpression('/^[^@]+@[^@]+\.[^@]+$/', $admin->email);
    }

    public function test_super_admin_credentials_validation()
    {
        $superAdmin = SuperAdmin::factory()->make([
            'email' => 'superadmin@test.com',
            'password' => bcrypt('password123')
        ]);

        $this->assertNotEmpty($superAdmin->email);
        $this->assertNotEmpty($superAdmin->password);
        $this->assertMatchesRegularExpression('/^[^@]+@[^@]+\.[^@]+$/', $superAdmin->email);
    }

    public function test_user_authentication_logic()
    {
        $password = 'password123';
        $hashedPassword = bcrypt($password);

        $user = Mockery::mock(Pengguna::class);
        $user->shouldReceive('getAttribute')->with('password')->andReturn($hashedPassword);

        $this->assertTrue(Hash::check($password, $user->password));
        $this->assertFalse(Hash::check('wrongpassword', $user->password));
    }
}