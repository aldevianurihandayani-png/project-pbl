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
        Schema::create('kelompok_anggota', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kelompok_id')->constrained('kelompoks')->cascadeOnDelete();
        $table->string('nim', 30);            // nim anggota
        $table->string('nama')->nullable();   // opsional kalau ingin tampil nama juga
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_kelompoks');
    }
};
