<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penilaian_anggota_kelompok', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('kelompok_id');

            // Mahasiswa yang menilai
            $table->string('penilai_nim', 30);

            // Mahasiswa yang dinilai
            $table->string('dinilai_nim', 30);

            // Nilai kontribusi
            $table->unsignedTinyInteger('nilai'); // 80–100

            // Catatan/keterangan
            $table->text('keterangan')->nullable();

            $table->timestamps();

            // Cegah penilaian ganda
            $table->unique(
                ['kelompok_id', 'penilai_nim', 'dinilai_nim'],
                'unik_penilaian_anggota'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_anggota_kelompok');
    }
};
