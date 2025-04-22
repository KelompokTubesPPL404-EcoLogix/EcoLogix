<?php

namespace Database\Factories;

use App\Models\EmisiCarbon;
use App\Models\Staff;
use App\Models\FaktorEmisi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmisiCarbon>
 */
class EmisiCarbonFactory extends Factory
{
    protected $model = EmisiCarbon::class;

    public function definition(): array
    {
        // Buat staff member
        $staff = Staff::factory()->create(); 
        $faktor = FaktorEmisi::factory()->create();

        return [
            'kode_emisi_carbon' => 'EMC-' . strtoupper(Str::random(6)),
            'kategori_emisi_karbon' => $this->faker->randomElement(['Energi', 'Transportasi', 'Limbah']),
            'sub_kategori' => $this->faker->word(),
            'nilai_aktivitas' => $this->faker->randomFloat(2, 1, 100),
            'faktor_emisi' => $this->faker->randomFloat(2, 0.1, 5.0),
            //'kadar_emisi_karbon' => null,
            'deskripsi' => $this->faker->sentence(),
            'status' => 'pending',
            'tanggal_emisi' => now(),
            'kode_staff' => $staff->kode_staff, 
            'kode_faktor_emisi' => $faktor->kode_faktor,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}