    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6 max-w-xl" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex items-center gap-6 mb-6">
            <div class="relative shrink-0">
                @php
                    $avatarUrl = $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff';
                @endphp
                <img src="{{ $avatarUrl }}" class="w-24 h-24 rounded-2xl object-cover" alt="Avatar">
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-2">Profile Photo</p>
                <input type="file" name="avatar" id="avatar" accept="image/*" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 dark:file:bg-indigo-500/10 dark:file:text-indigo-400 dark:hover:file:bg-indigo-500/20 transition-all cursor-pointer">
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                <p class="mt-1 text-xs text-gray-400">JPG, GIF or PNG. Max size of 2MB.</p>
            </div>
        </div>

        <div>
            <x-input label="Name" id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
        </div>

        <div>
            <x-input label="Email" id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-button type="submit">{{ __('Save Changes') }}</x-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-emerald-600"
                ><i class="ti ti-check"></i> {{ __('Saved successfuly.') }}</p>
            @endif
        </div>
    </form>
