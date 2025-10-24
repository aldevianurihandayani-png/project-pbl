<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('milestone', function (Blueprint $table) {
            $table->bigIncrements('id_milestone'); // Primary key
            $table->string('deskripsi', 255);
            $table->date('tanggal');
            $table->boolean('status')->default(false); // 0 = belum selesai, 1 = selesai
          

            // Relasi ke tabel proyek_pbl
            $table->foreign('id_proyek_pbl')
                  ->references('id_proyek_pbl')
                  ->on('proyek_pbl')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestone');
    }
};
