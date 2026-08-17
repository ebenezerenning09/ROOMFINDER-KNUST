<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        if (! Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->default(false);
            });
        }

        if (! Schema::hasColumn('rooms', 'is_published')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->boolean('is_published')->default(true);
            });
        }

        if (! Schema::hasColumn('rooms', 'is_verified')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->boolean('is_verified')->default(false);
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['is_published', 'is_verified']);
        });
    }
};
