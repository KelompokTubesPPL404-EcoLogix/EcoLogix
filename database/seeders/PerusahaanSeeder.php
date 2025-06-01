<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perusahaan;
use Illuminate\Support\Facades\Hash;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Contoh data perusahaan
        $perusahaanData = [
            [
                'kode_perusahaan' => 'PRSH001',
                'nama_perusahaan' => 'PT. Hijau Lestari',
                'alamat_perusahaan' => 'Jl. Merdeka No. 10, Jakarta',
                'no_telp_perusahaan' => '021-1234567',
                'email_perusahaan' => 'info@hijau-lestari.com',
                'password_perusahaan' => bcrypt('password'),
                'kode_manager' => null, // Akan diisi setelah manager dibuat
                'kode_super_admin' => 'SA001', // Sesuaikan dengan kode super admin yang ada
            ],
            [
                'kode_perusahaan' => 'PRSH002',
                'nama_perusahaan' => 'PT. Bersih Udara',
                'alamat_perusahaan' => 'Jl. Sudirman No. 20, Bandung',
                'no_telp_perusahaan' => '022-9876543',
                'email_perusahaan' => 'kontak@bersih-udara.com',
                'password_perusahaan' => bcrypt('password'),
                'kode_manager' => null, // Akan diisi setelah manager dibuat
                'kode_super_admin' => 'SA001', // Sesuaikan dengan kode super admin yang ada
            ],
        ];

        foreach ($perusahaanData as $data) {
            Perusahaan::create($data);
        }
    }
}