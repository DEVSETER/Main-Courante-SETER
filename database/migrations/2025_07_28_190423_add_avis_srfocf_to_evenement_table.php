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
            Schema::table('evenements', function (Blueprint $table) {
            $table->text('avis_srcof')->nullable()->after('description');
            $table->string('visa_encadrant')->nullable()->after('avis_srcof');
        });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
               $table->dropColumn(['avis_srcof', 'visa_encadrant']);
        });
    }
};
