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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->string('kode_notifikasi')->primary();
            $table->string('kategori_notifikasi');
            $table->string('deskripsi');
            $table->date('tanggal_notifikasi');
            $table->string('kode_admin');
            $table->string('kode_staff');
            $table->foreign('kode_admin')->references('kode_user')->on('users');
            $table->foreign('kode_staff')->references('kode_user')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
