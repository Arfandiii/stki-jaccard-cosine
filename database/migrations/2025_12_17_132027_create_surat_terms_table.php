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

            $table->decimal('tf', 8, 4)->default(0);
            $table->decimal('tfidf', 10, 6)->default(0);

            $table->timestamps();

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