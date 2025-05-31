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
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->string('judul')->nullable()->after('deskripsi');
            $table->text('isi')->nullable()->after('judul');
            $table->string('tipe')->nullable()->after('isi');
            $table->boolean('dibaca')->default(false)->after('tipe');
            $table->string('kode_perusahaan')->nullable()->after('kode_manager');
            $table->foreign('kode_perusahaan')->references('kode_perusahaan')->on('perusahaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropForeign(['kode_perusahaan']);
            $table->dropColumn(['judul', 'isi', 'tipe', 'dibaca', 'kode_perusahaan']);
        });
    }
};