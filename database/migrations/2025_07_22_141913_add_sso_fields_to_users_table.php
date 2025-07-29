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
        Schema::table('users', function (Blueprint $table) {
             Schema::table('users', function (Blueprint $table) {
            $table->string('sso_provider')->nullable()->after('email');
            $table->string('sso_id')->nullable()->after('sso_provider');
            $table->timestamp('last_login_at')->nullable()->after('sso_id');
            $table->string('last_login_method')->nullable()->after('last_login_at');
        });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sso_provider', 'sso_id', 'last_login_at', 'last_login_method']);
        });
    }
};
