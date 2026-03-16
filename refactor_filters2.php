<?php

$files = [
    'd:\Project\laravel\lms\resources\views\instructor\students\index.blade.php',
    'd:\Project\laravel\lms\resources\views\instructor\quizzes\index.blade.php',
    'd:\Project\laravel\lms\resources\views\instructor\courses\index.blade.php',
    'd:\Project\laravel\lms\resources\views\instructor\materials\overview.blade.php',
    'd:\Project\laravel\lms\resources\views\admin\courses\index.blade.php',
    'd:\Project\laravel\lms\resources\views\admin\users\index.blade.php',
    'd:\Project\laravel\lms\resources\views\admin\enrollments\index.blade.php',
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Convert hardcoded selects to x-select
    // Form 1: Filter Status
    $statusBlockOld = '<div class="md:col-span-5 lg:col-span-4 w-full">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Filter Status</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="ti ti-toggle-left text-lg"></i>
                        </span>
                        <select name="status" class="w-full pl-10 pr-4 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:text-gray-200 transition-colors" onchange="this.form.submit()">
                            <option value="">All</option>
                            <option value="published" @selected(request(\'status\') === \'published\')>Published</option>
                            <option value="draft" @selected(request(\'status\') === \'draft\')>Draft</option>
                        </select>
                    </div>
                </div>';
                
    $statusBlockNew = '<div class="md:col-span-5 lg:col-span-4 w-full">
                    <x-select name="status" label="Filter Status" icon="toggle-left" placeholder="All Statuses" onchange="this.form.submit()">
                        <option value="published" @selected(request(\'status\') === \'published\')>Published</option>
                        <option value="draft" @selected(request(\'status\') === \'draft\')>Draft</option>
                    </x-select>
                </div>';
    
    // Try different variations of Draft/Draftt/All Statuses
    $patternStatus = '/<div class="md:col-span-5 lg:col-span-4 w-full">\s*<label[^>]*>Filter Status<\/label>\s*<div class="relative">\s*<span[^>]*>\s*<i class="ti ti-toggle-left text-lg"><\/i>\s*<\/span>\s*<select name="status"[^>]*onchange="this.form.submit\(\)"[^>]*>.*?<\/select>\s*<\/div>\s*<\/div>/is';

    $content = preg_replace_callback($patternStatus, function($matches) {
        $options = '';
        if (strpos($matches[0], 'draftt') !== false) {
            $options = '
                        <option value="published" @selected(request(\'status\') === \'published\')>Published</option>
                        <option value="draftt" @selected(request(\'status\') === \'draftt\')>Draft</option>';
        } elseif (strpos($matches[0], 'pending') !== false) {
            $options = '
                        <option value="approved" @selected(request(\'status\') === \'approved\')>Approved</option>
                        <option value="rejected" @selected(request(\'status\') === \'rejected\')>Rejected</option>
                        <option value="pending" @selected(request(\'status\') === \'pending\')>Pending</option>';
        } else {
            $options = '
                        <option value="published" @selected(request(\'status\') === \'published\')>Published</option>
                        <option value="draft" @selected(request(\'status\') === \'draft\')>Draft</option>';
        }
        
        return '<div class="md:col-span-5 lg:col-span-4 w-full">
                    <x-select name="status" label="Filter Status" icon="toggle-left" placeholder="All Statuses" onchange="this.form.submit()">' . $options . '
                    </x-select>
                </div>';
    }, $content);

    // Instructor Students
    if (strpos($file, 'students\index.blade.php') !== false) {
        // match Filter Course
        $patternSelect = '/<div class="md:col-span-8 lg:col-span-9 w-full">\s*<label[^>]*>Filter Course<\/label>.*?<\/select>\s*<\/div>\s*<\/div>/is';
        if (preg_match($patternSelect, $content)) {
            $replace = '<div class="md:col-span-8 lg:col-span-9 w-full">
                    <x-select name="course_id" label="Filter Course" icon="book" placeholder="Select a Course" required onchange="this.form.submit()">
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @selected($selectedCourse?->id === $course->id)>{{ $course->title }}</option>
                        @endforeach
                    </x-select>
                </div>';
            $content = preg_replace($patternSelect, $replace, $content);
        }
    }
    
    // Instructor Materials Overview
    if (strpos($file, 'materials\overview.blade.php') !== false) {
        // match Filter Course
        $patternSelect = '/<div class="md:col-span-9 w-full">\s*<label[^>]*>Filter Course<\/label>.*?<\/select>\s*<\/div>\s*<\/div>/is';
        if (preg_match($patternSelect, $content)) {
            $replace = '<div class="md:col-span-9 w-full">
                    <x-select name="course_id" label="Filter Course" icon="book" placeholder="Select a Course" onchange="this.form.submit()">
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @selected(request(\'course_id\') == $course->id)>{{ $course->title }}</option>
                        @endforeach
                    </x-select>
                </div>';
            $content = preg_replace($patternSelect, $replace, $content);
        }
    }

    // Admin Users
    if (strpos($file, 'users\index.blade.php') !== false) {
        // Replace role select
        $pattern1 = '/<div class="lg:col-span-3 w-full">\s*<label[^>]*>Filter Role<\/label>.*?<select name="role"[^>]*>.*?<\/select>\s*<\/div>\s*<\/div>/is';
        $replace1 = '<div class="lg:col-span-3 w-full">
                    <x-select name="role" label="Filter Role" icon="users" placeholder="All Roles" onchange="this.form.submit()">
                        <option value="admin" @selected(request(\'role\') === \'admin\')>Admin</option>
                        <option value="instructor" @selected(request(\'role\') === \'instructor\')>Instructors</option>
                        <option value="student" @selected(request(\'role\') === \'student\')>Students</option>
                    </x-select>
                </div>';
        $content = preg_replace($pattern1, $replace1, $content);

        // Replace status select
        $pattern2 = '/<div class="lg:col-span-3 w-full">\s*<label[^>]*>Filter Status<\/label>.*?<select name="status"[^>]*>.*?<\/select>\s*<\/div>\s*<\/div>/is';
        $replace2 = '<div class="lg:col-span-3 w-full">
                    <x-select name="status" label="Filter Status" icon="toggle-left" placeholder="All Statuses" onchange="this.form.submit()">
                        <option value="active" @selected(request(\'status\') === \'active\')>Active</option>
                        <option value="suspended" @selected(request(\'status\') === \'suspended\')>Suspended</option>
                    </x-select>
                </div>';
        $content = preg_replace($pattern2, $replace2, $content);

        // Replace semester select
        $pattern3 = '/<div class="lg:col-span-3 w-full">\s*<label[^>]*>Filter Semester<\/label>.*?<select name="semester"[^>]*>.*?<\/select>\s*<\/div>\s*<\/div>/is';
        $replace3 = '<div class="lg:col-span-3 w-full">
                    <x-select name="semester" label="Filter Semester" icon="school" placeholder="All Semesters" onchange="this.form.submit()">
                        @foreach (array_filter($users->pluck(\'semester\')->unique()->toArray()) as $s)
                            <option value="{{ $s }}" @selected(request(\'semester\') === $s)>{{ $s }}</option>
                        @endforeach
                    </x-select>
                </div>';
        $content = preg_replace($pattern3, $replace3, $content);
    }
    
    file_put_contents($file, $content);
}
echo "Done replacing components!";
