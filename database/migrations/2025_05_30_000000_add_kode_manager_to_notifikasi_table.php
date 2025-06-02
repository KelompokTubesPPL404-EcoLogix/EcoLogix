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
            $table->string('kode_manager')->nullable()->after('kode_staff');
            $table->foreign('kode_manager')->references('kode_user')->on('users');
            
            // Make kode_admin and kode_staff nullable
            $table->string('kode_admin')->nullable()->change();
            $table->string('kode_staff')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropForeign(['kode_manager']);
            $table->dropColumn('kode_manager');
            
            // Revert kode_admin and kode_staff to non-nullable
            $table->string('kode_admin')->nullable(false)->change();
            $table->string('kode_staff')->nullable(false)->change();
        });
    }
};