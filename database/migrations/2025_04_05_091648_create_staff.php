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
        Schema::create('staff', function (Blueprint $table) {
            $table->string('kode_staff')->primary();
            $table->string('nama_staff');
            $table->string('alamat');
            $table->string('no_hp');
            $table->string('email');
            $table->string('password');
            $table->string('kode_perusahaan');
            $table->rememberToken();
            $table->foreign('kode_perusahaan')->references('kode_perusahaan')->on('perusahaan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
