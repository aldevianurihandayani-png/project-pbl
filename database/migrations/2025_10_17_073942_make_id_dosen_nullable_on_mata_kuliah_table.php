<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Pastikan kolom ada dulu
        if (!Schema::hasColumn('mata_kuliah', 'id_dosen')) {
            return;
        }

        // 1) Cari nama foreign key yang menaut ke kolom id_dosen (jika ada)
        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'mata_kuliah'
              AND COLUMN_NAME = 'id_dosen'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");

        // 2) Drop FK jika memang ada
        if ($fk?->CONSTRAINT_NAME) {
            DB::statement("ALTER TABLE `mata_kuliah` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // 3) Ubah kolom jadi nullable
        //    NOTE: butuh doctrine/dbal -> composer require doctrine/dbal
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dosen')->nullable()->change();
        });

        // 4) (Opsional) Pasang lagi FK dengan nullOnDelete/nullOnUpdate
        //    Sesuaikan nama tabel dosen/ users sesuai skema kamu.
        //    Contoh: jika relasinya ke tabel 'users' kolom 'id':
        // Schema::table('mata_kuliah', function (Blueprint $table) {
        //     $table->foreign('id_dosen')->references('id')->on('users')->nullOnDelete();
        // });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('mata_kuliah', 'id_dosen')) {
            return;
        }

        // 1) Drop FK apapun yang masih menaut ke id_dosen (jika ada)
        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'mata_kuliah'
              AND COLUMN_NAME = 'id_dosen'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");
        if ($fk?->CONSTRAINT_NAME) {
            DB::statement("ALTER TABLE `mata_kuliah` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // 2) Kembalikan jadi NOT NULL (kalau sebelumnya begitu)
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dosen')->nullable(false)->change();
        });

        // 3) (Opsional) Pasang lagi FK strict (tanpa nullOnDelete)
        // Schema::table('mata_kuliah', function (Blueprint $table) {
        //     $table->foreign('id_dosen')->references('id')->on('users')->cascadeOnDelete();
        // });
    }
};
