<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FaktorEmisi>
 */
class FaktorEmisiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_faktor' => 'FE-' . $this->faker->unique()->bothify('??###'),
            'kategori_emisi_karbon' => $this->faker->randomElement(['Energi', 'Transportasi', 'Limbah']),
            'sub_kategori' => $this->faker->word(),
            'nilai_faktor' => $this->faker->randomFloat(2, 0.1, 5.0),
            'satuan' => 'kg CO2e/' . $this->faker->randomElement(['liter', 'kWh', 'km']),
        ];
    }
}

