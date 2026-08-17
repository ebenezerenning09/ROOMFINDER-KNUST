<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('rooms')) {
            if (DB::getDriverName() === 'pgsql') {
                DB::statement("ALTER TABLE rooms ALTER COLUMN room_type SET DEFAULT '1in1'");
            }

            return;
        }

        Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->decimal('price', 10, 2);
                $table->string('location');
                $table->string('room_type')->default('1in1');
                $table->integer('bedrooms');
                $table->string('image')->nullable();
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally empty, prior migrations own the rooms table lifecycle.
    }
};
