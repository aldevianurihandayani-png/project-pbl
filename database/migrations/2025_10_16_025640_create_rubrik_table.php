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
        if (!Schema::hasTable('rubrik')) {
            Schema::create('rubrik', function (Blueprint $table) {
                $table->id();
                $table->string('kode_mk', 10);
                $table->string('nama_rubrik', 150);
                $table->unsignedTinyInteger('bobot')->comment('0-100');
                $table->unsignedSmallInteger('urutan')->default(1);
                $table->text('deskripsi')->nullable();
                $table->timestamps();

                $table->foreign('kode_mk')->references('kode_mk')->on('mata_kuliah')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubrik');
    }
};
