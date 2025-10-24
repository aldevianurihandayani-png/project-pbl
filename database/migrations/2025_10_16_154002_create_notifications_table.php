<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // judul notifikasi
            $table->string('type')->default('general'); // tipe notifikasi, bisa 'info', 'warning', dsb
            $table->text('message')->nullable(); // isi pesan
            $table->boolean('is_read')->default(false); // status dibaca/belum
            $table->unsignedBigInteger('user_id')->nullable(); // notifikasi untuk user tertentu (nullable)
            $table->string('link_url')->nullable(); // tautan ke halaman terkait
            $table->timestamps(); // created_at & updated_at

            // Relasi ke tabel users (jika ada)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
