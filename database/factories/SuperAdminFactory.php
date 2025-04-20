<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SuperAdminFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_super_admin' => strtoupper(Str::random(10)),
            'nama_super_admin' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // hash password default
            'no_hp' => $this->faker->phoneNumber(),
            'remember_token' => Str::random(10),
        ];
    }
}
