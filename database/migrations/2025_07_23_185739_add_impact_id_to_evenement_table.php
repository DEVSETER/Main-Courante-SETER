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
        Schema::disableForeignKeyConstraints();
 
        Schema::table('evenements', function (Blueprint $table) {
             $table->unsignedBigInteger('impact_id')->nullable()->after('entite_id');

            $table->foreign('impact_id')->references('id')->on('impacts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
              $table->dropForeign(['impact_id']);

             $table->dropColumn('impact_id');
        });
    }
};
