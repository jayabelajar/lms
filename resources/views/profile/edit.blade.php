<x-app-layout>
    <div class="space-y-6">
        <x-card>
            <x-slot name="header">
                <div class="flex flex-col gap-1 w-full">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Profile Information</h3>
                    </div>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">
                        Update your account's profile information and email address.
                    </p>
                </div>
            </x-slot>
            @include('profile.partials.update-profile-information-form')
        </x-card>

        <x-card>
            <x-slot name="header">
                <div class="flex flex-col gap-1 w-full">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Update Password</h3>
                    </div>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">
                        Ensure your account is using a long, random password to stay secure.
                    </p>
                </div>
            </x-slot>
            @include('profile.partials.update-password-form')
        </x-card>

        <x-card>
            <x-slot name="header">
                <div class="flex flex-col gap-1 w-full">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Delete Account</h3>
                    </div>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                    </p>
                </div>
            </x-slot>
            @include('profile.partials.delete-user-form')
        </x-card>
    </div>
</x-app-layout>
