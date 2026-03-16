<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Authentication' }} | {{ config('app.name', 'LMS') }}</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css" />

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Theme Initializer --}}
    <script>
        function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        initTheme();
        document.addEventListener('livewire:navigated', initTheme);
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="h-full bg-white dark:bg-gray-950 antialiased selection:bg-indigo-100 dark:selection:bg-indigo-900/30">
    <main>
        {{ $slot }}
    </main>

    <x-toast />

    @livewireScriptConfig
    @stack('scripts')
</body>
</html>
