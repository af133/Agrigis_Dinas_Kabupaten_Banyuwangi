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
 <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @if (!Route::is('login'))
        @include('components.navbar', $dataUser)
    @endif

    <div class="lg:ml-[16.5rem] ml-0 w-full lg:w-[63rem]">
        @yield('content')
    </div>

    <script>
        const userStatus = "{{ session('dataUser')['status'] ?? 'guest' }}";
    </script>
    <script>
        const formInputs = ['name', 'email', 'password']; 
const editBtn = document.getElementById('editBtn');
const saveBtn = document.getElementById('saveBtn');
const cancelBtn = document.getElementById('cancelBtn');
const form = document.getElementById('akunForm');

const originalData = {};
formInputs.forEach(id => {
    originalData[id] = document.getElementById(id).value;
});

editBtn.addEventListener('click', () => {
    formInputs.forEach(id => {
        document.getElementById(id).removeAttribute('readonly');
    });
    editBtn.classList.add('hidden');
    saveBtn.classList.remove('hidden');
    cancelBtn.classList.remove('hidden');
});

cancelBtn.addEventListener('click', () => {
    formInputs.forEach(id => {
        const input = document.getElementById(id);
        input.setAttribute('readonly', true);
        input.value = originalData[id];
    });
    editBtn.classList.remove('hidden');
    saveBtn.classList.add('hidden');
    cancelBtn.classList.add('hidden');
});

form.addEventListener('submit', function (e) {
    const confirmation = confirm('Apakah data yang diubah sudah sesuai?');
    if (!confirmation) {
        e.preventDefault();
        return;
    }

    setTimeout(() => {
        window.location.href = "{{ route('profile') }}";
    }, 500);
});
    </script>
</body>
</html>
