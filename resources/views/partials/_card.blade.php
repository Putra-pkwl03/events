<div class="group relative bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="relative">
        <img src="{{ asset('storage/' . $event->poster) }}" onerror="this.onerror=null; this.src='/images/default-event.jpg';"
             alt="Event Image" class="w-full h-80 object-cover">
    </div>
    <div class="p-4">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-sm font-medium text-gray-900">
                    <a href="{{ route('partisipan.event.form', $event->id) }}">
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{ $event->title }}
                    </a>
                </h3>
            </div>
            <p class="text-sm font-medium text-blue-600">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</p>
        </div>
        <p class="mt-1 h-16 text-sm text-gray-500">{{ Str::limit($event->description, 100) }}</p>
        <div class="mt-4">
            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm font-medium transition-colors duration-300">
                <a href="{{ route('partisipan.event.form', $event->id) }}" class="group">
                    daftar
                </a> 
            </button>
        </div>
    </div>
</div>
