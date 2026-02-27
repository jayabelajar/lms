@php
    $user = auth()->user();
    $roleLabel = $user ? (ucfirst($user->getRoleNames()->first() ?? 'User')) : 'Guest';
@endphp

<header
    class="sticky top-0 z-[50] w-full border-b border-gray-100 bg-white/95 backdrop-blur transition-all duration-300 dark:border-gray-900 dark:bg-gray-950/90"
    x-data>

    <div class="flex items-center justify-between px-4 py-3 sm:px-6">

        <div class="flex items-center gap-4">
            <button @click="$store.sidebar.toggleExpanded()"
                class="hidden h-10 w-10 items-center justify-center rounded-xl border border-gray-100 bg-white text-gray-500 transition-all hover:bg-gray-50 hover:text-indigo-600 active:scale-95 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 xl:flex">
                <i class="ti text-xl transition-transform duration-500"
                    :class="$store.sidebar.isExpanded ? 'ti-layout-sidebar-left-collapse' : 'ti-layout-sidebar-right-collapse rotate-180'">
                </i>
            </button>

            <button @click="$store.sidebar.toggleMobileOpen()"
                class="flex h-10 w-10 items-center justify-center rounded-xl border border-gray-100 bg-white text-gray-500 transition-all hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 xl:hidden">
                <i class="ti ti-menu-2 text-xl"></i>
            </button>

            <div class="flex md:hidden items-center gap-2 px-3 h-10 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-500 border border-transparent">
                <i class="ti ti-school text-lg text-indigo-500"></i>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">JLearn LMS</span>
            </div>
            <div class="hidden md:flex items-center gap-3 px-4 h-10 w-64 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-400 border border-transparent">
                <i class="ti ti-search text-lg"></i>
                <span class="text-sm font-medium">Cari...</span>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-3">
            <button @click="$store.theme.toggle()"
                class="relative h-10 w-10 flex items-center justify-center rounded-xl text-gray-500 transition-all duration-300 hover:bg-gray-100 active:scale-90 dark:text-gray-400 dark:hover:bg-gray-900"
                title="Toggle Theme">
                <i class="ti ti-moon text-xl absolute transition-all duration-500" x-show="$store.theme.theme === 'light'" x-transition></i>
                <i class="ti ti-sun text-xl absolute transition-all duration-500 text-amber-400" x-show="$store.theme.theme === 'dark'" x-transition></i>
            </button>

            <div class="mx-1 h-6 w-px bg-gray-200 dark:bg-gray-800"></div>

            <div class="relative" x-data="{ userOpen: false }">
                <button @click="userOpen = !userOpen" class="flex items-center gap-3 rounded-2xl p-1 group">
                    <div class="hidden md:flex flex-col items-end">
                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-none mb-1 group-hover:text-indigo-600 transition-colors truncate">
                            {{ $user->name ?? 'Guest' }}
                        </p>
                        <p class="text-xs font-bold text-gray-400 truncate">{{ $roleLabel }}</p>
                    </div>
                    <x-avatar :src="null" :name="($user->name ?? 'G')" size="sm" shape="xl" status="online" />
                </button>

                <div x-show="userOpen" @click.outside="userOpen = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    class="absolute right-0 mt-3 w-64 origin-top-right rounded-3xl border border-gray-100 bg-white p-2 dark:border-gray-900 dark:bg-gray-950 z-50"
                    x-cloak>

                    <div class="flex items-center gap-3 px-4 py-4 border-b border-gray-50 dark:border-gray-900 mb-2">
                        <x-avatar :src="null" :name="($user->name ?? 'G')" size="md" shape="xl" />
                        <div class="flex-1 overflow-hidden">
                            <p class="truncate text-sm font-bold text-gray-900 dark:text-white leading-none mb-1">
                                {{ $user->name ?? 'Guest' }}
                            </p>
                            <p class="truncate text-[10px] font-medium text-gray-400">
                                {{ $user->email ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-semibold text-gray-600 transition-colors hover:bg-indigo-50 hover:text-indigo-600 dark:text-gray-400 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400">
                            <i class="ti ti-user-circle text-lg text-indigo-500"></i> Profil
                        </a>
                        <hr class="mx-4 my-2 border-gray-50 dark:border-gray-900">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="flex w-full items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-bold text-rose-600 transition-colors hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10">
                                <i class="ti ti-logout-2 text-lg"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
