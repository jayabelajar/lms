<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseSection;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;

class LmsContentSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::role('instructor')->first();
        $student = User::role('student')->first();

        if (! $instructor) {
            return;
        }

        $course = Course::firstOrCreate(
            ['slug' => 'intro-lms'],
            [
                'title' => 'Pengantar LMS',
                'description' => 'Materi dasar penggunaan LMS.',
                'instructor_id' => $instructor->id,
                'status' => 'published',
            ]
        );

        if ($student) {
            $course->students()->syncWithoutDetaching([
                $student->id => ['status' => 'approved'],
            ]);
        }

        $section1 = CourseSection::firstOrCreate(
            ['course_id' => $course->id, 'title' => 'Minggu 1'],
            ['sort_order' => 1]
        );
        $section2 = CourseSection::firstOrCreate(
            ['course_id' => $course->id, 'title' => 'Minggu 2'],
            ['sort_order' => 2]
        );

        CourseMaterial::firstOrCreate(
            ['course_id' => $course->id, 'course_section_id' => $section1->id, 'title' => 'Pengenalan'],
            [
                'type' => 'text',
                'content' => 'Ringkasan singkat tentang LMS.',
                'sort_order' => 1,
            ]
        );
        CourseMaterial::firstOrCreate(
            ['course_id' => $course->id, 'course_section_id' => $section1->id, 'title' => 'Video Orientasi'],
            [
                'type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'sort_order' => 2,
            ]
        );
        CourseMaterial::firstOrCreate(
            ['course_id' => $course->id, 'course_section_id' => $section2->id, 'title' => 'Materi Lanjutan'],
            [
                'type' => 'text',
                'content' => 'Materi lanjutan penggunaan fitur LMS.',
                'sort_order' => 1,
            ]
        );

        Assignment::firstOrCreate(
            ['course_id' => $course->id, 'title' => 'Tugas 1'],
            [
                'description' => 'Buat rangkuman materi minggu 1.',
                'due_at' => now()->addDays(7),
                'max_score' => 100,
            ]
        );

        $quiz = Quiz::firstOrCreate(
            ['course_id' => $course->id, 'title' => 'Kuis 1'],
            [
                'description' => 'Kuis pengantar LMS.',
                'published' => true,
                'total_points' => 0,
            ]
        );

        $q1 = QuizQuestion::firstOrCreate(
            ['quiz_id' => $quiz->id, 'question' => 'Apa fungsi utama LMS?'],
            [
                'type' => 'mcq',
                'points' => 5,
                'sort_order' => 1,
            ]
        );
        QuizOption::firstOrCreate(
            ['quiz_question_id' => $q1->id, 'option_text' => 'Mengelola pembelajaran'],
            ['is_correct' => true]
        );
        QuizOption::firstOrCreate(
            ['quiz_question_id' => $q1->id, 'option_text' => 'Mengirim email massal'],
            ['is_correct' => false]
        );

        QuizQuestion::firstOrCreate(
            ['quiz_id' => $quiz->id, 'question' => 'Jelaskan manfaat LMS untuk mahasiswa.'],
            [
                'type' => 'essay',
                'points' => 10,
                'sort_order' => 2,
            ]
        );
    }
}
