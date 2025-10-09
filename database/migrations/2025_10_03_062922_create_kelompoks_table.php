<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void {
        Schema::create('kelompoks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                 // "Kelompok 1"
            $table->string('kelas', 20);           // "TI-3A"
            $table->text('anggota')->nullable();   // comma separated untuk simple (bisa dipisah tabel pivot nanti)
            $table->string('dosen_pembimbing')->nullable();
            $table->timestamps();

            $table->index('kelas');
        });
    }
    public function down(): void {
        Schema::dropIfExists('kelompoks');
    }
};



