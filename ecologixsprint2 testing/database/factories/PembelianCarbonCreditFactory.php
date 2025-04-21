<?php

namespace Database\Factories;

use App\Models\PembelianCarbonCredit;
use App\Models\Admin;
use App\Models\Pengguna;
use App\Models\PenyediaCarbonCredit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PembelianCarbonCreditFactory extends Factory
{
    protected $model = PembelianCarbonCredit::class;

    public function definition(): array
    {
        $admin = Admin::factory()->create();
        $pengguna = Pengguna::factory()->create();
        $penyedia = PenyediaCarbonCredit::factory()->create();

        return [
            'kode_pembelian_carbon_credit' => 'PCC-' . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'kode_admin' => $admin->kode_admin,
            'kode_user' => $pengguna->kode_user,
            'kode_penyedia' => $penyedia->kode_penyedia,
            'tanggal_pembelian_carbon_credit' => fake()->date(),
            'jumlah_kompensasi' => fake()->numberBetween(1, 1000),
            'harga_per_ton' => fake()->numberBetween(10000, 100000),
            'total_harga' => function (array $attributes) {
                return $attributes['jumlah_kompensasi'] * $attributes['harga_per_ton'];
            },
            'deskripsi' => fake()->sentence(),
            'bukti_pembelian' => 'bukti_pembelian/test.jpg',
            'status' => fake()->randomElement(['pending', 'approved', 'rejected'])
        ];
    }
}