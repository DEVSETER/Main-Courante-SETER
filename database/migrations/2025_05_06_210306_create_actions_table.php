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
        // Schema::create('actions', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('commentaire')->nullable();
        //     $table->bigInteger('evenement_id')->foreignId('evenement_id')->constrained('evenements')->onDelete('cascade');
        //     $table->bigInteger('auteur_id')->foreignId('auteur_id')->constrained('users')->onDelete('cascade');

        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
