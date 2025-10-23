<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // contoh kolom — sesuaikan dengan kebutuhanmu
            if (!Schema::hasColumn('users', 'nidn')) {
                $table->string('nidn')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'prodi')) {
                $table->string('prodi')->nullable()->after('nidn');
            }
            if (!Schema::hasColumn('users', 'avatar_url')) {
                $table->string('avatar_url')->nullable()->after('prodi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // drop kolom balik
            if (Schema::hasColumn('users', 'avatar_url')) $table->dropColumn('avatar_url');
            if (Schema::hasColumn('users', 'prodi')) $table->dropColumn('prodi');
            if (Schema::hasColumn('users', 'nidn')) $table->dropColumn('nidn');
        });
    }
};
