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
        Schema::create('diffusions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('evenement_id')->foreignId('evenement_id')->constrained('evenements')->onDelete('cascade');
            $table->string('nom_liste_difusion');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diffusions');
    }
};
