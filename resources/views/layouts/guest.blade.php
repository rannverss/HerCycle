<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HerCycle') }} 💖</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-pink-50 via-white to-accent-lavender flex flex-col justify-center items-center p-4">
            <!-- Logo -->
            <div class="mb-8 text-center animate-fade-in">
                <a href="/" class="inline-flex flex-col items-center group">
                    <span class="text-5xl mb-2 group-hover:scale-110 transition-transform duration-300">🌸</span>
                    <span class="text-3xl font-bold bg-gradient-to-r from-pink-400 to-pink-300 bg-clip-text text-transparent">HerCycle</span>
                    <span class="text-her-text-light text-sm mt-1">Your personal wellness companion 💕</span>
                </a>
            </div>

            <!-- Content Card -->
            <div class="w-full sm:max-w-md animate-slide-up">
                <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-cute-lg p-8 border border-pink-100">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <p class="mt-8 text-her-text-light text-sm animate-fade-in">Made with 💕 by HerCycle</p>
        </div>
    </body>
</html>
