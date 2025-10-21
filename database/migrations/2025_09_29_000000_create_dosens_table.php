<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('dosens', function (Blueprint $table) {
            $table->bigIncrements('id_dosen');                // Primary Key
            $table->string('nama_dosen', 255);               // Nama Dosen
            $table->string('jabatan', 155);                  // Jabatan Dosen
            $table->bigInteger('nip');                          // NIP Dosen
            $table->string('no_telp', 155);                  // Nomor Telepon
            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
