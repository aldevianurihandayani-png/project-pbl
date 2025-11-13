<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_tpks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->float('keaktifan');       // nilai keaktifan mahasiswa
            $table->float('nilai_kelompok');  // nilai kerja kelompok
            $table->float('nilai_dosen');     // nilai dosen pembimbing/penguji
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_tpks');
    }
};
