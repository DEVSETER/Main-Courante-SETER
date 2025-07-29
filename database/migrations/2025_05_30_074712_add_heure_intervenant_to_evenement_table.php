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

            $table->dateTime('heure_appel_intervenant')->nullable()->after('statut');
            $table->dateTime('heure_arrive_intervenant')->nullable()->after('heure_appel_intervenant');
            $table->string('piece_jointe')->nullable()->after('heure_arrive_intervenant');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
             $table->dropColumn(['heure_appel_intervenant', 'heure_arrive_intervenant', 'piece_jointe']);
    
        });
    }
};
