<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('pending_registrations', function (Blueprint $t) {
      $t->id();
      $t->string('name');
      $t->string('email')->unique();
      $t->string('password'); // sudah di-hash
      $t->string('role', 50);
      $t->string('nim', 20)->nullable();
      $t->string('prodi', 100)->nullable();
      $t->timestamp('verification_sent_at')->nullable();
      $t->timestamp('verification_expires_at')->nullable();
      $t->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('pending_registrations');
  }
};
