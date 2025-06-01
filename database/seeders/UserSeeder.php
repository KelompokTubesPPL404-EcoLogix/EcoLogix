<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Contoh data user
        $userData = [
            [
                'kode_user' => 'USR001',
                'nama' => 'User Umum 1',
                'email' => 'user1@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
                'no_hp' => '08123456731',
                'alamat' => 'Jl. Contoh No. 1',
                'kode_perusahaan' => null,
            ],
            [
                'kode_user' => 'USR002',
                'nama' => 'User Umum 2',
                'email' => 'user2@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
                'no_hp' => '08123456732',
                'alamat' => 'Jl. Contoh No. 2',
                'kode_perusahaan' => null,
            ],
        ];

        foreach ($userData as $data) {
            User::create($data);
        }
    }
}