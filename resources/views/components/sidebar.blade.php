@php
    $user = auth()->user();
    $role = $user?->getRoleNames()->first();

    $menuGroups = [];

    if ($user && $user->hasRole('admin')) {
        $menuGroups[] = [
            'title' => 'Admin Panel',
            'items' => [
                ['name' => 'Dashboard', 'icon' => 'smart-home', 'route' => 'admin.dashboard', 'activePattern' => 'admin.dashboard'],
                ['name' => 'Courses', 'icon' => 'book', 'route' => 'admin.courses.index', 'activePattern' => 'admin.courses.*'],
                ['name' => 'Users', 'icon' => 'users', 'route' => 'admin.users.index', 'activePattern' => 'admin.users.*'],
                ['name' => 'Settings', 'icon' => 'settings', 'route' => 'admin.settings.edit', 'activePattern' => 'admin.settings.*'],
            ],
        ];
    } elseif ($user && $user->hasRole('instructor')) {
        $menuGroups[] = [
            'title' => 'Main Panel',
            'items' => [
                ['name' => 'Dashboard', 'icon' => 'smart-home', 'route' => 'instructor.dashboard', 'activePattern' => 'instructor.dashboard'],
                ['name' => 'Courses', 'icon' => 'book', 'route' => 'instructor.courses.index', 'activePattern' => 'instructor.courses.*'],
            ],
        ];
        $menuGroups[] = [
            'title' => 'Learning Contents',
            'items' => [
                ['name' => 'Materials', 'icon' => 'file-text', 'route' => 'instructor.materials.overview', 'activePattern' => 'instructor.materials.*'],
                ['name' => 'Assignments', 'icon' => 'list-check', 'route' => 'instructor.assignments.overview', 'activePattern' => 'instructor.assignments.*'],
                ['name' => 'Quizzes', 'icon' => 'help', 'route' => 'instructor.quizzes.index', 'activePattern' => 'instructor.quizzes.*'],
            ],
        ];
        $menuGroups[] = [
            'title' => 'Evaluations',
            'items' => [
                ['name' => 'Students', 'icon' => 'users', 'route' => 'instructor.students.index', 'activePattern' => 'instructor.students.*'],
                ['name' => 'Grades', 'icon' => 'award', 'route' => 'instructor.grades.index', 'activePattern' => 'instructor.grades.*'],
                ['name' => 'Reports', 'icon' => 'chart-bar', 'route' => 'instructor.reports.index', 'activePattern' => 'instructor.reports.*'],
            ],
        ];
    } elseif ($user && $user->hasRole('student')) {
        $menuGroups[] = [
            'title' => 'Student Panel',
            'items' => [
                ['name' => 'Dashboard', 'icon' => 'smart-home', 'route' => 'student.dashboard', 'activePattern' => 'student.dashboard'],
                ['name' => 'My Courses', 'icon' => 'book', 'route' => 'student.my-courses', 'activePattern' => 'student.my-courses'],
                ['name' => 'Assignments', 'icon' => 'list-check', 'route' => 'student.assignments.index', 'activePattern' => 'student.assignments.*'],
                ['name' => 'Quizzes', 'icon' => 'help', 'route' => 'student.quizzes.index', 'activePattern' => 'student.quizzes.*'],
                ['name' => 'My Progress', 'icon' => 'chart-bar', 'route' => 'student.progress.index', 'activePattern' => 'student.progress.*'],
            ],
        ];
    } else {
        $menuGroups = [];
    }
@endphp

<aside id="sidebar"
    class="fixed top-0 left-0 z-[9999] h-screen bg-white dark:bg-gray-950 text-gray-900 transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)] border-r border-gray-200/50 dark:border-gray-800/50 flex flex-col"
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

    <div class="h-18 flex items-center p-4 overflow-hidden shrink-0 border-b border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-950/50">
        <a wire:navigate href="{{ route('dashboard') }}" class="flex items-center gap-3 group shrink-0">
            <div class="relative w-10 h-10 bg-gradient-to-tr from-indigo-600 to-violet-500 rounded-xl flex items-center justify-center transition-all duration-500 group-hover:rotate-[10deg] group-hover:scale-110">
                <i class="ti ti-school text-white text-xl"></i>
            </div>
            <div x-show="expanded" x-transition class="flex flex-col">
                <span class="font-bold text-base dark:text-white leading-tight">
                    JLearn
                </span>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $role ? ucfirst($role) . ' Panel' : 'User Panel' }}</span>
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
                                <a wire:navigate href="{{ route($item['route']) }}"
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
            <button @click="$store.theme.toggle()" class="flex-1 py-1.5 rounded-lg transition-all flex justify-center items-center gap-2 text-xs font-semibold"
                    :class="$store.theme.theme === 'light' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-400'">
                <i class="ti ti-sun text-sm"></i> Light
            </button>
            <button @click="$store.theme.toggle()" class="flex-1 py-1.5 rounded-lg transition-all flex justify-center items-center gap-2 text-xs font-semibold"
                    :class="$store.theme.theme === 'dark' ? 'bg-gray-800 text-indigo-400 shadow-sm' : 'text-gray-400'">
                <i class="ti ti-moon text-sm"></i> Dark
            </button>
        </div>

        <div class="relative mt-2 bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-2.5 border border-transparent transition-all flex items-center justify-between"
             :class="expanded ? '' : 'justify-center cursor-pointer hover:border-indigo-500/30'"
             @click="!expanded ? $store.sidebar.toggleExpanded() : null">
            <div class="flex items-center gap-3 w-full" :class="expanded ? '' : 'justify-center'">
                <div class="relative shrink-0">
                    @php
                        $avatarUrl = $user?->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user?->name ?? 'User') . '&background=6366f1&color=fff';
                    @endphp
                    <img src="{{ $avatarUrl }}" class="w-9 h-9 rounded-xl" alt="User">
                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-950 rounded-full"></div>
                </div>
                <div x-show="expanded" x-cloak class="overflow-hidden flex-1 cursor-pointer" @click="window.location.href='{{ route('profile.edit') }}'">
                    <p class="text-xs font-bold text-gray-900 dark:text-white truncate hover:text-indigo-600 transition-colors">{{ $user->name ?? 'Guest' }}</p>
                    <p class="text-[10px] text-gray-400 truncate">{{ $role ? ucfirst($role) : 'Visitor' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" x-show="expanded" x-cloak class="shrink-0">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 p-1.5 rounded-lg transition-colors flex items-center justify-center" title="Logout">
                    <i class="ti ti-logout text-lg"></i>
                </button>
            </form>
        </div>
    </div>
</aside>


