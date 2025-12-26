<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surat_terms', function (Blueprint $table) {
            $table->id();
            $table->enum('surat_type', ['masuk','keluar']);
            $table->unsignedBigInteger('surat_id');
            $table->string('term');
            $table->unsignedInteger('tf')->default(1);
            $table->double('tfidf')->default(0);
            $table->timestamps();

            // index
            $table->unique(['surat_type','surat_id','term']);
            $table->index(['term','surat_type']);
            $table->index(['surat_type','surat_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_terms');
    }
};