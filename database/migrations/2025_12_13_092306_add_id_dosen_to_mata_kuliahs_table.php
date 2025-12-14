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
    Schema::table('mata_kuliahs', function (Blueprint $table) {
        $table->unsignedBigInteger('id_dosen')->nullable()->after('id_matkul'); // sesuaikan after kolommu
        $table->foreign('id_dosen')
              ->references('id_dosen')->on('dosens')
              ->nullOnDelete(); // kalau dosen dihapus, id_dosen jadi null (aman)
    });
}

public function down(): void
{
    Schema::table('mata_kuliahs', function (Blueprint $table) {
        $table->dropForeign(['id_dosen']);
        $table->dropColumn('id_dosen');
    });
}
};