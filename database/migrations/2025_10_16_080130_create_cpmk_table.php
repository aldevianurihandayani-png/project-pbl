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
        Schema::create('cpmk', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mk', 10)->index();
            $table->string('kode', 20);
            $table->text('deskripsi');
            $table->unsignedTinyInteger('bobot')->default(0);
            $table->unsignedSmallInteger('urutan')->default(1);
            $table->timestamps();
            $table->foreign('kode_mk')
            ->references('kode_mk')->on('mata_kuliah')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpmk');
    }
};
