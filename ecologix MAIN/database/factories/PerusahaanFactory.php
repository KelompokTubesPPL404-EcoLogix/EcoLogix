<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\SuperAdmin;

class PerusahaanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_perusahaan' => strtoupper(Str::random(8)),
            'nama_perusahaan' => $this->faker->company(),
            'alamat_perusahaan' => $this->faker->address(),
            'no_telp_perusahaan' => $this->faker->phoneNumber(),
            'email_perusahaan' => $this->faker->unique()->companyEmail(),
            'password_perusahaan' => bcrypt('password'),
            'kode_manager' => null,
            'kode_super_admin' => SuperAdmin::factory(),
        ];
    }
}
    