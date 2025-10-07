<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    Schema::create('mahasiswa', function (Blueprint $table) {
        $table->string('nim', 15)->primary();
        $table->string('nama', 100);
        $table->year('angkatan');
        $table->string('no_hp', 15)->nullable();
        $table->timestamps();
    });
}
    public function down(): void {
        Schema::dropIfExists('mahasiswas');
    }
};
