<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyek_pbl', function (Blueprint $table) {
            $table->bigIncrements('id_proyek_pbl');    // PK
            $table->string('judul', 255);
            $table->date('tanggal')->nullable();

            // FK
            $table->unsignedBigInteger('kode_mk');      // → ke tabel matakuliah (sesuaikan nama tabel/Pk)
            $table->unsignedBigInteger('id_dosen');     // → ke tabel dosen (Pk-nya apa?)
            $table->unsignedBigInteger('id_kelompok');  // → ke tabel kelompok/kelompoks

            $table->timestamps();

            // Foreign keys — SESUAIKAN nama tabel & PK target!
            $table->foreign('kode_mk')
                  ->references('kode_mk')->on('matakuliah')
                  ->onDelete('cascade');

            $table->foreign('id_dosen')
                  ->references('id_dosen')->on('dosen')
                  ->onDelete('cascade');

            // Perhatikan: tabel kamu “kelompok” atau “kelompoks”?
            $table->foreign('id_kelompok')
                  ->references('id_kelompok')->on('kelompok')   // ganti ke 'kelompoks' jika itu nama tabelnya
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyek_pbl');
    }
};
