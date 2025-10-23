<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rubrik_penilaian', function (Blueprint $table) {
            $table->id();

            // sesuaikan tipe kolom nim di tabel 'mahasiswa' kamu (string/varchar)
            $table->string('mahasiswa_nim');

            // FK ke tabel komponen rubrik. Dari DB kamu, tabel yang dipakai aplikasi kemungkinan 'rubriks'
            $table->foreignId('rubrik_id')->constrained('rubriks')->cascadeOnDelete();

            $table->decimal('nilai', 5, 2)->nullable(); // mis: 0..100 dengan 2 desimal
            $table->timestamps();

            // kombinasi unik: 1 mahasiswa hanya 1 nilai per rubrik
            $table->unique(['mahasiswa_nim', 'rubrik_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rubrik_penilaian');
    }
};
