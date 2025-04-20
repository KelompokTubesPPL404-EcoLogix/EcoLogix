<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penyedia_carbon_credit', function (Blueprint $table) {
            $table->string('kode_penyedia')->primary();
            $table->string('nama_penyedia');
            $table->string('deskripsi');
            $table->decimal('harga_per_ton',10,2);
            $table->string('mata_uang')->default('IDR');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyedia_carbon_credit');
    }
};
