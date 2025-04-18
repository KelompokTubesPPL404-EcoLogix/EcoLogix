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
        Schema::create('kompensasi_emisi_carbon', function (Blueprint $table) {
            $table->string('kode_kompensasi')->primary();
            $table->string('kode_emisi_carbon');
            $table->decimal('jumlah_kompensasi', 10, 2);
            $table->date('tanggal_kompensasi');
            $table->enum('status_kompensasi', ['Belum Terkompensasi', 'Terkompensasi']);
            $table->string('kode_manager');
            $table->string('kode_perusahaan');
            $table->foreign('kode_manager')->references('kode_manager')->on('manager');
            $table->foreign('kode_perusahaan')->references('kode_perusahaan')->on('perusahaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kompensasi_emisi_carbon');
    }
};
