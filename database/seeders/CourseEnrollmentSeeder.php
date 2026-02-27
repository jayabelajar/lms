<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseEnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::firstOrCreate(
            ['email' => 'instructor2@lms.test'],
            [
                'name' => 'Instructor Two',
                'password' => 'password',
            ]
        );
        $instructor->assignRole('instructor');

        $student1 = User::firstOrCreate(
            ['email' => 'student1@lms.test'],
            [
                'name' => 'Student One',
                'password' => 'password',
            ]
        );
        $student1->assignRole('student');

        $student2 = User::firstOrCreate(
            ['email' => 'student2@lms.test'],
            [
                'name' => 'Student Two',
                'password' => 'password',
            ]
        );
        $student2->assignRole('student');

        $courseA = Course::firstOrCreate(
            ['slug' => 'laravel-basics'],
            [
                'title' => 'Laravel Basics',
                'description' => 'Learn Laravel fundamentals.',
                'instructor_id' => $instructor->id,
                'status' => 'published',
            ]
        );

        $courseB = Course::firstOrCreate(
            ['slug' => 'php-for-web'],
            [
                'title' => 'PHP for Web',
                'description' => 'Build web apps with PHP.',
                'instructor_id' => $instructor->id,
                'status' => 'published',
            ]
        );

        Enrollment::firstOrCreate(
            [
                'course_id' => $courseA->id,
                'user_id' => $student1->id,
            ],
            [
                'status' => 'approved',
            ]
        );
    }
}
