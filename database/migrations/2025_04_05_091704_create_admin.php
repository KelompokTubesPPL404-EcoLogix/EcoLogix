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
        Schema::create('admin', function (Blueprint $table) {
            $table->string('kode_admin')->primary();
            $table->string('nama_admin');
            $table->string('email');
            $table->string('password');
            $table->string('no_hp');
            $table->string('kode_perusahaan');
            $table->foreign('kode_perusahaan')->references('kode_perusahaan')->on('perusahaan');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
