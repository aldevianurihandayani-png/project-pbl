<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Jika sudah ada, jangan buat lagi
        if (Schema::hasTable('anggota_kelompoks')) {
            return;
        }

        Schema::create('anggota_kelompoks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelompok_id');
            // kalau NIM mengandung huruf, lebih aman VARCHAR. 
            // Samakan tipe ini dengan tabel yang sudah ada sebelumnya.
            $table->string('mahasiswa_nim', 50);
            $table->timestamps();

            // Index/relasi opsional:
            // $table->foreign('kelompok_id')->references('id')->on('kelompoks')->cascadeOnDelete();
            // $table->index('mahasiswa_nim');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_kelompoks');
    }
};
