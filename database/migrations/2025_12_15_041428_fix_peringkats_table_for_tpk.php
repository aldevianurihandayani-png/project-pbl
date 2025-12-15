<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peringkats', function (Blueprint $table) {

            // 🔴 INI PENYEBAB ERROR UTAMA
            $table->string('mahasiswa_id', 255)->nullable()->change();

            // pastikan kolom ini ada & nullable
            if (!Schema::hasColumn('peringkats', 'nama_tpk')) {
                $table->string('nama_tpk', 255)->nullable();
            }

            // default jenis
            $table->string('jenis', 20)->default('mahasiswa')->change();

            // precision nilai
            $table->decimal('nilai_total', 10, 4)->change();

            // default matkul
            $table->string('mata_kuliah', 255)->default('PBL')->change();
        });
    }

    public function down(): void
    {
        Schema::table('peringkats', function (Blueprint $table) {
            $table->string('mahasiswa_id', 255)->nullable(false)->change();
        });
    }
};
