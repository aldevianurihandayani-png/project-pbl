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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->bigInteger('nim')->primary();         // Primary Key
            $table->string('nama');                       // Nama Mahasiswa
            $table->string('email')->unique();            // Email Mahasiswa
            $table->string('no_telp')->nullable();        // Nomor Telepon
            $table->integer('angkatan');                  // Angkatan
            $table->string('kelas');                     // Kelas
            $table->unsignedBigInteger('id_dosen');       // Foreign Key ke tabel dosen

            $table->timestamps();

            // Relasi ke tabel dosen (pastikan tabel dosens sudah ada)
            $table->foreign('id_dosen')
                  ->references('id_dosen')
                  ->on('dosens')
                  ->onDelete('cascade');
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
