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
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->string('kode_perusahaan')->primary();
            $table->string('nama_perusahaan');
            $table->string('alamat_perusahaan');
            $table->string('no_telp_perusahaan');
            $table->string('email_perusahaan');
            $table->string('password_perusahaan');
            $table->string('kode_manager')->nullable();
            $table->string('kode_super_admin');
            $table->foreign('kode_manager')->references('kode_user')->on('users')->onDelete('set null');
            $table->foreign('kode_super_admin')->references('kode_user')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};
