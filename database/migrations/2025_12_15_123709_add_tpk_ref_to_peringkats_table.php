<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('peringkats', function (Blueprint $table) {
        $table->string('tpk_type', 20)->nullable()->after('jenis');
        $table->unsignedBigInteger('tpk_id')->nullable()->after('tpk_type');
    });
}

public function down(): void
{
    Schema::table('peringkats', function (Blueprint $table) {
        $table->dropColumn(['tpk_type', 'tpk_id']);
    });
}
};