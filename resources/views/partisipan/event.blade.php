@extends('partisipan.layouts.app')

@section('content')
@if (!request('query'))
    @include('partials._kategori')
@endif

<div class="min-h-screen text-white relative overflow-hidden">
    <div class="relative bg-gray-400 max-w-7xl mx-auto z-10 rounded-2xl mt-4">
        <h1 class="text-center font-display text-3xl font-bold tracking-tight text-slate-900 md:text-4xl mt-4 p-4">
            @if (request('kategori'))
                Kategori: {{ request('kategori') }}
            @endif
        </h1>

        @if(request('query') || request('kategori'))
            <p class="text-center text-sm mb-4 text-black">
                @if(request('query'))
                    Hasil pencarian untuk: <strong>{{ $query }}</strong>
                @endif
            </p>

            <div id="event-list" class="grid gap-8 sm:grid-cols-1 lg:grid-cols-3 p-3 text-center mt-10">
                @forelse ($upcomingEvents as $event)
                    @include('partials._card', ['event' => $event])
                @empty
                    <p class="col-span-full text-white">Tidak ada hasil ditemukan.</p>
                @endforelse
            </div>
        @else
            @php
                $sortedEvents = $upcomingEvents->sortBy(fn($event) => \Carbon\Carbon::parse($event->event_date));
                $initialEvents = $sortedEvents->take(8);
            @endphp

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
                <div id="event-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-6">
                    @forelse ($initialEvents as $event)
                        @include('partials._card', ['event' => $event])
                    @empty
                        <p class="col-span-full text-gray-500">Tidak ada event yang akan datang.</p>
                    @endforelse
                </div>
                <!-- Loading spinner, hidden default -->
                <div id="loading-spinner" class="flex justify-center mt-6" style="display:none;">
                    <div class="relative w-12 h-12 rounded-full animate-spin">
                        <div class="absolute top-0 left-0 w-full h-full border-t-4 border-blue-500 rounded-full"></div>
                        <div class="absolute top-0 left-0 w-full h-full border-r-4 border-green-500 rounded-full"></div>
                        <div class="absolute top-0 left-0 w-full h-full border-b-4 border-blue-800 rounded-full"></div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@if (!request('query') && !request('kategori'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    let offset = {{ $initialEvents->count() }};
    const limit = 4;
    let loading = false;

    const query = "{{ request('query') ?? '' }}";
    const kategori = "{{ request('kategori') ?? '' }}";

    const eventList = document.getElementById('event-list');
    const spinner = document.getElementById('loading-spinner');
    if (!eventList || !spinner) return;

    window.addEventListener('scroll', () => {
        if (loading) return;

        if ((window.innerHeight + window.scrollY) >= document.documentElement.scrollHeight - 300) {
            loading = true;

            // Tampilkan spinner
            spinner.style.display = 'flex';

            setTimeout(() => {
                const params = new URLSearchParams();
                params.append('offset', offset);
                params.append('limit', limit);
                if(query) params.append('query', query);
                if(kategori) params.append('kategori', kategori);

                fetch(`/event/load-more?${params.toString()}`)
                    .then(res => {
                        if(!res.ok) throw new Error('Network response not ok');
                        return res.json();
                    })
                    .then(data => {
                        if (data.count > 0) {
                            eventList.insertAdjacentHTML('beforeend', data.html);
                            offset += data.count;
                            loading = false;
                            spinner.style.display = 'none';  // sembunyikan spinner
                        } else {
                            spinner.style.display = 'none';
                            window.removeEventListener('scroll', arguments.callee);
                        }
                    })
                    .catch((err) => {
                        console.error(err);
                        loading = false;
                        spinner.style.display = 'none';
                    });
            }, 1000);
        }
    });
});
</script>
@endif

@endsection
