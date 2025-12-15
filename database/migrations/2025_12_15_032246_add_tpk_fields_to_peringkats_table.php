<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peringkats', function (Blueprint $table) {
            // tambah kolom (cek dulu supaya aman kalau sudah pernah ditambah)
            if (!Schema::hasColumn('peringkats', 'jenis')) {
                $table->string('jenis', 20)->default('mahasiswa')->after('mahasiswa_id');
            }

            if (!Schema::hasColumn('peringkats', 'nama_tpk')) {
                $table->string('nama_tpk')->nullable()->after('jenis');
            }

            // change kolom: butuh doctrine/dbal kalau Laravel kamu belum support native change
            if (Schema::hasColumn('peringkats', 'nilai_total')) {
                $table->decimal('nilai_total', 10, 4)->change();
            }

            if (Schema::hasColumn('peringkats', 'mata_kuliah')) {
                $table->string('mata_kuliah')->default('PBL')->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('peringkats', function (Blueprint $table) {
            if (Schema::hasColumn('peringkats', 'nama_tpk')) {
                $table->dropColumn('nama_tpk');
            }

            if (Schema::hasColumn('peringkats', 'jenis')) {
                $table->dropColumn('jenis');
            }

            // Optional: balikin tipe kolom seperti semula (sesuaikan dengan migration awal kamu)
            // $table->decimal('nilai_total', 8, 2)->change();
            // $table->string('mata_kuliah', 100)->change();
        });
    }
};
