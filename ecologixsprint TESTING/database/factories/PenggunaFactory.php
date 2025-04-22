<?php

namespace Database\Factories;

use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PenggunaFactory extends Factory
{
    protected $model = Pengguna::class;

    public function definition(): array
    {
        return [
            'kode_user' => 'USR' . Str::random(8),
            'nama_user' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'no_telepon' => fake()->phoneNumber(),
            'remember_token' => Str::random(10),
        ];
    }
}