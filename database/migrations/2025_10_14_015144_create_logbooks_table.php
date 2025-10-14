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
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // database/migrations/xxxx_xx_xx_create_logbooks_table.php
Schema::create('logbooks', function (Blueprint $t) {
  $t->id();
  $t->foreignId('user_id')->constrained()->cascadeOnDelete();
  $t->date('tanggal');
  $t->unsignedTinyInteger('minggu')->nullable();
  $t->string('aktivitas',150);
  $t->text('rincian')->nullable();
  $t->string('lampiran_path')->nullable();      // file bukti
  $t->enum('status',['menunggu','disetujui','ditolak'])->default('menunggu');
  $t->text('komentar_dosen')->nullable();
  $t->timestamps();
});

    }
};
