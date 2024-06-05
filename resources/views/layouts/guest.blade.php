<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
    <div class="flex flex-col space-y-4 min-h-screen text-gray-800 bg-gray-100">
        <main class="container mx-auto p-4 flex-1 px-4">
            <h1 class="text-3xl mb-4 mr-60 text-center">
                {{ $title ?? 'Title here...' }}
            </h1>
            <div class="font-sans text-gray-900 antialiased">
                {{ $slot }}
            </div>
        </main>
        <x-layout.footer />
    </div>
    @stack('script')
    @livewireScripts
    </body>
</html>
