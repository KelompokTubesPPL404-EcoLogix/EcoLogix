<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manager', function (Blueprint $table) {
            $table->id();
            $table->string('kode_manager')->unique();
            $table->string('nama_manager');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('no_telepon');
            $table->string('kode_perusahaan');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('managers');
    }
};
