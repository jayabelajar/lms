<?php

$files = [
    'd:\Project\laravel\lms\resources\views\instructor\students\index.blade.php',
    'd:\Project\laravel\lms\resources\views\instructor\quizzes\index.blade.php',
    'd:\Project\laravel\lms\resources\views\instructor\courses\index.blade.php',
    'd:\Project\laravel\lms\resources\views\instructor\assignments\index.blade.php',
    'd:\Project\laravel\lms\resources\views\admin\courses\index.blade.php',
    'd:\Project\laravel\lms\resources\views\admin\users\index.blade.php',
    'd:\Project\laravel\lms\resources\views\admin\enrollments\index.blade.php',
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // instructor/quizzes + instructor/courses + admin/courses
    if (strpos($file, 'quizzes\index.blade.php') !== false || strpos($file, 'instructor\courses\index.blade.php') !== false || strpos($file, 'admin\courses\index.blade.php') !== false) {
        $search = '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">';
        $replace = '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-4 lg:col-span-5 w-full">';
        
        $content = str_replace($search, $replace, $content);

        // find input label="Search" and replace to label="Search Title" to fix spacing
        $content = str_replace('label="Search"', 'label="Search Title"', $content);
        
        // fix the Status select wrapping
        $searchStatus = '<div class="w-full space-y-2">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase ml-1 tracking-widest">Status</label>
                    <select name="status" class="w-full rounded-2xl py-3 text-sm font-bold bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-white border border-gray-200 dark:border-gray-700">';
        
        $replaceStatus = '</div>
                <div class="md:col-span-5 lg:col-span-4 w-full">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Filter Status</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="ti ti-toggle-left text-lg"></i>
                        </span>
                        <select name="status" class="w-full pl-10 pr-4 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:text-gray-200 transition-colors" onchange="this.form.submit()">';
        $content = str_replace($searchStatus, $replaceStatus, $content);
        
        // closing select tag logic
        $content = str_replace('</select>
                </div>
                <div class="flex items-end gap-3">', '</select>
                    </div>
                </div>
                <div class="md:col-span-3 lg:col-span-3 w-full flex items-center gap-2 sm:gap-3">', $content);
                
        // update buttons
        $content = preg_replace('/<x-button type="submit" icon="filter" class="w-full sm:w-auto">Apply<\/x-button>/', '<x-button type="submit" icon="filter" class="flex-1 justify-center">Filter</x-button>', $content);
        $content = preg_replace('/<x-button href="([^"]+)" variant="secondary" class="w-full sm:w-auto">Reset<\/x-button>/', '<x-button href="$1" variant="secondary" class="flex-1 justify-center">Reset</x-button>', $content);
    }

    if (strpos($file, 'enrollments\index.blade.php') !== false) {
        $search = '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">';
        $replace = '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-4 lg:col-span-5 w-full">';
        $content = str_replace($search, $replace, $content);
        
        $searchStatus = '<div class="w-full space-y-2">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase ml-1 tracking-widest">Status</label>
                    <select name="status" class="w-full rounded-2xl py-3 text-sm font-bold bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-white border border-gray-200 dark:border-gray-700">';
        
        $replaceStatus = '</div>
                <div class="md:col-span-5 lg:col-span-4 w-full">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Filter Status</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="ti ti-toggle-left text-lg"></i>
                        </span>
                        <select name="status" class="w-full pl-10 pr-4 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:text-gray-200 transition-colors" onchange="this.form.submit()">';
        $content = str_replace($searchStatus, $replaceStatus, $content);
        
        $content = str_replace('</select>
                </div>
                <div class="flex items-end gap-3">', '</select>
                    </div>
                </div>
                <div class="md:col-span-3 lg:col-span-3 w-full flex items-center gap-2 sm:gap-3">', $content);
                
        $content = preg_replace('/<x-button type="submit" icon="filter" class="w-full sm:w-auto">Apply<\/x-button>/', '<x-button type="submit" icon="filter" class="flex-1 justify-center">Filter</x-button>', $content);
        $content = preg_replace('/<x-button href="([^"]+)" variant="secondary" class="w-full sm:w-auto">Reset<\/x-button>/', '<x-button href="$1" variant="secondary" class="flex-1 justify-center">Reset</x-button>', $content);
    }

    if (strpos($file, 'assignments\index.blade.php') !== false) {
        $search = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
        $replace = '<div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-8 lg:col-span-9 w-full">';
        
        $content = str_replace($search, $replace, $content);
        $content = str_replace('label="Search"', 'label="Search Title"', $content);
        
        $content = str_replace('<div class="flex items-end gap-3">', '</div>
                <div class="md:col-span-4 lg:col-span-3 w-full flex items-center gap-2 sm:gap-3">', $content);
                
        $content = preg_replace('/<x-button type="submit" icon="filter" class="w-full sm:w-auto">Apply<\/x-button>/', '<x-button type="submit" icon="filter" class="flex-1 justify-center">Filter</x-button>', $content);
        $content = preg_replace('/<x-button href="([^"]+)" variant="secondary" class="w-full sm:w-auto">Reset<\/x-button>/', '<x-button href="$1" variant="secondary" class="flex-1 justify-center">Reset</x-button>', $content);
    }

    if (strpos($file, 'students\index.blade.php') !== false) {
        $formOld = '<form method="GET" action="{{ route(\'instructor.students.index\') }}" class="space-y-4">
            <x-select name="course_id" label="Courses" icon="book" required>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected($selectedCourse?->id === $course->id)>{{ $course->title }}</option>
                @endforeach
            </x-select>
            <x-button type="submit" icon="filter">Terapkan</x-button>
        </form>';
        
        $formNew = '<form method="GET" action="{{ route(\'instructor.students.index\') }}" class="p-4 sm:p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-8 lg:col-span-9 w-full">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Filter Course</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="ti ti-book text-lg"></i>
                        </span>
                        <select name="course_id" class="w-full pl-10 pr-4 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:text-gray-200 transition-colors" required onchange="this.form.submit()">
                            <option value="">Select a Course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @selected($selectedCourse?->id === $course->id)>{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="md:col-span-4 lg:col-span-3 w-full flex items-center gap-2 sm:gap-3">
                    <x-button type="submit" icon="filter" class="flex-1 justify-center">Filter</x-button>
                </div>
            </div>
        </form>';
        $content = str_replace($formOld, $formNew, $content);
    }
    
    if (strpos($file, 'users\index.blade.php') !== false) {
        // Find existing users logic
        $content = preg_replace('/<form method="GET" action="{{ route\(\'admin\.users\.index\'\) }}" class="space-y-4">/', '<form method="GET" action="{{ route(\'admin.users.index\') }}" class="p-4 sm:p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 space-y-4">', $content);
        
        $content = preg_replace('/<div class="w-full space-y-2">/', '<div>', $content);
        $content = preg_replace('/<label class="block text-\[11px\] font-bold text-gray-400 uppercase ml-1 tracking-widest">/', '<label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Filter ', $content);
        $content = preg_replace('/class="w-full rounded-2xl py-3 text-sm font-bold bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-white border border-gray-200 dark:border-gray-700"/', 'class="w-full pl-4 pr-4 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:text-gray-200 transition-colors"', $content);
        
        $content = preg_replace('/<x-button type="submit" icon="filter" class="w-full sm:w-auto">Apply<\/x-button>/', '<x-button type="submit" icon="filter" class="flex-1 justify-center">Filter</x-button>', $content);
        $content = preg_replace('/<x-button href="([^"]+)" variant="secondary" class="w-full sm:w-auto">Reset<\/x-button>/', '<x-button href="$1" variant="secondary" class="flex-1 justify-center">Reset</x-button>', $content);
        
        $content = preg_replace('/<div class="flex items-end gap-3">/', '<div class="w-full flex items-center gap-2 sm:gap-3 lg:col-span-2">', $content);
    }

    file_put_contents($file, $content);
}
echo "Done!";
