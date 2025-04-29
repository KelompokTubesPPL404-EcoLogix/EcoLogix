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
        Schema::create('pembelian_carbon_credit', function (Blueprint $table) {
            $table->string('kode_pembelian_carbon_credit')->primary();
            $table->string('kode_penyedia');
            $table->string('kode_kompensasi');
            $table->string('kode_admin');
            $table->decimal('jumlah_kompensasi', 10, 2);
            $table->decimal('harga_per_ton', 10, 2);
            $table->decimal('total_harga', 10, 2);
            $table->date('tanggal_pembelian');
            $table->string('bukti_pembayaran');
            $table->string('deskripsi');
            $table->foreign('kode_penyedia')->references('kode_perusahaan')->on('perusahaan');
            $table->foreign('kode_kompensasi')->references('kode_kompensasi')->on('kompensasi_emisi_carbon');
            $table->foreign('kode_admin')->references('kode_admin')->on('admin');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_carbon_credit');
    }
};
