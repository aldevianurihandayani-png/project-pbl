<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            // primary key pakai kode_mk (string)
            $table->string('kode_mk', 20)->primary();

            $table->string('nama_mk', 150);
            $table->unsignedTinyInteger('sks');
            $table->unsignedTinyInteger('semester');

            // kelas A–E
            $table->string('kelas', 2)->nullable();

            // data dosen
            $table->string('nama_dosen', 150)->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->string('nip', 50)->nullable();
            $table->string('no_telp', 50)->nullable();

            $table->unsignedBigInteger('id_dosen')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
