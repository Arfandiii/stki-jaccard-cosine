<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('queries', function (Blueprint $table) {
            $table->id();
            $table->text('query_text');
            $table->timestamps();
        });

        Schema::create('query_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('query_id');
            $table->string('term');
            $table->unsignedInteger('tf')->default(1);
            $table->double('tfidf')->default(0);
            $table->timestamps();

            $table->foreign('query_id')->references('id')->on('queries')->onDelete('cascade');
            $table->index(['query_id','term']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('query_terms');
        Schema::dropIfExists('queries');
    }
};
