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
            $table->string('nim', 15)->primary();         // NIM sebagai PK
            $table->string('nama', 100);
            $table->string('email')->unique();
            $table->string('no_hp', 15)->nullable();
            $table->integer('angkatan');
            $table->string('kelas');
            $table->unsignedBigInteger('id_dosen');       // relasi ke dosens
            $table->timestamps();

            // Relasi ke tabel dosens
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
