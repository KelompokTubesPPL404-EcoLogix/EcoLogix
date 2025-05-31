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
        Schema::table('pembelian_carbon_credit', function (Blueprint $table) {
            $table->string('kode_perusahaan')->after('kode_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian_carbon_credit', function (Blueprint $table) {
            $table->dropColumn('kode_perusahaan');
        });
    }
};
