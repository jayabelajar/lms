<x-guest-layout>
    <div class="relative flex flex-col min-h-screen bg-white dark:bg-gray-950 md:flex-row" x-data>

        <div class="absolute top-4 right-4 z-[60] md:top-6 md:right-6">
            <button @click="$store.theme.toggle()"
                class="w-10 h-10 md:w-11 md:h-11 flex items-center justify-center rounded-xl md:rounded-2xl bg-white/70 dark:bg-gray-900/60 backdrop-blur border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:text-indigo-600 transition-all active:scale-95">
                <i class="ti text-xl transition-transform duration-200"
                    :class="$store.theme.theme === 'light' ? 'ti-moon' : 'ti-sun rotate-180'"></i>
            </button>
        </div>

        <div class="hidden md:flex md:w-1/2 lg:w-3/5 relative overflow-hidden bg-indigo-50 dark:bg-indigo-600 sticky top-0 h-screen">
            <div class="absolute inset-0 z-0">
                <div class="absolute -top-[15%] -left-[15%] w-[70%] h-[70%] rounded-full blur-[140px] bg-indigo-300/40 dark:bg-purple-500/50"></div>
                <div class="absolute -bottom-[15%] -right-[15%] w-[60%] h-[60%] rounded-full blur-[120px] bg-sky-300/40 dark:bg-indigo-400/40"></div>

                <div class="absolute inset-0 opacity-20 dark:hidden" style="background-image: radial-gradient(circle, rgba(0,0,0,.15) 1px, transparent 1px); background-size: 40px 40px;"></div>
                <div class="absolute inset-0 hidden dark:block opacity-20" style="background-image: radial-gradient(circle, rgba(255,255,255,.25) 1px, transparent 1px); background-size: 40px 40px;"></div>

                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[40%] rotate-12 backdrop-blur-md bg-white/40 dark:bg-white/5 border-y border-black/5 dark:border-white/10"></div>
            </div>

            <div class="relative z-10 w-full h-full p-12 lg:p-16 flex flex-col justify-between text-gray-900 dark:text-white">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-indigo-600 shadow-sm">
                        <i class="ti ti-school text-3xl font-black"></i>
                    </div>
                    <span class="text-2xl font-bold tracking-tight">{{ config('app.name', 'LMS') }}</span>
                </div>

                <div class="max-w-xl space-y-6 lg:space-y-8">
                    <div class="space-y-4">
                        <span class="inline-block px-4 py-2 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 dark:bg-white/10 dark:text-white border border-indigo-200 dark:border-white/20">
                           Learning Management System
                        </span>
                        <h2 class="text-4xl lg:text-6xl font-bold leading-[1.1] tracking-tight">
                           Manage courses<br>
                            <span class="text-indigo-600 dark:text-indigo-200">and enrollments</span>
                        </h2>
                    </div>
                    <p class="text-lg lg:text-xl font-medium leading-relaxed text-gray-700 dark:text-indigo-50/80">
                        Track teaching and learning progress with a clean dashboard.
                    </p>
                </div>

                <div class="flex items-center gap-6 text-sm font-medium text-gray-600 dark:text-white/60">
                    <span>&copy; {{ date('Y') }} {{ config('app.name', 'LMS') }}</span>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 lg:w-2/5 flex flex-col justify-center items-center p-6 sm:p-12 lg:p-20 bg-white dark:bg-gray-950">

            <div class="w-full mb-12 md:hidden">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-600/20">
                        <i class="ti ti-school text-2xl"></i>
                    </div>
                    <span class="text-xl font-bold dark:text-white tracking-tighter">{{ config('app.name', 'LMS') }}</span>
                </div>
            </div>

            <div class="w-full max-w-md space-y-8">
                <div class="space-y-2">
                    <h3 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">Welcome back</h3>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Please enter your details to sign in.</p>
                </div>

                @if (session('status'))
                    <div class="text-sm font-semibold text-emerald-600">{{ session('status') }}</div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-5">
                        <x-input label="Email address" name="email" type="email" icon="mail" placeholder="hello@example.com" required autofocus value="{{ old('email') }}" />

                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs text-indigo-600 font-bold hover:text-indigo-500 transition-colors">Forgot password?</a>
                                @endif
                            </div>
                            <x-input name="password" type="password" icon="lock" placeholder="********" required />
                        </div>
                    </div>

                    <div class="flex items-center">
                        <x-checkbox name="remember" label="Keep me signed in" />
                    </div>

                    <x-button type="submit" icon="login" variant="primary" class="w-full">
                        Sign In
                    </x-button>
                </form>

                @if (Route::has('register'))
                    <p class="text-center text-sm text-gray-500 font-medium">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline underline-offset-4 decoration-2">Create Account</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>
