<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Seed sample student hostels around KNUST, Kumasi.
     */
    public function run(): void
    {
        RoomImage::query()->delete();
        Room::query()->delete();

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER SEQUENCE rooms_id_seq RESTART WITH 1');
            DB::statement('ALTER SEQUENCE room_images_id_seq RESTART WITH 1');
        }

        $rooms = [
            [
                'title' => 'Cozy 1in1 near KNUST main gate',
                'description' => 'Self-contained hostel room in Ayeduase, a short walk from the main gate. Includes running water, prepaid electricity meter, and 24-hour security. Ideal for a final-year student who wants quiet study time.',
                'price' => 4800.00,
                'location' => 'Ayeduase',
                'room_type' => '1in1',
                'bedrooms' => 1,
            ],
            [
                'title' => '2in1 flat off Ayeduase Road',
                'description' => 'Two students share a furnished flat with a common kitchen and washroom. Wi-Fi ready, close to campus shuttle stops and food vendors along Ayeduase Road.',
                'price' => 3200.00,
                'location' => 'Ayeduase',
                'room_type' => '2in1',
                'occupants_count' => 1,
                'bedrooms' => 2,
            ],
            [
                'title' => 'Bright 1in1 ensuite behind Ayeduase market',
                'description' => 'Single ensuite hostel room with wardrobe and ceiling fan. Five minutes to lecture halls by trotro. Landlord lives on-site; water tank and borehole backup available.',
                'price' => 4500.00,
                'location' => 'Ayeduase',
                'room_type' => '1in1',
                'bedrooms' => 1,
            ],
            [
                'title' => '3in1 student house in Bomso',
                'description' => 'Three-bedroom hostel with shared living area. Each tenant has a lockable room. Bomso trotro route to campus runs every few minutes; shops and chop bars nearby.',
                'price' => 2800.00,
                'location' => 'Bomso',
                'room_type' => '3in1',
                'occupants_count' => 2,
                'bedrooms' => 3,
            ],
            [
                'title' => 'Quiet 1in1 on Bomso High Street',
                'description' => 'Furnished hostel room on the first floor of a gated compound. Prepaid meter, shared kitchen, and paved compound parking. Popular with engineering students.',
                'price' => 4200.00,
                'location' => 'Bomso',
                'room_type' => '1in1',
                'bedrooms' => 1,
            ],
            [
                'title' => '2in1 flat near Bomso junction',
                'description' => 'Two students per flat with separate bedrooms and a shared bathroom. Stable power supply, DSTV hook-up in the common area, and easy access to KNUST via Bomso–Ayeduase road.',
                'price' => 3000.00,
                'location' => 'Bomso',
                'room_type' => '2in1',
                'bedrooms' => 2,
            ],
            [
                'title' => '1in1 with balcony in Kotei',
                'description' => 'Spacious hostel room in Kotei with a small balcony and built-in wardrobe. Gated estate with security guard; borehole water and ECG prepaid meter included.',
                'price' => 3800.00,
                'location' => 'Kotei',
                'room_type' => '1in1',
                'bedrooms' => 1,
            ],
            [
                'title' => 'Budget 2in1 rooms at Kotei',
                'description' => 'Two-bedroom unit rented per room to KNUST students. Shared kitchen and washroom; walking distance to Kotei trotro station. Great value for first-year students.',
                'price' => 2600.00,
                'location' => 'Kotei',
                'room_type' => '2in1',
                'bedrooms' => 2,
            ],
            [
                'title' => '4in1 hostel-style room in New Site',
                'description' => 'Four students share a self-contained unit with individual beds and study desks. New Site keeps rent affordable; regular trotro service to campus and provision shops along the main road.',
                'price' => 2500.00,
                'location' => 'New Site',
                'room_type' => '4in1',
                'occupants_count' => 4,
                'bedrooms' => 4,
            ],
            [
                'title' => 'Premium 1in1 ensuite in New Site',
                'description' => 'Top-floor single ensuite with tiled floors, personal meter, and reliable Wi-Fi. New Site is close to campus with good trotro links and a lively student community.',
                'price' => 5000.00,
                'location' => 'New Site',
                'room_type' => '1in1',
                'bedrooms' => 1,
            ],
            [
                'title' => '2in1 flat near Boadi roundabout',
                'description' => 'Two students share a furnished flat with separate bedrooms and a shared kitchen. Boadi offers a quieter residential feel with easy access to KNUST via trotro and nearby food spots.',
                'price' => 2900.00,
                'location' => 'Boadi',
                'room_type' => '2in1',
                'bedrooms' => 2,
            ],
            [
                'title' => 'Homestay with family in Ayeduase',
                'description' => 'Live with a welcoming host family in Ayeduase. Includes a private bedroom, shared kitchen access, home-cooked meals on request, and a safe, quiet environment ideal for students who prefer a family setting over a hostel.',
                'price' => 3500.00,
                'location' => 'Ayeduase',
                'room_type' => 'homestay',
                'bedrooms' => 1,
            ],
        ];

        foreach ($rooms as $index => $roomData) {
            $count = Room::PLACEHOLDER_IMAGE_COUNT;
            $primaryImage = Room::placeholderImageUrl(($index % $count) + 1);

            $room = Room::query()->create([
                ...$roomData,
                'image' => $primaryImage,
                'is_published' => true,
                'is_verified' => true,
            ]);

            $offsets = [0, 3, 6, 9, 11];
            $imageNumbers = array_values(array_unique(array_map(
                fn (int $offset) => (($index + $offset) % $count) + 1,
                $offsets
            )));

            foreach ($imageNumbers as $sortOrder => $imageNumber) {
                RoomImage::query()->create([
                    'room_id' => $room->id,
                    'path' => Room::placeholderImageUrl($imageNumber),
                    'sort_order' => $sortOrder,
                ]);
            }
        }
    }
}
