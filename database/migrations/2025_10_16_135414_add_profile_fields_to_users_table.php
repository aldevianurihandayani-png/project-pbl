<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // tambahkan jika belum ada
            if (!Schema::hasColumn('users', 'nidn')) {
                $table->string('nidn')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'prodi')) {
                $table->string('prodi')->nullable()->after('nidn');
            }
            if (!Schema::hasColumn('users', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable()->after('prodi'); // path foto
            }
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // drop hanya kalau ada
            if (Schema::hasColumn('users', 'profile_photo_path')) {
                $table->dropColumn('profile_photo_path');
            }
            if (Schema::hasColumn('users', 'prodi')) {
                $table->dropColumn('prodi');
            }
            if (Schema::hasColumn('users', 'nidn')) {
                $table->dropColumn('nidn');
            }
           
        });
    }
};
