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
        Schema::create('action_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('action_id')->foreignId('action_id')->constrained('action')->onDelete('cascade');
            $table->bigInteger('user_id')->foreignId('user_id')->constrained('user')->onDelete('cascade');
            $table->enum('type_action_user', ['REDACTEUR', 'INTERVENANT'])->default('REDACTEUR');
            $table->timestamps();
        });
    }

    // protected $fillable = [
    //     'action_id',
    //     'evenement_id',
    //     'user_id',
    //     'type_action_user'
    // ];


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_user');
    }
};
