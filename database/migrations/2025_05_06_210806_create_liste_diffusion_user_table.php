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
        Schema::create('liste_diffusion_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->foreignId('user_id')->constrained('user')->onDelete('cascade');
            $table->bigInteger('liste_diffusion_id')->foreignId('liste_diffusion_id')->constrained('liste_diffusion')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liste_diffusion_user');
    }
};
