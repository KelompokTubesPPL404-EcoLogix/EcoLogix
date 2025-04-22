<?php

namespace Database\Factories;

use App\Models\SuperAdmin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SuperAdminFactory extends Factory
{
    protected $model = SuperAdmin::class;

    public function definition(): array
    {
        return [
            'kode_super_admin' => 'USR' . Str::random(8),
            'nama_super_admin' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'no_telepon' => fake()->phoneNumber(),
            'remember_token' => Str::random(10),
        ];
    }
}