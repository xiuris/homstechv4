<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiscal_document_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiscal_document_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('message')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiscal_document_logs');
    }
};
