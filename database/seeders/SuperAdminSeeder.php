<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmins = [
            [
                'kode_user' => 'SA001',
                'nama' => 'Super Admin 1',
                'email' => 'superadmin1@example.com',
                'password' => Hash::make('12345678'),
                'role' => (string)'super_admin',
                'no_hp' => '081234567891',
                'alamat' => 'Kantor Pusat 1',
                'kode_perusahaan' => null,
                'email_verified_at' => now(),
            ],
            [
                'kode_user' => 'SA002',
                'nama' => 'Super Admin 2',
                'email' => 'superadmin2@example.com',
                'password' => Hash::make('12345678'),
                'role' => (string)'super_admin',
                'no_hp' => '081234567892',
                'alamat' => 'Kantor Pusat 2',
                'kode_perusahaan' => null,
                'email_verified_at' => now(),
            ],
            [
                'kode_user' => 'SA003',
                'nama' => 'Super Admin 3',
                'email' => 'superadmin3@example.com',
                'password' => Hash::make('12345678'),
                'role' => (string)'super_admin',
                'no_hp' => '081234567893',
                'alamat' => 'Kantor Pusat 3',
                'kode_perusahaan' => null,
                'email_verified_at' => now(),
            ]
        ];

        foreach ($superAdmins as $admin) {
            User::create($admin);
        }
    }
}