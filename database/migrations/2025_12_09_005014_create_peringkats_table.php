<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeringkatsTable extends Migration
{
    public function up()
    {
        Schema::create('peringkats', function (Blueprint $table) {
            $table->id();

            // sementara tanpa constraint foreign key
            $table->unsignedBigInteger('mahasiswa_id');

            $table->string('mata_kuliah');          
            $table->decimal('nilai_total', 5, 2);  
            $table->unsignedInteger('peringkat');  
            $table->string('semester')->nullable();
            $table->string('tahun_ajaran')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('peringkats');
    }
}
