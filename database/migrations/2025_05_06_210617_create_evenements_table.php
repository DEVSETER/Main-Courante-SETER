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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('date_evenement');
            $table->bigInteger('nature_evenement_id')->foreignId('nature_evenement_id')->constrained('nature_evenement')->onDelete('cascade');
            $table->string('location_id')->foreignId('location_id')->constrained('location')->onDelete('cascade');
            $table->string('location_description')->nullable();
            $table->string('description');
            $table->foreignId('redacteur')->constrained('users')->onDelete('cascade'); // Rédacteur de l’événement
            $table->boolean('consequence_sur_pdt');
            $table->string('statut');
            $table->string('date_cloture')->nullable();;
            $table->boolean('confidentialite');






            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
