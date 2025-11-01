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
        Schema::create('document_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doc_id');
            $table->enum('doc_type',['masuk','keluar']);
            $table->string('term');
            $table->unsignedInteger('tf')->default(1);
            $table->unique(['doc_id','doc_type','term']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_terms');
    }
};
