<x-app-layout>
    

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Instructors</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($courses as $course)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $course->title }}</span>
                                </td>
                                <td class="px-4 py-3">{{ $course->instructor?->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $course->pivot->status ?? 'approved' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-gray-500" colspan="3">No enrollments.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
