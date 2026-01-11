<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('query_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('query_id');
            $table->string('method'); // jaccard | cosine
            $table->string('surat_type'); // masuk | keluar
            $table->unsignedBigInteger('surat_id');
            $table->float('score');
            $table->timestamps();

            $table->foreign('query_id')->references('id')->on('queries')->onDelete('cascade');
            $table->index(['query_id', 'method']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('query_results');
    }
};
