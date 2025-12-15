<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bobot_peringkats', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['mahasiswa', 'kelompok'])->unique();

            // mahasiswa
            $table->unsignedInteger('mhs_keaktifan')->default(0);
            $table->unsignedInteger('mhs_nilai_kelompok')->default(0);
            $table->unsignedInteger('mhs_nilai_dosen')->default(0);

            // kelompok
            $table->unsignedInteger('klp_review_uts')->default(0);
            $table->unsignedInteger('klp_review_uas')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bobot_peringkats');
    }
};
