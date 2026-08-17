<?php

use App\Models\Room;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table): void {
            $table->string('slug')->nullable()->after('title');
        });

        // Backfill unique slugs for existing rooms.
        Room::query()->orderBy('id')->get()->each(function (Room $room): void {
            $room->slug = Room::generateUniqueSlug((string) $room->title, $room->id);
            $room->saveQuietly();
        });

        Schema::table('rooms', function (Blueprint $table): void {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table): void {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
