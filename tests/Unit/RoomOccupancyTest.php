<?php

namespace Tests\Unit;

use App\Models\Room;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RoomOccupancyTest extends TestCase
{
    #[DataProvider('availabilityProvider')]
    public function test_availability_helpers(string $roomType, int $occupants, int $expectedSpots, string $expectedLabel, bool $expectedFull): void
    {
        $room = new Room([
            'room_type' => $roomType,
            'occupants_count' => $occupants,
        ]);

        $this->assertSame($expectedSpots, $room->availableSpots());
        $this->assertSame($expectedLabel, $room->availabilityLabel());
        $this->assertSame($expectedFull, $room->isFull());
    }

    /**
     * @return array<string, array{string, int, int, string, bool}>
     */
    public static function availabilityProvider(): array
    {
        return [
            'empty 2in1' => ['2in1', 0, 2, 'Available', false],
            'one person in 2in1' => ['2in1', 1, 1, '1 spot left', false],
            'full 2in1' => ['2in1', 2, 0, 'Full', true],
            'two people in 3in1' => ['3in1', 2, 1, '1 spot left', false],
            'full 4in1' => ['4in1', 4, 0, 'Full', true],
            'empty 1in1' => ['1in1', 0, 1, 'Available', false],
            'full homestay' => ['homestay', 1, 0, 'Full', true],
        ];
    }

    public function test_whatsapp_url_uses_global_number_and_prefilled_message(): void
    {
        config(['roomfinder.whatsapp' => '0551978928']);

        $room = new Room([
            'title' => '2in1 flat off Ayeduase Road',
            'room_type' => '2in1',
            'occupants_count' => 1,
            'price' => 3200,
        ]);

        $message = $room->whatsappContactMessage();
        $url = $room->whatsappUrl($message);

        $this->assertStringContainsString('https://wa.me/233551978928', $url);
        $this->assertStringContainsString('text=', $url);
        $this->assertStringContainsString('2in1', $message);
        $this->assertStringContainsString('1 spot left', $message);
        $this->assertStringContainsString('2in1 flat off Ayeduase Road', $message);
    }
}
