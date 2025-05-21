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
        Schema::create('faktor_emisi', function (Blueprint $table) {
            $table->string('kode_faktor')->primary(); 
            $table->string('kategori_emisi_karbon');
            $table->string('sub_kategori');
            $table->decimal('nilai_faktor', 10, 2);
            $table->string('satuan');
            $table->string('kode_perusahaan');
            $table->foreign('kode_perusahaan')->references('kode_perusahaan')->on('perusahaan')->onDelete('cascade');
            $table->timestamps();
            
            // Membuat kombinasi unik dari sub_kategori dan kode_perusahaan
            $table->unique(['sub_kategori', 'kode_perusahaan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faktor_emisi');
    }
};
