<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('data_tpks', function (Blueprint $table) {
        $table->string('mahasiswa_nim', 15)->nullable()->after('id');
        $table->foreign('mahasiswa_nim')->references('nim')->on('mahasiswas')->nullOnDelete();
    });
}

public function down()
{
    Schema::table('data_tpks', function (Blueprint $table) {
        $table->dropForeign(['mahasiswa_nim']);
        $table->dropColumn('mahasiswa_nim');
    });
}
};
