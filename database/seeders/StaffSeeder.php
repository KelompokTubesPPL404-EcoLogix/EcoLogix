<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Contoh data staff
        $staffData = [
            [
                'kode_user' => 'STF001',
                'nama' => 'Staff Perusahaan PRSH001',
                'alamat' => 'Jl. Mawar No. 5, Jakarta',
                'no_hp' => '081234567890',
                'email' => 'staff1@example.com',
                'password' => bcrypt('password'),
                'kode_perusahaan' => 'PRSH001',
                'role' => 'staff', // Sesuaikan dengan kode perusahaan yang ada
            ],
            [
                'kode_user' => 'STF002',
                'nama' => 'Staff Perusahaan PRSH002',
                'alamat' => 'Jl. Melati No. 12, Bandung',
                'no_hp' => '089876543210',
                'email' => 'staff2@example.com',
                'password' => bcrypt('password'),
                'kode_perusahaan' => 'PRSH002',
                'role' => 'staff', // Sesuaikan dengan kode perusahaan yang ada
            ],
        ];

        foreach ($staffData as $data) {
            User::create($data);
        }
    }
}