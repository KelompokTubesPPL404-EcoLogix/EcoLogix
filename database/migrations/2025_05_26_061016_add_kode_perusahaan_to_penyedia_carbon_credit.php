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
        Schema::table('penyedia_carbon_credit', function (Blueprint $table) {
            $table->string('kode_perusahaan')->after('kode_penyedia')->nullable();
            $table->foreign('kode_perusahaan')->references('kode_perusahaan')->on('perusahaan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyedia_carbon_credit', function (Blueprint $table) {
            $table->dropForeign(['kode_perusahaan']);
            $table->dropColumn('kode_perusahaan');
        });
    }
};
