<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Contoh data manager
        $managers = [
            [
                'kode_user' => 'MGR001',
                'nama' => 'Manager Perusahaan PRSH001',
                'email' => 'manager1@example.com',
                'password' => bcrypt('password'),
                'no_hp' => '081234567890',
                'kode_perusahaan' => 'PRSH001',
                'role' => 'manager', // Sesuaikan dengan kode perusahaan yang ada
            ],
            [
                'kode_user' => 'MGR002',
                'nama' => 'Manager Perusahaan PRSH002',
                'email' => 'manager2@example.com',
                'password' => bcrypt('password'),
                'no_hp' => '089876543210',
                'kode_perusahaan' => 'PRSH002',
                'role' => 'manager', // Sesuaikan dengan kode perusahaan yang ada
            ],
        ];

        foreach ($managers as $manager) {
            User::create($manager);
        }
    }
}