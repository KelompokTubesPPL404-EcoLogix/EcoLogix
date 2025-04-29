<?php

namespace Database\Factories;

use App\Models\Staff;
use App\Models\Perusahaan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition(): array
    {
        $perusahaan = Perusahaan::factory()->create();
        return [
            'kode_staff' => 'USR' . Str::random(8),
            'nama_staff' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'alamat' => Str::random(10),
            'password' => bcrypt('password'),
            'no_hp' => fake()->phoneNumber(),
            'kode_perusahaan' => $perusahaan->kode_perusahaan,
            'remember_token' => Str::random(10),
        ];
    }
}