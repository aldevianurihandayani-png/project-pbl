<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tpk_kelompoks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');        // nama kelompok
            $table->float('review_uts');   // nilai review uts
            $table->float('review_uas');   // nilai review uas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tpk_kelompoks');
    }
};
