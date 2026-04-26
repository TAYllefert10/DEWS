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
            // ✅ Campos para Google OAuth
            $table->string('google_id')->nullable()->unique()->after('password');
            $table->string('avatar')->nullable()->after('email');
            $table->string('google_token')->nullable()->after('google_id');
            $table->string('google_refresh_token')->nullable()->after('google_token');

            // ✅ Hacer password nullable para usuarios que solo usan Google
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'google_id',
                'avatar',
                'google_token',
                'google_refresh_token'
            ]);
            // Restaurar password como required
            $table->string('password')->nullable(false)->change();
        });
    }
};
