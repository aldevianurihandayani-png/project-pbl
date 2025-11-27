<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // tambah hanya jika belum ada
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type', 32)->default('info')->after('title');
            }
            if (!Schema::hasColumn('notifications', 'link_url')) {
                $table->string('link_url')->nullable()->after('message');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('notifications', 'link_url')) {
                $table->dropColumn('link_url');
            }
        });
    }
};
