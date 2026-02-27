<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth overflow-x-hidden">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'LMS') }}</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    {{-- Theme Initializer --}}
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>

<body
    class="h-full bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-300 antialiased overflow-x-hidden"
    x-data @resize.window="if (window.innerWidth < 1280) { $store.sidebar.isExpanded = false; }">

    <div class="min-h-screen flex">
        <div x-show="$store.sidebar.isMobileOpen" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="$store.sidebar.toggleMobileOpen()"
            class="fixed inset-0 z-[55] bg-gray-900/50 backdrop-blur-sm xl:hidden">
        </div>

        <x-sidebar />

        <div class="flex-1 flex flex-col min-h-screen transition-all duration-300 ease-in-out min-w-0"
            :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[80px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
            }">

            <x-header />

            <main class="flex-1 p-4 md:p-8 overflow-x-hidden min-w-0">
                <div class="max-w-screen-2xl mx-auto space-y-6 w-full min-w-0">
                    @isset($header)
                        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl px-6 py-4 shadow-sm">
                            {{ $header }}
                        </div>
                    @endisset

                    {{ $slot }}
                </div>
            </main>

            <footer class="p-6 text-center text-xs text-gray-400 font-medium">
                <p class="text-xs text-gray-500">
                   &copy; {{ date('Y') }} {{ config('app.name', 'LMS') }}.
                </p>
            </footer>
        </div>
    </div>

    <x-toast />

    @stack('scripts')
</body>
</html>
