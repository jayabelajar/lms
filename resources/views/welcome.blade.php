<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LMS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
</head>
<body class="bg-gray-50 dark:bg-gray-950 font-sans antialiased text-gray-900 dark:text-gray-100" x-data>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white dark:bg-gray-950 border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-600/20">
                        <i class="ti ti-school text-2xl font-black"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight">{{ config('app.name', 'LMS') }}</span>
                </div>

                <!-- Right Side -->
                <div class="flex items-center gap-4">
                    <button @click="$store.theme.toggle()" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <i class="ti text-xl" :class="$store.theme.theme === 'light' ? 'ti-moon' : 'ti-sun'"></i>
                    </button>

                    @auth
                        <a href="{{ url('/student/dashboard') }}" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm transition-colors shadow-md shadow-indigo-600/20">
                            Dashboard
                        </a>
                    @else
                        <div class="hidden md:flex gap-3">
                            <a href="{{ route('student.login') }}" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm transition-colors shadow-md shadow-indigo-600/20">
                                Valid Login
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative min-h-screen flex items-center justify-center pt-16 overflow-hidden">
        
        <!-- Background Decor -->
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div class="absolute top-1/4 -left-1/4 w-[50%] h-[50%] rounded-full blur-[140px] bg-indigo-500/20 dark:bg-indigo-500/30"></div>
            <div class="absolute bottom-1/4 -right-1/4 w-[50%] h-[50%] rounded-full blur-[120px] bg-purple-500/20 dark:bg-purple-500/30"></div>
            
            <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05]" style="background-image: radial-gradient(#4f46e5 1px, transparent 1px); background-size: 32px 32px;"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 mb-8">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                The Next Generation LMS
            </span>

            <h1 class="text-5xl md:text-7xl font-bold tracking-tight mb-6">
                Redefining the way<br />
                you <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">learn and teach</span>
            </h1>

            <p class="max-w-2xl mx-auto text-lg md:text-xl text-gray-600 dark:text-gray-400 mb-10 leading-relaxed font-medium">
                A seamless, intuitive platform designed to empower educators, engage students, and simplify management.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ url('/student/dashboard') }}" class="group w-full sm:w-auto px-8 py-4 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-lg transition-all shadow-lg shadow-indigo-600/25 flex items-center justify-center gap-3">
                        Enter Dashboard
                        <i class="ti ti-arrow-right transition-transform group-hover:translate-x-1"></i>
                    </a>
                @else
                    <a href="{{ route('student.login') }}" class="group w-full sm:w-auto px-8 py-4 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-lg transition-all shadow-lg shadow-indigo-600/25 flex items-center justify-center gap-3">
                        Start Learning Now
                        <i class="ti ti-arrow-right transition-transform group-hover:translate-x-1"></i>
                    </a>
                    <a href="{{ route('instructor.login') }}" class="w-full sm:w-auto px-8 py-4 rounded-full border-2 border-gray-200 dark:border-gray-800 hover:border-gray-300 dark:hover:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900 font-bold text-lg transition-colors flex items-center justify-center gap-3 text-gray-600 dark:text-gray-300">
                        I am an Instructor
                    </a>
                @endauth
            </div>
            
            <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto border-t border-gray-200 dark:border-gray-800 pt-10">
                <div>
                    <h4 class="text-3xl font-black text-indigo-600 dark:text-indigo-400">10k+</h4>
                    <p class="text-sm font-semibold text-gray-500 mt-1 uppercase tracking-wider">Active Students</p>
                </div>
                <div>
                    <h4 class="text-3xl font-black text-indigo-600 dark:text-indigo-400">500+</h4>
                    <p class="text-sm font-semibold text-gray-500 mt-1 uppercase tracking-wider">Expert Instructors</p>
                </div>
                <div>
                    <h4 class="text-3xl font-black text-indigo-600 dark:text-indigo-400">2.5k</h4>
                    <p class="text-sm font-semibold text-gray-500 mt-1 uppercase tracking-wider">Premium Courses</p>
                </div>
                <div>
                    <h4 class="text-3xl font-black text-indigo-600 dark:text-indigo-400">4.9/5</h4>
                    <p class="text-sm font-semibold text-gray-500 mt-1 uppercase tracking-wider">User Rating</p>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
