<?php

namespace Database\Factories;

use App\Models\PenyediaCarbonCredit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PenyediaCarbonCreditFactory extends Factory
{
    protected $model = PenyediaCarbonCredit::class;

    public function definition(): array
    {
        return [
            'kode_penyedia' => 'PCC-' . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'nama_penyedia' => fake()->company(),
            'mata_uang' => fake()->randomElement(['IDR', 'USD', 'EUR']),
            'harga_per_ton' => fake()->numberBetween(10000, 100000),
            'is_active' => true
        ];
    }
}