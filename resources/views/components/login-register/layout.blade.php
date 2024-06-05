<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description ?? 'Staff Administration Application' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <x-layout.favicons />
    <title>Staff Administration Application: {{ $title ?? '' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-montserrat antialiased">
<div class="flex flex-col space-y-4 min-h-screen text-gray-800 bg-pastel-blue">
    <header class="shadow bg-white sticky inset-0 backdrop-blur-sm z-10">
        {{--  Navigation  --}}
        <x-layout.nav/>
    </header>
    {{-- Title --}}
    <main class="container mx-auto p-4 flex-1 px-4">
        <h1 class="text-3xl mb-4 pl-4 text-left">
            {{ $title ?? 'Title here...' }}
        </h1>
        {{-- Main content --}}
        {{ $slot }}
    </main>
    <x-layout.footer />
</div>
@stack('script')
@livewireScripts
</body>
</html>
