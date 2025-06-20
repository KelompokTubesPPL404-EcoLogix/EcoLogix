<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {

        $this->call([
            SuperAdminSeeder::class,
            PerusahaanSeeder::class,
            ManagerSeeder::class,
            AdminSeeder::class,
            StaffSeeder::class,

            FaktorEmisiSeeder::class,
            EmisiKarbonSeeder::class,
            PenyediaCarbonCreditSeeder::class
        ]);
    }
}
