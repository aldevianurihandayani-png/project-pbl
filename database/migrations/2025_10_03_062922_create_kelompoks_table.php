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
        Schema::create('kelompoks', function (Blueprint $table) {
        $table->id();
        $table->string('judul');          // judul proyek/kelompok
        $table->string('topik')->nullable();
        $table->unsignedBigInteger('id_dosen')->nullable(); // opsional, jika ada relasi dosen
        $table->timestamps();
        });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompoks');
    }
};

