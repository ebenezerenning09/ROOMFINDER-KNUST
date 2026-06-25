<?php

namespace Tests\Feature\Admin;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_unpublished_rooms_are_hidden_from_browse_page(): void
    {
        $published = Room::query()->create([
            'title' => 'Published Room',
            'description' => 'Visible listing.',
            'price' => 3000,
            'location' => 'Ayeduase',
            'room_type' => '1in1',
            'bedrooms' => 1,
            'is_published' => true,
            'is_verified' => true,
        ]);

        Room::query()->create([
            'title' => 'Draft Room',
            'description' => 'Hidden listing.',
            'price' => 3000,
            'location' => 'Ayeduase',
            'room_type' => '1in1',
            'bedrooms' => 1,
            'is_published' => false,
            'is_verified' => false,
        ]);

        $response = $this->get(route('rooms.index'));

        $response->assertOk();
        $response->assertSee($published->title);
        $response->assertDontSee('Draft Room');
    }

    public function test_unpublished_room_detail_returns_not_found(): void
    {
        $room = Room::query()->create([
            'title' => 'Draft Room',
            'description' => 'Hidden listing.',
            'price' => 3000,
            'location' => 'Ayeduase',
            'room_type' => '1in1',
            'bedrooms' => 1,
            'is_published' => false,
            'is_verified' => false,
        ]);

        $this->get(route('rooms.show', $room))->assertNotFound();
    }
}
