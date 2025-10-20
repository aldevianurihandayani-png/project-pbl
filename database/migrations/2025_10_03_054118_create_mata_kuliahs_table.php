<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
<<<<<<< HEAD
    /**
     * Run the migrations.
     */
=======
>>>>>>> bbcfba2 (commit noorma)
    public function up(): void
    {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->string('kode_mk', 20)->primary();
            $table->string('nama_mk');
<<<<<<< HEAD
            $table->integer('sks');
            $table->integer('semester');
            $table->unsignedBigInteger('id_dosen');
            $table->timestamps();


            $table->foreign('id_dosen')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
=======
            $table->unsignedTinyInteger('sks');
            $table->unsignedTinyInteger('semester');
            $table->unsignedBigInteger('id_dosen')->nullable();  // ✅ perbaikan
            $table->timestamps();

            $table->foreign('id_dosen')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

>>>>>>> bbcfba2 (commit noorma)
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
<<<<<<< HEAD
};
=======
};
>>>>>>> bbcfba2 (commit noorma)
