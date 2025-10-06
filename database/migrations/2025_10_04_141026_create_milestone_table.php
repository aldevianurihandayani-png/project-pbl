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
    Schema::create('milestone', function (Blueprint $table) {
        $table->id();
        $table->integer('minggu');
        $table->string('kegiatan');
        $table->date('deadline');
        $table->enum('status', ['Belum', 'Pending', 'Selesai'])->default('Belum');
        $table->boolean('approved')->default(false);
        $table->timestamp('approved_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestone');
    }
};
