<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <title>@yield('title', 'UNJAYA Event')</title>
</head>
<body class="@yield('bodyClass', 'bg-blue-900 min-h-screen')">
    <!-- -- Navbar -- -->
    @include('partials.navbar')
    @yield('content')
    @include('partials._footer')
</body>
</html>
