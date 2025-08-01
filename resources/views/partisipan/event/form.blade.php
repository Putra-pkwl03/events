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

    <form action="{{ route('partisipan.event.submit', $event->id) }}" method="POST" class="space-y-5">
        @csrf
        @foreach ($event->fields as $field)
            <div>
                <label class="block mb-2 font-semibold text-gray-700">{{ $field->label }}</label>
                <div class="relative">
                    @if ($field->type === 'select')
                        @php
                            $options = explode(',', $field->options ?? '');
                        @endphp
                        <i class="fas fa-list absolute left-3 top-3 text-gray-400"></i>
                        <select name="{{ $field->label }}" class="pl-10 w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:outline-none transition" {{ $field->required ? 'required' : '' }}>
                            @foreach ($options as $option)
                                <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                            @endforeach
                        </select>

                    @elseif($field->type === 'text')
                        <i class="fas fa-font absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="{{ $field->label }}"
                            class="pl-10 w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:outline-none transition"
                            placeholder="Enter {{ strtolower($field->label) }}" 
                            {{ $field->required ? 'required' : '' }}>

                    @elseif($field->type === 'number')
                        <i class="fas fa-hashtag absolute left-3 top-3 text-gray-400"></i>
                        <input type="number" name="{{ $field->label }}"
                            class="pl-10 w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:outline-none transition"
                            placeholder="Enter {{ strtolower($field->label) }}"
                            {{ $field->required ? 'required' : '' }}>

                    @elseif($field->type === 'date')
                        <i class="fas fa-calendar absolute left-3 top-3 text-gray-400"></i>
                        <input type="date" name="{{ $field->label }}"
                            class="pl-10 w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:outline-none transition"
                            {{ $field->required ? 'required' : '' }}>

                    @else
                        <input 
                            type="{{ $field->type }}" 
                            name="{{ $field->label }}" 
                            class="w-full border border-gray-300 rounded px-3 py-2" 
                            {{ $field->required ? 'required' : '' }}
                        >
                    @endif
                </div>
            </div>
        @endforeach
        <div class="text-center pt-4">
            <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl font-semibold shadow hover:shadow-lg hover:from-green-600 hover:to-green-700 transition">
                <i class="fas fa-paper-plane mr-2"></i>
                Submit
            </button>
        </div> 
    </form>
</div>
@endsection
