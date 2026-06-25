<?php

namespace App\Http\Requests\Admin;

use App\Models\Room;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoomRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->roomRules();
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    protected function roomRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'location' => ['required', 'string', 'max:255'],
            'room_type' => ['required', 'string', Rule::in(Room::ROOM_TYPES)],
            'occupants_count' => [
                'required',
                'integer',
                'min:0',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $roomType = (string) $this->input('room_type', '');
                    $max = Room::maxCapacityForType($roomType);

                    if ((int) $value > $max) {
                        $fail("People already in room cannot exceed {$max} for a {$roomType} listing.");
                    }
                },
            ],
            'bedrooms' => ['required', 'integer', 'min:1', 'max:20'],
            'is_published' => ['sometimes', 'boolean'],
            'is_verified' => ['sometimes', 'boolean'],
            'images' => ['sometimes', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,webp', 'max:5120'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
            'is_verified' => $this->boolean('is_verified'),
            'occupants_count' => $this->input('occupants_count', 0),
        ]);
    }
}
