<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\MaterialCompletion;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Auth::user()
            ->coursesEnrolled()
            ->with('instructor')
            ->latest('enrollments.created_at')
            ->paginate(10);

        return view('student.courses.index', compact('courses'));
    }

    public function show(Course $course): View
    {
        $this->ensureEnrolled($course);

        $course->load([
            'instructor',
            'sections.materials',
            'assignments',
            'quizzes' => function ($q) {
                $q->where('published', true);
            },
        ]);
        $completedIds = MaterialCompletion::where('student_id', Auth::id())
            ->whereIn('course_material_id', $course->materials()->select('id'))
            ->pluck('course_material_id')
            ->toArray();

        return view('student.courses.show', compact('course', 'completedIds'));
    }

    private function ensureEnrolled(Course $course): void
    {
        $isEnrolled = Auth::user()
            ->coursesEnrolled()
            ->where('courses.id', $course->id)
            ->exists();

        abort_unless($isEnrolled, 403);
    }
}
