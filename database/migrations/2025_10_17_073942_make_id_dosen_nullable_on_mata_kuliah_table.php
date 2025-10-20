<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
/**
 * Menghapus foreign key 'id_dosen' di tabel 'mata_kuliah'
 * Lalu mengubah kolom 'id_dosen' menjadi nullable
 * dan mengeset foreign key lagi dengan aksi 'nullOnDelete'
 */
    public function up(): void
    {
        // kalau FK sudah ada, lepas dulu
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->dropForeign(['id_dosen']);
        });

        // ubah jadi nullable (perlu doctrine/dbal)
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dosen')->nullable()->change();
        });

        // pasang lagi FK, biar kalau user dihapus, kolom jadi NULL
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->foreign('id_dosen')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->dropForeign(['id_dosen']);
            $table->unsignedBigInteger('id_dosen')->nullable(false)->change();
            $table->foreign('id_dosen')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
