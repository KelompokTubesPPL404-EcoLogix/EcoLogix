<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FaktorEmisi;
use Illuminate\Support\Str;

class FaktorEmisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Contoh data faktor emisi
        $faktorEmisiData = [
            [
                'kode_faktor' => 'FE001',
                'kategori_emisi_karbon' => 'Transportasi',
                'sub_kategori' => 'Kendaraan Pribadi',
                'nilai_faktor' => 0.25,
                'satuan' => 'kg CO2e/km',
                'kode_perusahaan' => 'PRSH001',
            ],
            [
                'kode_faktor' => 'FE002',
                'kategori_emisi_karbon' => 'Energi',
                'sub_kategori' => 'Listrik',
                'nilai_faktor' => 0.8,
                'satuan' => 'kg CO2e/kWh',
                'kode_perusahaan' => 'PRSH002',
            ],
            [
                'kode_faktor' => 'FE003',
                'kategori_emisi_karbon' => 'Limbah',
                'sub_kategori' => 'Limbah Padat',
                'nilai_faktor' => 0.1,
                'satuan' => 'kg CO2e/kg',
                'kode_perusahaan' => 'PRSH001',
            ],
        ];

        foreach ($faktorEmisiData as $data) {
            FaktorEmisi::create($data);
        }
    }
}