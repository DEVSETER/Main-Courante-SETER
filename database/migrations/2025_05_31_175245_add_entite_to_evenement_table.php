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
            $table->string('entite')->nullable()->after('piece_jointe');
            $table->foreignId('entite_id')->constrained('entites')->onDelete('cascade');
            $table->index(['entite', 'entite_id'], 'evenement_entite_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
             $table->dropColumn(['entite', 'entite_id']);

        });
    }
};
