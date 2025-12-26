<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surat_vector_norms', function (Blueprint $table) {
            $table->id();
            $table->enum('surat_type',['masuk','keluar']);
            $table->unsignedBigInteger('surat_id');
            $table->double('norm')->default(0);
            $table->timestamps();
            $table->unique(['surat_type','surat_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_vector_norms');
    }
};