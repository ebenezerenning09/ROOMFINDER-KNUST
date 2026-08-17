@props([
    'alt' => 'RoomFinder: Find Your Next Home',
    'variant' => 'trimmed',
])

@php
    $defaultClass = match ($variant) {
        'icon' => 'h-10 w-10',
        default => 'h-8 w-auto',
    };
@endphp

@if ($variant === 'icon')
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 48 48"
        role="img"
        aria-label="{{ $alt }}"
        {{ $attributes->merge(['class' => $defaultClass]) }}
    >
        <rect x="8" y="6" width="22" height="36" rx="2" fill="#10b981"/>
        <path d="M30 6h6v36h-6V6z" fill="#059669"/>
        <circle cx="19" cy="26" r="2.5" fill="#ffffff"/>
        <circle cx="34" cy="30" r="11" fill="none" stroke="#059669" stroke-width="3"/>
        <circle cx="34" cy="30" r="7" fill="#ecfdf5" stroke="#10b981" stroke-width="1.5"/>
        <path d="M41 37l6 6" stroke="#059669" stroke-width="3" stroke-linecap="round"/>
        <circle cx="34" cy="30" r="2" fill="#10b981"/>
    </svg>
@elseif ($variant === 'full')
    <div {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 ' . $defaultClass]) }}>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="h-12 w-12 shrink-0" aria-hidden="true">
            <rect x="8" y="6" width="22" height="36" rx="2" fill="#10b981"/>
            <path d="M30 6h6v36h-6V6z" fill="#059669"/>
            <circle cx="19" cy="26" r="2.5" fill="#ffffff"/>
            <circle cx="34" cy="30" r="11" fill="none" stroke="#059669" stroke-width="3"/>
            <circle cx="34" cy="30" r="7" fill="#ecfdf5" stroke="#10b981" stroke-width="1.5"/>
            <path d="M41 37l6 6" stroke="#059669" stroke-width="3" stroke-linecap="round"/>
            <circle cx="34" cy="30" r="2" fill="#10b981"/>
        </svg>
        <div class="leading-tight">
            <p class="text-xl font-bold tracking-tight">
                <span class="text-slate-900">Room</span><span class="text-emerald-600">Finder</span>
            </p>
            <p class="text-sm font-medium text-emerald-600">Find Your Next Home</p>
        </div>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 ' . $defaultClass]) }}>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="h-8 w-8 shrink-0 sm:h-9 sm:w-9" aria-hidden="true">
            <rect x="8" y="6" width="22" height="36" rx="2" fill="#10b981"/>
            <path d="M30 6h6v36h-6V6z" fill="#059669"/>
            <circle cx="19" cy="26" r="2.5" fill="#ffffff"/>
            <circle cx="34" cy="30" r="11" fill="none" stroke="#059669" stroke-width="3"/>
            <circle cx="34" cy="30" r="7" fill="#ecfdf5" stroke="#10b981" stroke-width="1.5"/>
            <path d="M41 37l6 6" stroke="#059669" stroke-width="3" stroke-linecap="round"/>
            <circle cx="34" cy="30" r="2" fill="#10b981"/>
        </svg>
        <span class="text-base font-bold tracking-tight sm:text-lg">
            <span class="text-slate-900">Room</span><span class="text-emerald-600">Finder</span>
        </span>
    </div>
@endif
