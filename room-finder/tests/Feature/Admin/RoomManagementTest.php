<?php

namespace Tests\Feature\Admin;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoomManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::factory()->admin()->create();
    }

    public function test_admin_can_create_a_room(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.rooms.store'), [
            'title' => 'Test Hostel Room',
            'description' => 'A nice room near campus.',
            'price' => 3500,
            'location' => 'Ayeduase',
            'room_type' => '1in1',
            'occupants_count' => 0,
            'bedrooms' => 1,
            'is_published' => '1',
            'is_verified' => '1',
        ]);

        $room = Room::query()->where('title', 'Test Hostel Room')->first();

        $this->assertNotNull($room);
        $this->assertTrue($room->is_published);
        $this->assertTrue($room->is_verified);

        $response->assertRedirect(route('admin.rooms.edit', $room));
    }

    public function test_admin_can_upload_room_images(): void
    {
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        Storage::fake('public');

        $room = Room::query()->create([
            'title' => 'Image Upload Room',
            'description' => 'Room for image test.',
            'price' => 3000,
            'location' => 'Ayeduase',
            'room_type' => '1in1',
            'bedrooms' => 1,
            'is_published' => true,
            'is_verified' => false,
        ]);

        $this->actingAs($this->admin())->post(route('admin.rooms.images.store', $room), [
            'images' => [
                UploadedFile::fake()->image('room.jpg'),
            ],
        ])->assertRedirect();

        $this->assertCount(1, $room->fresh()->images);
    }

    public function test_admin_can_update_a_room(): void
    {
        $room = Room::query()->create([
            'title' => 'Old Title',
            'description' => 'Old description.',
            'price' => 2000,
            'location' => 'Bomso',
            'room_type' => '2in1',
            'bedrooms' => 2,
            'is_published' => true,
            'is_verified' => false,
        ]);

        $this->actingAs($this->admin())->patch(route('admin.rooms.update', $room), [
            'title' => 'Updated Title',
            'description' => 'Updated description.',
            'price' => 2500,
            'location' => 'Kotei',
            'room_type' => '3in1',
            'occupants_count' => 2,
            'bedrooms' => 3,
            'is_published' => '1',
            'is_verified' => '1',
        ])->assertRedirect(route('admin.rooms.edit', $room));

        $room->refresh();

        $this->assertSame('Updated Title', $room->title);
        $this->assertSame('Kotei', $room->location);
        $this->assertSame(2, $room->occupants_count);
        $this->assertSame('1 spot left', $room->availabilityLabel());
        $this->assertTrue($room->is_verified);
    }

    public function test_admin_can_delete_a_room(): void
    {
        $room = Room::query()->create([
            'title' => 'To Delete',
            'description' => 'Will be removed.',
            'price' => 1000,
            'location' => 'Ayeduase',
            'room_type' => '1in1',
            'bedrooms' => 1,
            'is_published' => true,
            'is_verified' => false,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.rooms.destroy', $room))
            ->assertRedirect(route('admin.rooms.index'));

        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }

    public function test_admin_can_toggle_publish_status(): void
    {
        $room = Room::query()->create([
            'title' => 'Toggle Room',
            'description' => 'Toggle test.',
            'price' => 1000,
            'location' => 'Ayeduase',
            'room_type' => '1in1',
            'bedrooms' => 1,
            'is_published' => true,
            'is_verified' => false,
        ]);

        $this->actingAs($this->admin())
            ->patch(route('admin.rooms.toggle-published', $room))
            ->assertRedirect();

        $this->assertFalse($room->fresh()->is_published);
    }

    public function test_admin_cannot_set_occupants_above_room_capacity(): void
    {
        $this->actingAs($this->admin())->post(route('admin.rooms.store'), [
            'title' => 'Invalid Occupancy Room',
            'description' => 'Too many occupants.',
            'price' => 3000,
            'location' => 'Ayeduase',
            'room_type' => '2in1',
            'occupants_count' => 3,
            'bedrooms' => 2,
            'is_published' => '1',
            'is_verified' => '0',
        ])->assertSessionHasErrors('occupants_count');
    }
}
