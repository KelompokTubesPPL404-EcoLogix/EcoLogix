<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenyediaCarbonCredit;
use Illuminate\Support\Str;

class PenyediaCarbonCreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Contoh data penyedia carbon credit
        $penyediaData = [
            [
                'kode_penyedia' => 'PCCRED001',
                'kode_perusahaan' => 'PRSH001', // Sesuaikan dengan kode perusahaan yang ada
                'nama_penyedia' => 'Green Earth Solutions',
                'deskripsi' => 'Penyedia kredit karbon terkemuka dengan fokus pada proyek reboisasi.',
                'harga_per_ton' => 15.00,
                'mata_uang' => 'USD',
                'is_active' => true,
            ],
            [
                'kode_penyedia' => 'PCCRED002',
                'kode_perusahaan' => 'PRSH002', // Sesuaikan dengan kode perusahaan yang ada
                'nama_penyedia' => 'Carbon Neutral Alliance',
                'deskripsi' => 'Menyediakan kredit karbon dari proyek energi terbarukan dan efisiensi energi.',
                'harga_per_ton' => 12.50,
                'mata_uang' => 'USD',
                'is_active' => true,
            ],
            [
                'kode_penyedia' => 'PCCRED003',
                'kode_perusahaan' => 'PRSH001', // Sesuaikan dengan kode perusahaan yang ada
                'nama_penyedia' => 'EcoBalance Corp',
                'deskripsi' => 'Spesialis dalam proyek penangkapan metana dan pengelolaan limbah.',
                'harga_per_ton' => 18.00,
                'mata_uang' => 'USD',
                'is_active' => false,
            ],
        ];

        foreach ($penyediaData as $data) {
            PenyediaCarbonCredit::create($data);
        }
    }
}