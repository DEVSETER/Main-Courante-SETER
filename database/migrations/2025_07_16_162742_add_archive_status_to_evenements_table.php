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
        Schema::table('evenements', function (Blueprint $table) {
            // Modifier l'enum pour inclure 'archive'
            $table->enum('statut', allowed: ['en_cours', 'cloture', 'archive'])->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            // Retour à l'ancien enum
            $table->enum('statut', ['en_cours', 'cloture', 'archive'])->change();
        });
    }
};
