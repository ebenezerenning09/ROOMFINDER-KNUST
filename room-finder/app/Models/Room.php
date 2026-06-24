<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'description', 'price', 'location', 'room_type', 'bedrooms', 'image', 'whatsapp'])]
class Room extends Model
{
    /** Display label for price in views (not stored in the database). */
    public const PRICE_PERIOD_LABEL = 'per academic year';

    /** @var list<string> */
    public const ROOM_TYPES = ['1in1', '2in1', '3in1', '4in1'];

    public static function roomTypeBadgeClass(string $roomType): string
    {
        return match ($roomType) {
            '1in1' => 'bg-emerald-100 text-emerald-800',
            '2in1' => 'bg-sky-100 text-sky-800',
            '3in1' => 'bg-amber-100 text-amber-800',
            '4in1' => 'bg-violet-100 text-violet-800',
            default => 'bg-slate-100 text-slate-800',
        };
    }

    public static function placeholderImageUrl(int $number): string
    {
        $file = str_pad((string) $number, 2, '0', STR_PAD_LEFT);

        return "/images/hostels/{$file}.jpg";
    }

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class)->orderBy('sort_order');
    }

    public function fallbackImageUrl(): string
    {
        return '/images/hostels/default.jpg';
    }

    public function imageUrl(): string
    {
        $firstImage = $this->images->first();

        if ($firstImage) {
            return $firstImage->url();
        }

        $image = $this->attributes['image'] ?? null;

        if (is_string($image) && str_starts_with($image, '/images/hostels/')) {
            return $image;
        }

        if (is_string($image) && str_starts_with($image, 'images/hostels/')) {
            return '/'.$image;
        }

        $index = $this->id > 0 ? (($this->id - 1) % 12) + 1 : 1;

        return self::placeholderImageUrl($index);
    }

    /**
     * @return list<string>
     */
    public function imageUrls(): array
    {
        if ($this->images->isNotEmpty()) {
            return $this->images->map(fn (RoomImage $image) => $image->url())->all();
        }

        return [$this->imageUrl()];
    }

    public function whatsappUrl(?string $message = null): ?string
    {
        if (! $this->whatsapp) {
            return null;
        }

        $phone = preg_replace('/\D/', '', $this->whatsapp);

        if ($phone === '') {
            return null;
        }

        if (str_starts_with($phone, '0')) {
            $phone = '233'.substr($phone, 1);
        } elseif (! str_starts_with($phone, '233')) {
            $phone = '233'.$phone;
        }

        $url = "https://wa.me/{$phone}";

        if ($message !== null && $message !== '') {
            $url .= '?text='.rawurlencode($message);
        }

        return $url;
    }

    public function whatsappContactMessage(): string
    {
        return "Hi, I'm interested in your hostel listing: {$this->title} (GHS ".number_format((float) $this->price, 2).' / academic year). Is it still available?';
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'bedrooms' => 'integer',
        ];
    }
}
