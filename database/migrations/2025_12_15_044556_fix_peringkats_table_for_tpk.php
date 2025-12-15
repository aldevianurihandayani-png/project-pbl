<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peringkats', function (Blueprint $table) {

            // 1️⃣ mahasiswa_id WAJIB nullable (ini sumber error kamu)
            $table->string('mahasiswa_id')->nullable()->change();

            // 2️⃣ jenis (mahasiswa / kelompok)
            if (!Schema::hasColumn('peringkats', 'jenis')) {
                $table->string('jenis', 20)->default('mahasiswa')->after('mahasiswa_id');
            }

            // 3️⃣ nama_tpk (nama kelompok / fallback nama mahasiswa)
            if (!Schema::hasColumn('peringkats', 'nama_tpk')) {
                $table->string('nama_tpk')->nullable()->after('jenis');
            }

            // 4️⃣ nilai_total decimal presisi (0–1)
            $table->decimal('nilai_total', 10, 4)->change();

            // 5️⃣ soft delete (undo)
            if (!Schema::hasColumn('peringkats', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('peringkats', function (Blueprint $table) {

            // rollback soft delete
            if (Schema::hasColumn('peringkats', 'deleted_at')) {
                $table->dropSoftDeletes();
            }

            // rollback nama_tpk
            if (Schema::hasColumn('peringkats', 'nama_tpk')) {
                $table->dropColumn('nama_tpk');
            }

            // rollback jenis
            if (Schema::hasColumn('peringkats', 'jenis')) {
                $table->dropColumn('jenis');
            }

            // ⚠️ mahasiswa_id JANGAN dibalikin NOT NULL
            // karena sudah dipakai oleh kelompok
        });
    }
};
