<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmisiKarbon;
use Illuminate\Support\Str;

class EmisiKarbonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Contoh data emisi karbon
        $emisiKarbonData = [
            [
                'kode_emisi_carbon' => 'EMK001',
                'kategori_emisi_karbon' => 'Transportasi',
                'sub_kategori' => 'Kendaraan Pribadi',
                'nilai_aktivitas' => 100.50,
                'faktor_emisi' => 0.5,
                'deskripsi' => 'Perjalanan harian dengan mobil pribadi.',
                'status' => 'Disetujui',
                'tanggal_emisi' => '2024-01-15',
                'kode_staff' => 'STF001', // Sesuaikan dengan kode staff yang ada
                'kode_faktor_emisi' => 'FE001', // Sesuaikan dengan kode faktor emisi yang ada
                'kode_perusahaan' => 'PRSH001', // Sesuaikan dengan kode perusahaan yang ada
            ],
            [
                'kode_emisi_carbon' => 'EMK002',
                'kategori_emisi_karbon' => 'Energi',
                'sub_kategori' => 'Listrik',
                'nilai_aktivitas' => 250.00,
                'faktor_emisi' => 0.8,
                'deskripsi' => 'Penggunaan listrik bulanan di kantor.',
                'status' => 'Menunggu Persetujuan',
                'tanggal_emisi' => '2024-02-20',
                'kode_staff' => 'STF002', // Sesuaikan dengan kode staff yang ada
                'kode_faktor_emisi' => 'FE002', // Sesuaikan dengan kode faktor emisi yang ada
                'kode_perusahaan' => 'PRSH002', // Sesuaikan dengan kode perusahaan yang ada
            ],
        ];

        foreach ($emisiKarbonData as $data) {
            EmisiKarbon::create($data);
        }
    }
}