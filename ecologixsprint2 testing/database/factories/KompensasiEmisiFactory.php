<?php

namespace Database\Factories;

use App\Models\KompensasiEmisi;
use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KompensasiEmisiFactory extends Factory
{
    protected $model = KompensasiEmisi::class;

    public function definition(): array
    {
        $pengguna = Pengguna::factory()->create();

        return [
            'kode_kompensasi' => 'KMP-' . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'kode_user' => $pengguna->kode_user,
            'jumlah_emisi' => fake()->numberBetween(1, 1000),
            'tanggal_kompensasi' => fake()->date(),
            'status' => fake()->randomElement(['pending', 'completed', 'rejected']),
            'deskripsi' => fake()->sentence()
        ];
    }
}