<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Tambah kolom hanya kalau belum ada (AMAN!)
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('pending'); 
            }

            if (!Schema::hasColumn('users', 'requested_role')) {
                $table->string('requested_role')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('users', 'requested_role')) {
                $table->dropColumn('requested_role');
            }
        });
    }
};
