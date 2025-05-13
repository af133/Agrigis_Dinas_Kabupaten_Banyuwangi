<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'judul tidak masuk')</title>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="{{ secure_asset('build/assets/app-C2QBqpaD.js') }}"></script>
    <link rel="stylesheet" href="{{ secure_asset('build/assets/app-RYJ67zM2.css') }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

</head>
<body class="bg-[#F1FFDE] h-full w-full">
    @php
        $dataUser = session('dataUser');
    @endphp

    @if (!Route::is('login'))
        @include('components.navbar', $dataUser)
    @endif

    <div class="lg:ml-[16.5rem] ml-0 w-full lg:w-[63rem]">
        @yield('content')
    </div>

    <script>
        const userStatus = "{{ session('dataUser')['status'] ?? 'guest' }}";
    </script>
</body>
</html>
