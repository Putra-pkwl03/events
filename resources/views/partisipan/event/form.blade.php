@extends('partisipan.layouts.app')

@section('title', 'Event: ' . $event->title)

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl p-8 mt-6">
    <!-- Event Header -->
    <div class="mb-6 text-center border-b pb-4">
        
        <h2 class="text-3xl font-bold text-cyan-600">{{ $event->title }}</h2>
        <p class="text-gray-600 mt-2">{{ $event->description }}</p>
        <p class="text-gray-500 mt-2 flex justify-center items-center gap-2">
            <i class="fas fa-calendar-alt text-cyan-500"></i>
            <strong>{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</strong>
        </p>
    </div>

    <form id="event-form" action="{{ route('partisipan.event.submit', $event->id) }}" method="POST" class="space-y-5">
        @csrf
        @foreach ($event->fields as $field)
            <div>
                <label class="block mb-2 font-semibold text-gray-700">{{ $field->label }}</label>
                <div class="relative">
                    @php
                        $options = [];
                        if (is_array($field->options)) {
                            $options = $field->options;
                        } elseif (is_string($field->options)) {
                            $decoded = json_decode($field->options, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $options = $decoded;
                            } else {
                                $options = explode(',', $field->options);
                            }
                        }
                    @endphp
                    
                    @if ($field->type === 'select')
                        <i class="fas fa-list absolute left-3 top-3 text-gray-400"></i>
                        <select name="{{ $field->label }}" class="pl-10 w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:outline-none transition" {{ $field->required ? 'required' : '' }}>
                            @foreach ($options as $option)
                                <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                            @endforeach
                        </select>

                    @elseif($field->type === 'text')
                        <i class="fas fa-font absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="{{ $field->label }}" class="pl-10 w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:outline-none transition" placeholder="Enter {{ strtolower($field->label) }}" {{ $field->required ? 'required' : '' }}>

                    @elseif($field->type === 'email')
                        <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                        <input type="email" name="{{ $field->label }}" class="pl-10 w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:outline-none transition" placeholder="Email" {{ $field->required ? 'required' : '' }}>

                    @elseif($field->type === 'number')
                        <i class="fas fa-hashtag absolute left-3 top-3 text-gray-400"></i>
                        <input type="number" name="{{ $field->label }}" class="pl-10 w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:outline-none transition" placeholder="Enter {{ strtolower($field->label) }}" {{ $field->required ? 'required' : '' }}>

                    @elseif($field->type === 'date')
                        <i class="fas fa-calendar absolute left-3 top-3 text-gray-400"></i>
                        <input type="date" name="{{ $field->label }}" class="pl-10 w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:outline-none transition" {{ $field->required ? 'required' : '' }}>

                    @elseif($field->type === 'textarea')
                        <textarea name="{{ $field->label }}" rows="4" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:outline-none transition" placeholder="Enter {{ strtolower($field->label) }}" {{ $field->required ? 'required' : '' }}></textarea>

                    @elseif($field->type === 'radio')
                        <div class="flex flex-col space-y-2 pl-2">
                            @foreach ($options as $option)
                                <label class="inline-flex items-center">
                                    <input type="radio" name="{{ $field->label }}" value="{{ trim($option) }}" class="text-cyan-600" {{ $field->required ? 'required' : '' }}>
                                    <span class="ml-2 text-gray-700">{{ trim($option) }}</span>
                                </label>
                            @endforeach
                        </div>

                    @elseif($field->type === 'checkbox')
                        <div class="flex flex-col space-y-2 pl-2">
                            @foreach ($options as $option)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="{{ $field->label }}[]" value="{{ trim($option) }}" class="text-cyan-600">
                                    <span class="ml-2 text-gray-700">{{ trim($option) }}</span>
                                </label>
                            @endforeach
                        </div>

                    @else
                        <input type="{{ $field->type }}" name="{{ $field->label }}" class="w-full border border-gray-300 rounded px-3 py-2" {{ $field->required ? 'required' : '' }}>
                    @endif
                </div>
            </div>
        @endforeach
        <div class="text-center pt-4">
            <button 
                type="submit" 
                id="submit-btn"
                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl font-semibold shadow hover:shadow-lg hover:from-green-600 hover:to-green-700 transition cursor-pointer"
            >
                <i class="fas fa-paper-plane mr-2"></i>
                Submit
            </button>
        </div> 
    </form>
</div>

{{-- Modal QR Code --}}
@if(session('qr_wrapped'))
<div 
    x-data="{ open: true }" 
    x-show="open" 
    x-transition
    class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
>
    <div class="bg-white p-6 rounded-xl shadow-lg max-w-sm w-full text-center relative">
        
        {{-- Tombol Close (X) --}}
        <button 
            @click="open = false"
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 transition"
            aria-label="Close"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Gambar QR --}}
        <div class="flex justify-center mb-4">
            <img src="{{ asset(session('qr_wrapped')) }}" alt="QR Code" class="w-auto h-auto object-contain">
        </div>

        @php
            $nomorWa = '628975770056';
            $pesan = "Halo, saya *" . session('nama_pendaftar') . "* ingin konfirmasi hadir pada event *" 
                    . $event->title . "*.\n\n"
                    . "Link Check-in: " . session('qr_link') . "\n"
                    . "QR Code: " . asset(session('qr_wrapped'));
            $urlWa = "https://wa.me/{$nomorWa}?text=" . urlencode($pesan);
        @endphp

        {{-- Tombol Aksi --}}
        <div class="flex gap-2 justify-center">
            {{-- Tombol Download QR --}}
            <a href="{{ asset(session('qr_wrapped')) }}" download="qrcode.png"
                class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                </svg>
                Download QR
            </a>

            {{-- Tombol Kirim WA --}}
            <a href="{{ $urlWa }}" target="_blank"
                class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                <img src="{{ asset('img/logo-whatsapp.svg') }}" alt="WhatsApp" class="w-5 h-5">
                Kirim WA
            </a>
        </div>
    </div>
</div>
@endif

{{-- Script untuk tombol submit --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('event-form');
    const submitBtn = document.getElementById('submit-btn');

    form.addEventListener('submit', function (e) {
        // Ubah tombol jadi loading dan disable
        submitBtn.disabled = true;
        submitBtn.style.cursor = 'not-allowed';
        submitBtn.innerHTML = `
            <svg width="20" height="20" fill="currentColor" class="mr-2 animate-spin inline-block" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                <path d="M526 1394q0 53-37.5 90.5t-90.5 37.5q-52 0-90-38t-38-90q0-53 37.5-90.5t90.5-37.5 90.5 37.5 37.5 90.5zm498 206q0 53-37.5 90.5t-90.5 37.5-90.5-37.5-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm-704-704q0 53-37.5 90.5t-90.5 37.5-90.5-37.5-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm1202 498q0 52-38 90t-90 38q-53 0-90.5-37.5t-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm-964-996q0 66-47 113t-113 47-113-47-47-113 47-113 113-47 113 47 47 113zm1170 498q0 53-37.5 90.5t-90.5 37.5-90.5-37.5-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm-640-704q0 80-56 136t-136 56-136-56-56-136 56-136 136-56 136 56 56 136zm530 206q0 93-66 158.5t-158 65.5q-93 0-158.5-65.5t-65.5-158.5q0-92 65.5-158t158.5-66q92 0 158 66t66 158z"></path>
            </svg>
            loading
        `;
    });
});
</script>

@endsection
