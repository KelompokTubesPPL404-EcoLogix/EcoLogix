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
        // Tambahkan foreign key untuk manager ke perusahaan
        Schema::table('manager', function (Blueprint $table) {
            $table->foreign('kode_perusahaan')->references('kode_perusahaan')->on('perusahaan');
        });

        // Tambahkan foreign key untuk perusahaan ke manager
        Schema::table('perusahaan', function (Blueprint $table) {
            $table->foreign('kode_manager')->references('kode_manager')->on('manager');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus foreign key dari manager
        Schema::table('manager', function (Blueprint $table) {
            $table->dropForeign(['kode_perusahaan']);
        });

        // Hapus foreign key dari perusahaan
        Schema::table('perusahaan', function (Blueprint $table) {
            $table->dropForeign(['kode_manager']);
        });
    }
};