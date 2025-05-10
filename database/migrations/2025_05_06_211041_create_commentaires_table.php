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
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('evenement_id')->foreignId('evenement_id')->constrained('evenements')->onDelete('cascade');
            $table->string('redacteur')->foreignId('redacteur')->constrained('users')->onDelete('cascade');
            $table->string('text');
            $table->enum('type', ['simple', 'autre_entite','visa_encadrant','avis_srcof'])->default('simple');
            $table->string('date');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentaires');
    }
};
