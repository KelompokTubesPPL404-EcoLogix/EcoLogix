<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * File ini berisi semua foreign key untuk semua tabel dalam database
     */
    public function up(): void
    {
        // Foreign key untuk tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('kode_perusahaan')->references('kode_perusahaan')->on('perusahaan')->onDelete('set null');
        });

        // Foreign key untuk tabel pembelian_carbon_credit
        Schema::table('pembelian_carbon_credit', function (Blueprint $table) {
            $table->foreign('kode_penyedia')->references('kode_penyedia')->on('penyedia_carbon_credit');
            $table->foreign('kode_kompensasi')->references('kode_kompensasi')->on('kompensasi_emisi_carbon');
            $table->foreign('kode_admin')->references('kode_user')->on('users');
        });

        // Foreign key untuk tabel kompensasi_emisi_carbon
        Schema::table('kompensasi_emisi_carbon', function (Blueprint $table) {
            $table->foreign('kode_emisi_carbon')->references('kode_emisi_carbon')->on('emisi_carbon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus foreign key dari tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kode_perusahaan']);
        });

        // Hapus foreign key dari tabel pembelian_carbon_credit
        Schema::table('pembelian_carbon_credit', function (Blueprint $table) {
            $table->dropForeign(['kode_penyedia']);
            $table->dropForeign(['kode_kompensasi']);
            $table->dropForeign(['kode_admin']);
        });

        // Hapus foreign key dari tabel kompensasi_emisi_carbon
        Schema::table('kompensasi_emisi_carbon', function (Blueprint $table) {
            $table->dropForeign(['kode_emisi_carbon']);
        });
    }
};