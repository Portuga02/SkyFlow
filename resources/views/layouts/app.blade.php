<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SkyFlow') }}</title>

        <!-- Favicon & PWA -->
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="theme-color" content="#0071c4">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>[x-cloak] { display: none !important; }</style>
    </head>
    <body class="font-figtree antialiased" x-data="{ mobileOpen: false }">
        <div class="min-h-screen bg-brand-50">

            <!-- Sidebar Overlay Mobile -->
            <div x-show="mobileOpen" @click="mobileOpen = false" 
                class="fixed inset-0 bg-black/50 z-30 md:hidden" x-transition></div>

            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <main class="md:ml-64">
                <!-- Mobile Header Button -->
                <div class="md:hidden bg-white border-b border-brand-100 px-4 py-3 flex items-center gap-3">
                    <button @click="mobileOpen = true" class="text-brand-600 hover:text-brand-700">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <span class="font-bold text-brand-950">SkyFlow</span>
                </div>

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white border-b border-brand-100">
                        <div class="px-4 sm:px-6 lg:px-8 py-6">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <div class="py-6 px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <!-- Search Modal (Global) -->
        <x-search-modal />

        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/service-worker.js').catch(() => {});
                });
            }
        </script>
    </body>
</html>