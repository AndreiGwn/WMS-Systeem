<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WMS Kantoor') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-100 min-h-screen">
        <!-- Desktop Centered App Canvas / Mobile Viewport Canvas -->
        <div class="max-w-2xl mx-auto min-h-screen bg-slate-50 flex flex-col shadow-xl border-x border-slate-200 relative">
            <livewire:layout.navigation />

            <!-- Main Content Area with Bottom Padding for Bottom Navigation -->
            <main class="flex-1 px-4 pt-4 pb-28">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
