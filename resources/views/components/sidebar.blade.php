@php
    $user = auth()->user();
    $role = $user?->getRoleNames()->first();

    $menuGroups = [];

    if ($user && $user->hasRole('admin')) {
        $menuGroups[] = [
            'title' => 'Admin',
            'items' => [
                ['name' => 'Dasbor', 'icon' => 'smart-home', 'route' => 'admin.dashboard', 'activePattern' => 'admin.dashboard'],
                ['name' => 'Mata Kuliah', 'icon' => 'book', 'route' => 'admin.courses.index', 'activePattern' => 'admin.courses.*'],
                ['name' => 'Pengguna', 'icon' => 'users', 'route' => 'admin.users.index', 'activePattern' => 'admin.users.*'],
                ['name' => 'Enrolmen', 'icon' => 'list-check', 'route' => 'admin.enrollments.index', 'activePattern' => 'admin.enrollments.*'],
                ['name' => 'Pengaturan', 'icon' => 'settings', 'route' => 'admin.settings.edit', 'activePattern' => 'admin.settings.*'],
            ],
        ];
    } elseif ($user && $user->hasRole('instructor')) {
        $menuGroups[] = [
            'title' => 'Dosen',
            'items' => [
                ['name' => 'Dasbor', 'icon' => 'smart-home', 'route' => 'instructor.dashboard', 'activePattern' => 'instructor.dashboard'],
                ['name' => 'Mata Kuliah', 'icon' => 'book', 'route' => 'instructor.courses.index', 'activePattern' => 'instructor.courses.*'],
                ['name' => 'Materi', 'icon' => 'file-text', 'route' => 'instructor.materials.overview', 'activePattern' => 'instructor.materials.*'],
                ['name' => 'Tugas', 'icon' => 'list-check', 'route' => 'instructor.assignments.overview', 'activePattern' => 'instructor.assignments.*'],
                ['name' => 'Mahasiswa', 'icon' => 'users', 'route' => 'instructor.students.index', 'activePattern' => 'instructor.students.*'],
                ['name' => 'Kuis', 'icon' => 'help', 'route' => 'instructor.quizzes.index', 'activePattern' => 'instructor.quizzes.*'],
                ['name' => 'Nilai', 'icon' => 'award', 'route' => 'instructor.grades.index', 'activePattern' => 'instructor.grades.*'],
                ['name' => 'Laporan', 'icon' => 'chart-bar', 'route' => 'instructor.reports.index', 'activePattern' => 'instructor.reports.*'],
            ],
        ];
    } elseif ($user && $user->hasRole('student')) {
        $menuGroups[] = [
            'title' => 'Mahasiswa',
            'items' => [
                ['name' => 'Dasbor', 'icon' => 'smart-home', 'route' => 'student.dashboard', 'activePattern' => 'student.dashboard'],
                ['name' => 'Mata Kuliah', 'icon' => 'book', 'route' => 'student.my-courses', 'activePattern' => 'student.my-courses'],
                ['name' => 'Tugas', 'icon' => 'list-check', 'route' => 'student.assignments.index', 'activePattern' => 'student.assignments.*'],
                ['name' => 'Kuis', 'icon' => 'help', 'route' => 'student.quizzes.index', 'activePattern' => 'student.quizzes.*'],
                ['name' => 'Progres', 'icon' => 'chart-bar', 'route' => 'student.progress.index', 'activePattern' => 'student.progress.*'],
            ],
        ];
    } else {
        $menuGroups = [];
    }
@endphp

<aside id="sidebar"
    class="fixed top-0 left-0 z-[9999] h-screen bg-white/90 backdrop-blur-xl dark:bg-gray-950/90 text-gray-900 transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)] border-r border-gray-200/50 dark:border-gray-800/50 flex flex-col"
    x-data="{
        openSubmenus: {},
        tooltipText: '',
        tooltipVisible: false,
        tooltipTop: 0,
        get expanded() {
            if (window.innerWidth < 1280) return true;
            return $store.sidebar.isExpanded;
        },
        toggleSubmenu(key) {
            if(!this.expanded) {
                $store.sidebar.toggleExpanded();
                setTimeout(() => { this.openSubmenus[key] = true; }, 100);
            } else {
                this.openSubmenus[key] = !this.openSubmenus[key];
            }
        },
        showTooltip(e, text) {
            if(!this.expanded) {
                this.tooltipText = text;
                this.tooltipVisible = true;
                this.tooltipTop = e.currentTarget.getBoundingClientRect().top + (e.currentTarget.offsetHeight / 2);
            }
        }
    }"
    :class="{
        'w-72': expanded,
        'w-20': !expanded,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen,
        'translate-x-0': $store.sidebar.isMobileOpen,
    }"
    @mouseleave="tooltipVisible = false">

   <div x-show="tooltipVisible && !expanded"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="fixed left-[90px] z-[10000] px-4 py-2.5 bg-gray-900 dark:bg-indigo-600 text-white text-[13px] font-bold rounded-xl pointer-events-none shadow-2xl min-w-[120px] whitespace-nowrap border border-white/10"
         :style="`top: ${tooltipTop}px; transform: translateY(-50%);`"
         x-cloak>
        <div class="flex items-center gap-2">
            <span x-text="tooltipText"></span>
        </div>
        <div class="absolute left-0 top-1/2 -translate-x-full -translate-y-1/2 w-0 h-0 border-y-[7px] border-y-transparent border-r-[7px] border-r-gray-900 dark:border-r-indigo-600"></div>
    </div>

    <div class="h-18 flex items-center p-4 overflow-hidden shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-4 group shrink-0">
            <div class="relative w-12 h-12 bg-gradient-to-tr from-indigo-600 to-violet-500 rounded-2xl flex items-center justify-center transition-all duration-500 group-hover:rotate-[10deg] group-hover:scale-110">
                <i class="ti ti-school text-white text-2xl"></i>
            </div>
            <div x-show="expanded" x-transition class="flex flex-col">
                <span class="font-bold text-lg dark:text-white leading-none">
                    {{ config('app.name', 'LMS') }}
                </span>
                <span class="text-sm font-semibold text-gray-400">JLearn LMS</span>
            </div>
        </a>
    </div>

    <div class="flex-1 overflow-y-auto no-scrollbar py-4 px-4">
        <nav class="space-y-6">
            @foreach ($menuGroups as $gIdx => $group)
                <div class="space-y-2">
                    <h2 class="px-4 text-[11px] font-semibold text-gray-400 dark:text-gray-500 tracking-wider"
                        x-show="expanded" x-cloak>
                        {{ $group['title'] }}
                    </h2>
                    <ul class="space-y-1">
                        @foreach ($group['items'] as $iIdx => $item)
                            @php
                                $isSingleActive = isset($item['route']) && request()->routeIs($item['activePattern'] ?? $item['route']);
                            @endphp
                            <li class="relative">
                                <a href="{{ route($item['route']) }}"
                                   @mouseenter="showTooltip($event, '{{ $item['name'] }}')"
                                   class="flex items-center py-2.5 rounded-xl group relative overflow-hidden transition-all duration-300"
                                   :class="[
                                       {{ $isSingleActive ? 'true' : 'false' }} ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900/50',
                                       expanded ? 'px-4' : 'justify-center'
                                   ]">
                                    <i class="ti ti-{{ $item['icon'] }} text-xl shrink-0 z-10 transition-transform duration-300 group-hover:scale-110"></i>
                                    <span x-show="expanded" x-cloak class="ml-3 font-semibold text-sm grow z-10 flex items-center">
                                        {{ $item['name'] }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>
    </div>

    <div class="p-4 border-t border-gray-100 dark:border-gray-800 space-y-3 shrink-0">
        <div class="bg-gray-100/50 dark:bg-gray-900/50 rounded-xl p-1 flex items-center gap-1" x-show="expanded" x-cloak>
            <button @click="$store.theme.toggle()" class="flex-1 py-2 rounded-lg transition-all flex justify-center items-center gap-2 text-xs font-semibold"
                    :class="$store.theme.theme === 'light' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-400'">
                <i class="ti ti-sun"></i> Light
            </button>
            <button @click="$store.theme.toggle()" class="flex-1 py-2 rounded-lg transition-all flex justify-center items-center gap-2 text-xs font-semibold"
                    :class="$store.theme.theme === 'dark' ? 'bg-gray-800 text-indigo-400 shadow-sm' : 'text-gray-400'">
                <i class="ti ti-moon"></i> Dark
            </button>
        </div>

        <div class="relative group cursor-pointer bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-3 border border-transparent transition-all hover:border-indigo-500/30"
             @click="!expanded ? $store.sidebar.toggleExpanded() : null">
            <div class="flex items-center gap-3" :class="expanded ? '' : 'justify-center'">
                <div class="relative shrink-0">
                    @php
                        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($user?->name ?? 'User') . '&background=6366f1&color=fff';
                    @endphp
                    <img src="{{ $avatarUrl }}" class="w-9 h-9 rounded-xl" alt="User">
                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-950 rounded-full"></div>
                </div>
                <div x-show="expanded" x-cloak class="overflow-hidden">
                    <p class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $user->name ?? 'Guest' }}</p>
                    <p class="text-[10px] text-gray-400 truncate">{{ $role ? ucfirst($role) : 'Visitor' }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>


