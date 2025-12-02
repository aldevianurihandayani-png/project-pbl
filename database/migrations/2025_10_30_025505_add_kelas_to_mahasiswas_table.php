<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        $t = Schema::hasTable('mahasiswas') ? 'mahasiswas' : (Schema::hasTable('mahasiswa') ? 'mahasiswa' : null);

        if ($t && !Schema::hasColumn($t, 'kelas')) {
            Schema::table($t, function (Blueprint $table) {
                $table->string('kelas', 5)->nullable()->after('angkatan');
            });
        }
    }

    public function down(): void {
        $t = Schema::hasTable('mahasiswas') ? 'mahasiswas' : (Schema::hasTable('mahasiswa') ? 'mahasiswa' : null);

        if ($t && Schema::hasColumn($t, 'kelas')) {
            Schema::table($t, function (Blueprint $table) {
                $table->dropColumn('kelas');
            });
        }
    }
};
