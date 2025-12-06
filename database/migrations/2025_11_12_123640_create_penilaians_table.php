<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penilaians', function (Blueprint $t) {
            $t->id(); // BIGINT UNSIGNED

            // === Cocokkan tipe: mahasiswas.id kemungkinan INT UNSIGNED
            $t->unsignedInteger('mahasiswa_id');
            $t->foreign('mahasiswa_id')
              ->references('id')->on('mahasiswas')
              ->cascadeOnDelete();

            // FK ke mata_kuliah.kode_mk (STRING)
            $t->string('matakuliah_kode');
            $t->foreign('matakuliah_kode')
              ->references('kode_mk')->on('mata_kuliah')
              ->cascadeOnDelete();

            // === Cocokkan tipe: kelas.id kemungkinan INT UNSIGNED
            $t->unsignedInteger('kelas_id')->nullable();
            $t->foreign('kelas_id')
              ->references('id')->on('kelas')
              ->nullOnDelete();

            // users.id biasanya BIGINT UNSIGNED → boleh pakai foreignId
            $t->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();

            $t->json('komponen')->nullable();
            $t->decimal('nilai_akhir', 5, 2)->default(0);
            $t->timestamps();

            $t->unique(['mahasiswa_id','matakuliah_kode','kelas_id','dosen_id'], 'uniq_penilaian_satu_dosen');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
