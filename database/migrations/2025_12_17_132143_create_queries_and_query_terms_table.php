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
            $table->string('letter_type')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->float('execution_time')->nullable();
            $table->integer('results_count')->nullable();
            $table->decimal('avg_cosine_score', 5, 4)->nullable();
            $table->decimal('avg_jaccard_score', 5, 4)->nullable();
            $table->decimal('cosine_time', 8, 3)->nullable();
            $table->decimal('jaccard_time', 8, 3)->nullable();
            $table->decimal('preprocessing_time', 8, 3)->nullable();
            $table->timestamps();
        });

        Schema::create('query_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('query_id');
            $table->string('term');
            $table->unsignedInteger('tf')->default(1);
            $table->double('idf')->default(0);
            $table->double('tfidf')->default(0);
            $table->double('tfidf_norm')->default(0);
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
