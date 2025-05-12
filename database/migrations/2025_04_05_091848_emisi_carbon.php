<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('emisi_carbon', function (Blueprint $table) {
            $table->string(column: 'kode_emisi_carbon')->primary();
            $table->string('kategori_emisi_karbon');
            $table->string('sub_kategori');
            $table->decimal('nilai_aktivitas', 10, 2);
            $table->decimal('faktor_emisi', 10, 2)->nullable(); // akan diisi via trigger
            $table->decimal('kadar_emisi_karbon', 10, 2)->storedAs('nilai_aktivitas * faktor_emisi');
            $table->string('deskripsi');
            $table->string('status')->nullable();
            $table->date('tanggal_emisi');
            $table->string('kode_staff');
            $table->string('kode_faktor_emisi');
            $table->foreign('kode_staff')->references('kode_user')->on('users')->onDelete('cascade');
            $table->foreign('kode_faktor_emisi')->references('kode_faktor')->on('faktor_emisi')->onDelete('cascade');
            $table->timestamps();
        });
         // Membuat trigger untuk mengisi faktor_emisi secara otomatis
         DB::unprepared("
         CREATE TRIGGER set_faktor_emisi_before_insert 
         BEFORE INSERT ON emisi_carbon
         FOR EACH ROW
         BEGIN
             SET NEW.faktor_emisi = (
                 SELECT nilai_faktor
                 FROM faktor_emisi
                 WHERE kategori_emisi_karbon = NEW.kategori_emisi_karbon
                 AND sub_kategori = NEW.sub_kategori
                 LIMIT 1
             );
         END
     ");

     DB::unprepared("
         CREATE TRIGGER set_faktor_emisi_before_update
         BEFORE UPDATE ON emisi_carbon
         FOR EACH ROW
         BEGIN
             IF (NEW.kategori_emisi_karbon != OLD.kategori_emisi_karbon OR NEW.sub_kategori != OLD.sub_kategori) THEN
                 SET NEW.faktor_emisi = (
                     SELECT nilai_faktor
                     FROM faktor_emisi
                     WHERE kategori_emisi_karbon = NEW.kategori_emisi_karbon
                     AND sub_kategori = NEW.sub_kategori
                     LIMIT 1
                 );
             END IF;
         END
     ");
    }
    public function down(): void
    {
        Schema::dropIfExists('emisi_carbon');
        DB::unprepared('DROP TRIGGER IF EXISTS set_faktor_emisi_before_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS set_faktor_emisi_before_update');
    }
};
