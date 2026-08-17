@extends('layouts.app')

@section('title', $room->title)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($room->description), 155))
@section('og_image', $room->imageUrl())
@section('og_type', 'product')

@push('head')
    @php
        $ldRoom = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $room->title,
            'description' => \Illuminate\Support\Str::limit(strip_tags($room->description), 300),
            'image' => array_map(fn ($url) => url($url), $room->imageUrls()),
            'category' => 'Student hostel accommodation near KNUST',
            'brand' => ['@type' => 'Brand', 'name' => 'RoomFinder'],
            'offers' => [
                '@type' => 'Offer',
                'price' => number_format((float) $room->price, 2, '.', ''),
                'priceCurrency' => 'GHS',
                'availability' => $room->isFull() ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock',
                'url' => url()->current(),
                'areaServed' => $room->location.', Kumasi',
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($ldRoom, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
    <div class="mb-5 sm:mb-6">
        <a href="{{ route('rooms.index') }}" class="inline-flex min-h-11 touch-manipulation items-center text-sm font-medium text-emerald-700 hover:text-emerald-800 active:text-emerald-900">
            <svg class="mr-1 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back to listings
        </a>
    </div>

    <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        @php
            $galleryImages = $room->imageUrls();
            $primaryImage = $galleryImages[0] ?? $room->fallbackImageUrl();
        @endphp

        <div class="border-b border-slate-200 bg-slate-50">
            <img
                id="room-gallery-main"
                src="{{ $primaryImage }}"
                alt="{{ $room->title }}"
                class="h-64 w-full object-cover sm:h-80"
                referrerpolicy="no-referrer"
                onerror="this.onerror=null; this.src='{{ $room->fallbackImageUrl() }}';"
            >

            @if (count($galleryImages) > 1)
                <div class="flex gap-2 overflow-x-auto p-3 sm:p-4">
                    @foreach ($galleryImages as $index => $imageUrl)
                        <button
                            type="button"
                            data-gallery-thumb
                            data-image-url="{{ $imageUrl }}"
                            aria-label="View photo {{ $index + 1 }} of {{ count($galleryImages) }}"
                            aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"
                            class="gallery-thumb shrink-0 overflow-hidden rounded-lg border-2 transition {{ $index === 0 ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-emerald-300' }}"
                        >
                            <img
                                src="{{ $imageUrl }}"
                                alt=""
                                class="h-16 w-24 object-cover sm:h-20 sm:w-28"
                                loading="lazy"
                                referrerpolicy="no-referrer"
                                onerror="this.onerror=null; this.src='{{ $room->fallbackImageUrl() }}';"
                            >
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="p-4 sm:p-8">
            <div class="mb-3 flex flex-wrap items-center gap-2 sm:mb-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium sm:px-3 sm:py-1 sm:text-sm {{ \App\Models\Room::roomTypeBadgeClass($room->room_type) }}">
                    {{ $room->room_type }}
                </span>
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium sm:px-3 sm:py-1 sm:text-sm {{ \App\Models\Room::availabilityBadgeClass($room->isFull()) }}">
                    {{ $room->availabilityLabel() }}
                </span>
                @if ($room->is_verified)
                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 sm:px-3 sm:py-1 sm:text-sm">
                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Verified
                    </span>
                @endif
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 sm:px-3 sm:py-1 sm:text-sm">
                    {{ $room->location }}
                </span>
                @if ($room->bedrooms)
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 sm:px-3 sm:py-1 sm:text-sm">
                        {{ $room->bedrooms }} {{ Str::plural('bedroom', $room->bedrooms) }}
                    </span>
                @endif
                @if (count($galleryImages) > 1)
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 sm:px-3 sm:py-1 sm:text-sm">
                        {{ count($galleryImages) }} photos
                    </span>
                @endif
            </div>

            <h1 class="text-xl font-bold leading-tight text-slate-900 sm:text-2xl md:text-3xl">{{ $room->title }}</h1>

            <p class="mt-3 text-2xl font-bold text-green-700 sm:mt-4 sm:text-3xl">
                GHS {{ number_format($room->price, 2) }}
                <span class="block text-base font-semibold text-green-600 sm:inline sm:text-base sm:font-normal sm:text-slate-500">{{ \App\Models\Room::PRICE_PERIOD_LABEL }}</span>
            </p>

            <div class="mt-5 border-t border-slate-200 pt-5 sm:mt-6 sm:pt-6">
                <h2 class="text-xs font-semibold uppercase tracking-wide text-slate-500 sm:text-sm">Description</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-700 sm:text-base">{{ $room->description }}</p>
            </div>

            <div class="mt-6 sm:mt-8">
                <div class="mb-4 rounded-lg border-2 border-amber-300 bg-amber-50 px-4 py-3 sm:px-5 sm:py-4">
                    <p class="text-sm font-bold text-amber-950 sm:text-base">
                        10% agent fee
                    </p>
                    <p class="mt-1 text-sm text-amber-900">
                        This fee is charged only after RoomFinder successfully helps you secure this hostel, not when you enquire.
                    </p>
                </div>

                @auth
                    <a
                        href="{{ $room->whatsappUrl($room->whatsappContactMessage()) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex min-h-11 w-full touch-manipulation items-center justify-center gap-2 rounded-lg bg-[#25D366] px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-[#20BD5A] focus:outline-none focus:ring-2 focus:ring-[#25D366] focus:ring-offset-2 active:bg-[#1DA851] sm:w-auto"
                    >
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Chat on WhatsApp
                    </a>
                    <p class="mt-2 text-xs text-slate-500">Opens WhatsApp with a pre-filled message to RoomFinder.</p>
                @else
                    <div class="rounded-lg border-2 border-emerald-200 bg-emerald-50 px-4 py-4 sm:px-5 sm:py-5">
                            <p class="text-sm font-semibold text-emerald-900 sm:text-base">
                                Log in or register to contact the RoomFinder admin
                            </p>
                            <p class="mt-1 text-sm text-emerald-800">
                                Browsing is free. Create an account to message us on WhatsApp about this listing.
                            </p>
                            <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                                <a
                                    href="{{ route('login', ['redirect' => url()->current()]) }}"
                                    class="inline-flex min-h-11 items-center justify-center rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700"
                                >
                                    Log in
                                </a>
                                <a
                                    href="{{ route('register', ['redirect' => url()->current()]) }}"
                                    class="inline-flex min-h-11 items-center justify-center rounded-lg border border-emerald-600 bg-white px-5 py-2.5 text-sm font-semibold text-emerald-700 hover:bg-emerald-50"
                                >
                                    Register
                                </a>
                            </div>
                        </div>
                @endauth
            </div>
        </div>
    </article>

    @if (count($galleryImages) > 1)
        <script>
            document.querySelectorAll('[data-gallery-thumb]').forEach((button) => {
                button.addEventListener('click', () => {
                    const main = document.getElementById('room-gallery-main');
                    main.src = button.dataset.imageUrl;

                    document.querySelectorAll('[data-gallery-thumb]').forEach((thumb) => {
                        thumb.setAttribute('aria-pressed', 'false');
                        thumb.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-500/20');
                        thumb.classList.add('border-slate-200');
                    });

                    button.setAttribute('aria-pressed', 'true');
                    button.classList.add('border-emerald-500', 'ring-2', 'ring-emerald-500/20');
                    button.classList.remove('border-slate-200');
                });
            });
        </script>
    @endif
@endsection
