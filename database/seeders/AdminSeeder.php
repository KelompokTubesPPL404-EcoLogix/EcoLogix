<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh data admin
        $admins = [
            [
                'kode_user' => 'ADM001',
                'nama' => 'Admin Perusahaan A',
                'email' => 'adminA@example.com',
                'password' => bcrypt('password'),
                'no_hp' => '081234567890',
                'kode_perusahaan' => 'PRSH001',
                'role' => 'admin',
            ],
            [
                'kode_user' => 'ADM002',
                'nama' => 'Admin Perusahaan B',
                'email' => 'adminB@example.com',
                'password' => bcrypt('password'),
                'no_hp' => '089876543210',
                'kode_perusahaan' => 'PRSH002',
                'role' => 'admin',
            ],
        ];

        foreach ($admins as $admin) {
            User::create($admin);
        }
    }
}