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
        Schema::table('users', function (Blueprint $table) {
            // pastikan kolom 'password' sudah ada di tabel users
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('password'); 
            }
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
