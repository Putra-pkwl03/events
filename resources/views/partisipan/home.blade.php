@extends('partisipan.layouts.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />

@section('content')
<!-- Hero Carousel Section -->
<div class="relative bg-blue-100 overflow-hidden bg-cover items-center" style="background-image: url('{{ asset('img/coursel-background.jpg') }}');">
    <div class="absolute inset-0 bg-gray-600/60 z-0"></div>
    <div class="max-w-7xl mx-auto relative z-10 p-8 bg-transparant sm:pb-16 md:pb-20 lg:pb-28 xl:pb-32">
        <div id="hero-carousel" class="relative w-full" data-carousel="slide">
            <!-- Carousel wrapper -->
            <div class="relative h-96 overflow-hidden rounded-lg">
                @foreach ($upcomingEvents as $index => $event)
                    <div class="{{ $index === 0 ? '' : 'hidden' }} duration-700 ease-in-out" data-carousel-item="{{ $index === 0 ? 'active' : '' }}">
                        <div class="grid grid-cols-1 lg:grid-cols-2 items-center h-full ">
                            <div class="px-24">
                                <h2 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">{{ $event->title }}</h2>
                                <p class="mt-3 text-base text-gray-100 sm:mt-5 sm:text-lg md:mt-5 md:text-xl">
                                    {{ Str::limit($event->description, 100) }}
                                </p>
                                <p class="mt-3 text-base text-gray-300 sm:mt-5 sm:text-lg md:mt-5 md:text-xl">
                                    📅 {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                                </p>
                                <div class="mt-5 flex space-x-4">
                                    <a href="{{ route('partisipan.event.form', $event->id) }}" class="px-6 py-3 rounded-md text-white bg-blue-600 hover:bg-pink-700 font-medium">
                                        Daftar
                                    </a>
                                </div>
                            </div>
                            <div class="">
                                <img class="h-96 w-full object-contain" src="{{ asset('storage/' . $event->poster) }}" alt="Poster Event">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Slider controls -->
            <button type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </span>
            </button>
            <button type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div>
@include('partials._kategori')
<!-- Konten utama -->
<div class="min-h-screen text-white relative overflow-hidden">
    <div class="relative bg-gray-400 max-w-7xl mx-auto z-10 rounded-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">Upcoming Event</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-6">
                @forelse ($upcomingEvents as $event)
                    @include('partials._card', ['event' => $event])
                @empty
                    <p class="col-span-full text-gray-500">Tidak ada event yang akan datang.</p>
                @endforelse
            </div>
            <div class="mt-12 text-center">
                <a href="{{ route('partisipan.event') }}">
                    <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition">
                        View All Event
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
