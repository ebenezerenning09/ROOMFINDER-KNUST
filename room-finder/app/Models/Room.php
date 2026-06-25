<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

#[Fillable(['title', 'description', 'price', 'location', 'room_type', 'occupants_count', 'bedrooms', 'image', 'whatsapp', 'is_published', 'is_verified'])]
class Room extends Model
{
    /** Display label for price in views (not stored in the database). */
    public const PRICE_PERIOD_LABEL = 'per academic year';

    public const PLACEHOLDER_IMAGE_COUNT = 14;

    /** @var list<string> */
    public const ROOM_TYPES = ['1in1', '2in1', '3in1', '4in1', 'homestay'];

    public static function roomTypeBadgeClass(string $roomType): string
    {
        return match ($roomType) {
            '1in1' => 'bg-emerald-100 text-emerald-800',
            '2in1' => 'bg-sky-100 text-sky-800',
            '3in1' => 'bg-amber-100 text-amber-800',
            '4in1' => 'bg-violet-100 text-violet-800',
            'homestay' => 'bg-rose-100 text-rose-800',
            default => 'bg-slate-100 text-slate-800',
        };
    }

    public static function maxCapacityForType(string $roomType): int
    {
        return match ($roomType) {
            '2in1' => 2,
            '3in1' => 3,
            '4in1' => 4,
            default => 1,
        };
    }

    public static function availabilityBadgeClass(bool $isFull): string
    {
        return $isFull
            ? 'bg-red-100 text-red-800'
            : 'bg-emerald-100 text-emerald-800';
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

    /**
     * @param  Builder<static>  $query
     */
    public function scopePublished(Builder $query): void
    {
        self::applyBooleanWhere($query, 'is_published', true);
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeUnpublished(Builder $query): void
    {
        self::applyBooleanWhere($query, 'is_published', false);
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeVerified(Builder $query): void
    {
        self::applyBooleanWhere($query, 'is_verified', true);
    }

    /**
     * @param  Builder<static>  $query
     */
    protected static function applyBooleanWhere(Builder $query, string $column, bool $value): void
    {
        if (DB::getDriverName() === 'pgsql') {
            $query->whereRaw($value ? "{$column} IS TRUE" : "{$column} IS FALSE");

            return;
        }

        $query->where($column, $value);
    }

    public function maxCapacity(): int
    {
        return self::maxCapacityForType($this->room_type);
    }

    public function availableSpots(): int
    {
        $occupants = (int) ($this->occupants_count ?? 0);

        return max(0, $this->maxCapacity() - $occupants);
    }

    public function isFull(): bool
    {
        return $this->availableSpots() === 0;
    }

    public function availabilityLabel(): string
    {
        $spots = $this->availableSpots();

        if ($spots === 0) {
            return 'Full';
        }

        if ($spots === $this->maxCapacity()) {
            return 'Available';
        }

        return $spots === 1 ? '1 spot left' : "{$spots} spots left";
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

        $index = $this->id > 0 ? (($this->id - 1) % self::PLACEHOLDER_IMAGE_COUNT) + 1 : 1;

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
        $phone = self::normalizeWhatsappNumber((string) config('roomfinder.whatsapp', ''));

        if ($phone === null) {
            return null;
        }

        $url = "https://wa.me/{$phone}";

        if ($message !== null && $message !== '') {
            $url .= '?text='.rawurlencode($message);
        }

        return $url;
    }

    public function whatsappContactMessage(): string
    {
        $price = number_format((float) $this->price, 2);

        return "Hi RoomFinder, I'm interested in \"{$this->title}\" ({$this->room_type}, {$this->availabilityLabel()}, GHS {$price} / academic year). Is it still available?";
    }

    public static function normalizeWhatsappNumber(string $number): ?string
    {
        $phone = preg_replace('/\D/', '', $number);

        if ($phone === '') {
            return null;
        }

        if (str_starts_with($phone, '0')) {
            $phone = '233'.substr($phone, 1);
        } elseif (! str_starts_with($phone, '233')) {
            $phone = '233'.$phone;
        }

        return $phone;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'bedrooms' => 'integer',
            'occupants_count' => 'integer',
            'is_published' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }
}
