<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;800&display=swap" rel="stylesheet">
    <meta name="description" content="{{ $description ?? 'Staff Administration Application' }}">
    <x-layout.favicons/>
    <title>Staff Administration Application: {{ $title ?? '' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-montserrat antialiased">
<div class="flex flex-col justify-between space-y-4 xl:space-y-0 min-h-screen text-gray-800 bg-pastel-blue w-full">
    <main class=" flex flex-col justify-between sm:ml-64">
        @livewire('layout.nav-bar')

{{--        <header class="shadow bg-white/70 sticky inset-0 backdrop-blur-sm z-10 mb-4">--}}
{{--            --}}{{-- Navigation --}}

{{--        </header>--}}

        {{-- Title --}}
        <div class="mx-2">
            <h1 class="text-3xl mb-4">
                {{ $title ?? 'Title here...' }}
            </h1>
            {{-- Main content --}}
            {{ $slot }}
        </div>
    </main>

    <x-layout.footer/>

</div>

@stack('script')
@livewireScripts
</body>
</html>
