<nav class="bg-white shadow-inner  border-b-4 border-gray-600 shadow-lg dark:bg-gray-900">
  <div class="max-w-screen-s flex flex-wrap items-center justify-between mx-auto p-2">
    <!-- logo -->
    <a href="#" class="w-40 max-w-md h-10 overflow-hidden rounded-lg flex items-center">
      <img src="{{ asset('img/logos.png') }}" alt="Logo" class="w-full h-20 object-cover" />
    </a>

    <!-- searchbar kanan -->
    <div class="flex md:order-2 items-center space-x-2">
      <form action="{{ route('events.search') }}" method="GET" class="relative hidden md:block w-64">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
          </svg>
        </div>
        <input
          type="text"
          name="query"
          id="search-navbar"
          class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 
                 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
                 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
          placeholder="Cari event..."
          value="{{ request('query') }}">
      </form>

      <!-- button menu mobile -->
      <button data-collapse-toggle="navbar-search" type="button"
        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
        aria-controls="navbar-search" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M1 1h15M1 7h15M1 13h15" />
        </svg>
      </button>
    </div>

    <!-- menu -->
    <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-search">
      <ul
        class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 
               md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 
               md:dark:bg-gray-900 dark:border-gray-700">
        <li>
          <a href="{{ route('partisipan.home') }}"
            class="block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:hover:bg-transparent 
                    md:p-0 dark:hover:bg-gray-700 md:dark:hover:bg-transparent dark:border-gray-700
                    {{ Route::is('partisipan.home') 
                        ? 'text-blue-500 font-semibold dark:text-white md:hover:text-blue-700 md:dark:hover:text-blue-500' 
                        : 'text-gray-700 dark:text-white md:hover:text-blue-700 md:dark:hover:text-blue-500' }}"
            aria-current="page">Home</a>
        </li>
        <li>
          <a href="{{ route('partisipan.event') }}"
             class="block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:hover:bg-transparent 
                    md:p-0 dark:hover:bg-gray-700 md:dark:hover:bg-transparent dark:border-gray-700
                    {{ Route::is('partisipan.event') 
                        ? 'text-blue-500 font-semibold dark:text-white md:hover:text-blue-700 md:dark:hover:text-blue-500' 
                        : 'text-gray-700 dark:text-white md:hover:text-blue-700 md:dark:hover:text-blue-500' }}"
             aria-current="page">
             Event List
          </a>

        </li>
      </ul>
    </div>
  </div>
</nav>
