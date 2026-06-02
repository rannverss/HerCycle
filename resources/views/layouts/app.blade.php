<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="HerCycle — Your personal mood, diary, and menstrual cycle tracker 💖">

        <title>{{ config('app.name', 'HerCycle') }} 💖</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-her-bg">
            <!-- Navigation -->
            @include('layouts.navigation')

            <!-- Flash Messages -->
            @if (session('success'))
                <div id="flash-message" class="fixed top-4 right-4 z-50 animate-slide-up">
                    <div class="bg-white border border-green-200 rounded-2xl shadow-cute-lg px-6 py-4 flex items-center gap-3">
                        <span class="text-2xl">✅</span>
                        <p class="text-her-text font-medium">{{ session('success') }}</p>
                        <button onclick="document.getElementById('flash-message').remove()" class="ml-2 text-her-text-light hover:text-her-text">✕</button>
                    </div>
                </div>
                <script>
                    setTimeout(() => {
                        const el = document.getElementById('flash-message');
                        if (el) el.style.opacity = '0';
                        setTimeout(() => { if (el) el.remove(); }, 300);
                    }, 3000);
                </script>
            @endif

            @if ($errors->any())
                <div id="error-message" class="fixed top-4 right-4 z-50 animate-slide-up">
                    <div class="bg-white border border-red-200 rounded-2xl shadow-cute-lg px-6 py-4 flex items-center gap-3">
                        <span class="text-2xl">❌</span>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-her-text font-medium text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                        <button onclick="document.getElementById('error-message').remove()" class="ml-2 text-her-text-light hover:text-her-text">✕</button>
                    </div>
                </div>
                <script>
                    setTimeout(() => {
                        const el = document.getElementById('error-message');
                        if (el) el.style.opacity = '0';
                        setTimeout(() => { if (el) el.remove(); }, 300);
                    }, 4000);
                </script>
            @endif

            <!-- Page Content -->
            <main class="pb-8">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="text-center py-6 text-her-text-light text-sm">
                <p>Made with 💕 by HerCycle • {{ date('Y') }}</p>
            </footer>
        </div>
    </body>
</html>
