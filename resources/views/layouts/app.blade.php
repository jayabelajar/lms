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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css" />

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        @if(request()->has('drawer') || request()->header('X-Drawer'))
            .app-header, header { display: none !important; }
            body { background: transparent !important; min-height: 100vh; }
            /* Hide back buttons inside the iframe drawer */
            [variant="secondary"][icon="arrow-left"] { display: none !important; }
        @endif
    </style>

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
</head>

<body
    class="h-full bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-300 antialiased overflow-x-hidden"
    x-data @resize.window="if (window.innerWidth < 1280) { $store.sidebar.isExpanded = false; }">

    @if(request()->has('drawer') || request()->header('X-Drawer'))
        <div class="px-6 py-6 pb-24">
            {{ $slot }}
        </div>
        <x-toast />
        @livewireScriptConfig
        @stack('scripts')
        <script>
            document.addEventListener('submit', function(e) {
                if (e.target.tagName === 'FORM') {
                    if (e.target.method.toUpperCase() !== 'GET') {
                        let action = new URL(e.target.action, window.location.origin);
                        action.searchParams.set('drawer', '1');
                        e.target.action = action.toString();
                    }
                }
            });

            document.addEventListener('click', function(e) {
                let link = e.target.closest('a');
                if (link && link.href && !link.href.startsWith('javascript') && !link.getAttribute('href').startsWith('#')) {
                    if (link.hostname === window.location.hostname && !link.hasAttribute('target')) {
                        let url = new URL(link.href);
                        if (!url.searchParams.has('drawer')) {
                            url.searchParams.set('drawer', '1');
                            link.href = url.toString();
                        }
                    }
                }
            });

            if (window !== window.parent) {
                @if(session('status') || session('success'))
                    window.parent.postMessage({ action: 'drawer-success', message: '{!! addslashes(session("status") ?? session("success")) !!}' }, '*');
                @endif
            }
        </script>
    @else
        <div class="min-h-screen flex">
            <div x-show="$store.sidebar.isMobileOpen" x-cloak x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="$store.sidebar.toggleMobileOpen()"
                class="fixed inset-0 z-[55] bg-gray-900/80 xl:hidden">
            </div>

            <x-sidebar />

            <div class="flex-1 flex flex-col min-h-screen transition-all duration-300 ease-in-out min-w-0"
                :class="{
                    'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                    'xl:ml-[80px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                }">

                <div class="app-header sticky top-0 z-[50]">
                    <x-header />
                </div>

                <main class="flex-1 p-4 md:p-8 overflow-x-hidden min-w-0">
                    <div class="max-w-screen-2xl mx-auto space-y-6 w-full min-w-0">
                        @isset($header)
                            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl px-6 py-4 shadow-sm app-header">
                                {{ $header }}
                            </div>
                        @endisset

                        {{ $slot }}
                    </div>
                </main>

                <footer class="p-6 text-center text-xs text-gray-400 font-medium app-header">
                    <p class="text-xs text-gray-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'LMS') }}.
                    </p>
                </footer>
            </div>
        </div>

        <!-- Drawer Component -->
        <div x-data="{ drawerOpen: false, drawerUrl: '', drawerTitle: 'Manage' }"
            @open-drawer.window="drawerUrl = $event.detail.url; drawerTitle = $event.detail.title || 'Manage'; drawerOpen = true; document.body.style.overflow = 'hidden';"
            @close-drawer.window="drawerOpen = false; setTimeout(() => drawerUrl = '', 300); document.body.style.overflow = '';"
            class="relative z-[100]"
            x-cloak>
            
            <div x-show="drawerOpen"
                x-transition:enter="ease-in-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in-out duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/80"
                @click="$dispatch('close-drawer')"></div>

            <div class="fixed inset-y-0 right-0 flex max-w-full w-full sm:w-[500px] xl:w-[600px] pointer-events-none">
                <div x-show="drawerOpen"
                    x-transition:enter="transform transition ease-in-out duration-300"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-300"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="w-full h-full flex flex-col bg-white dark:bg-gray-900 shadow-2xl pointer-events-auto">
                    
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50 dark:bg-gray-900/50">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="drawerTitle"></h3>
                        <button @click="$dispatch('close-drawer')" class="text-gray-400 hover:text-gray-500 bg-gray-100 dark:bg-gray-800 p-2 rounded-full transition-colors focus:outline-none">
                            <i class="ti ti-x text-xl"></i>
                        </button>
                    </div>
                    
                    <div class="flex-1 w-full h-full relative overflow-y-auto">
                        <template x-if="drawerUrl">
                            <iframe :src="drawerUrl" class="w-full h-full absolute inset-0 border-0 bg-transparent" id="drawer-iframe"></iframe>
                        </template>
                        <div x-show="!drawerUrl" class="flex items-center justify-center p-12 text-gray-400 h-full">
                            Loading...
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-toast />

        <script>
            window.addEventListener('message', event => {
                if (event.data && event.data.action === 'drawer-success') {
                    window.dispatchEvent(new CustomEvent('close-drawer'));
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: event.data.message } }));
                    setTimeout(() => window.location.reload(), 1500);
                }
            });

            if (window !== window.parent) {
                @if(session('status') || session('success'))
                    window.parent.postMessage({ action: 'drawer-success', message: '{{ session("status") ?? session("success") }}' }, '*');
                @endif
            }

            document.addEventListener('alpine:init', () => {
                document.body.addEventListener('click', (e) => {
                    const link = e.target.closest('a[data-drawer]');
                    if (link) {
                        e.preventDefault();
                        let href = link.getAttribute('href');
                        if (href && href !== '#' && !href.startsWith('javascript')) {
                            let url = new URL(href, window.location.origin);
                            url.searchParams.set('drawer', '1');
                            window.dispatchEvent(new CustomEvent('open-drawer', {
                                detail: { url: url.toString(), title: link.getAttribute('data-drawer-title') || 'Manage' }
                            }));
                        }
                    }
                });
            });
        </script>

        @livewireScriptConfig
        @stack('scripts')
    @endif
</body>
</html>
