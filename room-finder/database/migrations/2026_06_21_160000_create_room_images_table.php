<?php

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::create('room_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Room::query()->each(function (Room $room): void {
            if (! $room->image) {
                return;
            }

            RoomImage::query()->create([
                'room_id' => $room->id,
                'path' => $room->image,
                'sort_order' => 0,
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_images');
    }
};
