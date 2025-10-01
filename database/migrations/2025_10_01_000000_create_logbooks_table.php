<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('aktivitas');
            $table->text('keterangan');
            $table->string('foto')->nullable(); // path foto (public storage)
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('logbooks');
    }
};
