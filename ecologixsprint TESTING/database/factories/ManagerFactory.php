<?php

namespace Database\Factories;

use App\Models\Manager;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ManagerFactory extends Factory
{
    protected $model = Manager::class;

    public function definition(): array
    {
        return [
            'kode_manager' => 'USR' . Str::random(8),
            'kode_manager' => 'USR' . Str::random(8),
            'nama_manager' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'no_telepon' => fake()->phoneNumber(),
            'remember_token' => Str::random(10),
        ];
    }
}