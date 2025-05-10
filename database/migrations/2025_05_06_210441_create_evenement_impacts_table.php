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
        Schema::create('evenement_impacts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('evenement_id')->foreignId('evenement_id')->constrained('evenements')->onDelete('cascade');
            $table->bigInteger('impact_id')->foreignId('impact_id')->constrained('impacts')->onDelete('cascade');
            $table->string('duree');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenement_impacts');
    }
};
