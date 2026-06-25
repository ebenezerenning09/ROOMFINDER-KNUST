@props([
    'alt' => 'RoomFinder — Find Your Next Home',
    'variant' => 'trimmed',
])

@php
    $src = match ($variant) {
        'icon' => 'images/roomfinder-logo-icon-transparent.png',
        'full' => 'images/roomfinder-logo.png',
        default => 'images/roomfinder-logo-trimmed.png',
    };

    $defaultClass = match ($variant) {
        'icon' => 'h-10 w-10 object-contain',
        default => 'h-8 w-auto object-contain',
    };
@endphp

<img
    src="{{ asset($src) }}?v=2"
    alt="{{ $alt }}"
    {{ $attributes->merge(['class' => $defaultClass]) }}
>
