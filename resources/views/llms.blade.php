# RoomFinder

> RoomFinder helps students at KNUST (Kwame Nkrumah University of Science and Technology) in Kumasi, Ghana find verified hostel rooms. Listings cover 1in1, 2in1, 3in1, 4in1 and homestay room types, priced per academic year in Ghana Cedis (GHS).

Important details:
- Browsing listings is free and open to everyone.
- Contacting the RoomFinder admin about a room requires signing in.
- A 10% agent fee applies only after a room placement is successfully completed, never upfront.
- Contact: hello@elitealfaventures.com

## Main pages
- [Home]({{ url('/') }}): Overview of RoomFinder for KNUST students.
- [Browse rooms]({{ route('rooms.index') }}): All published hostel listings, filterable by location, room type and price.

## Current listings
@foreach ($rooms as $room)
- [{{ $room->title }}]({{ route('rooms.show', $room) }}): {{ $room->room_type }} in {{ $room->location }}, GHS {{ number_format((float) $room->price, 2) }} per academic year ({{ $room->availabilityLabel() }}).
@endforeach
