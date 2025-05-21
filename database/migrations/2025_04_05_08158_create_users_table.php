<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('kode_user')->unique(); // Format: SA001, MGR002, ADM003, STF004
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['super_admin', 'manager', 'admin', 'staff']);
            $table->string('no_hp');
            $table->string('alamat')->nullable();
            $table->string('kode_perusahaan')->nullable(); // Untuk staff/admin/manager
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Foreign key ditambahkan setelah semua tabel dibuat
        // Ini akan dieksekusi di migration terpisah
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}; 