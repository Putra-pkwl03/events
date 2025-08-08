<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>@yield('title', 'Qr Event')</title>
</head>
<body class="@yield('bodyClass', 'bg-blue-100 min-h-screen')">
    @yield('content')
</body>
</html>
