<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-white rounded-2xl m-4 relative">
    <h2 class="text-2xl font-bold text-gray-900 mb-8">Kategori Event</h2>

    <div id="categorySlider" class="flex space-x-4 overflow-x-auto scroll-smooth no-scrollbar">
        @php
            $categories = [
                ['img' => 'seminar.jpg', 'title' => 'Seminar'],
                ['img' => 'workshop.jpg', 'title' => 'Workshop'],
                ['img' => 'kompetisi.jpg', 'title' => 'Kompetisi'],
                ['img' => 'lomba.jpg', 'title' => 'Lomba'],
                ['img' => 'pelatihan.jpg', 'title' => 'Pelatihan'],
            ];
        @endphp

        @foreach ($categories as $cat)
            <a href="{{ route('partisipan.event') }}?kategori={{ urlencode($cat['title']) }}" 
               class="min-w-[250px] group relative block bg-gray-100 rounded-lg overflow-hidden">
                <img src="{{ asset('img/' . $cat['img']) }}" alt="{{ $cat['title'] }}" class="w-full h-64 object-cover transition-opacity">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent hover:from-transparent hover:to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-4">
                    <h3 class="text-xl font-semibold text-white">{{ $cat['title'] }}</h3>
                    <p class="mt-1 text-sm text-gray-300">Lihat Event</p>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Slider controls -->
    <button type="button" id="prevBtn" 
        class="absolute top-10 left-5 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none">
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50">
            <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </span>
    </button>
    <button type="button" id="nextBtn" 
        class="absolute top-10 right-5 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none">
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50">
            <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </span>
    </button>
</div>

<style>
    /* Sembunyikan scrollbar horizontal tapi tetap bisa scroll */
    #categorySlider {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;     /* Firefox */
    }
    #categorySlider::-webkit-scrollbar {
        display: none;             /* Chrome, Safari, Opera */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slider = document.getElementById('categorySlider');
        const btnPrev = document.getElementById('prevBtn');
        const btnNext = document.getElementById('nextBtn');
        const scrollAmount = 280; // Besar pergeseran scroll (sesuaikan dengan lebar item + gap)

        btnPrev.addEventListener('click', () => {
            slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });

        btnNext.addEventListener('click', () => {
            slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });
    });
</script>
