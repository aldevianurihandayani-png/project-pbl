<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamps(); // created_at & updated_at (nullable)
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};

