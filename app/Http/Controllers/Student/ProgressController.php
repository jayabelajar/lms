<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\MaterialCompletion;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProgressController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $courseIds = $user->coursesEnrolled()->pluck('courses.id');

        $courses = Course::whereIn('id', $courseIds)->withCount('materials')->get();
        $completedByCourse = MaterialCompletion::where('student_id', $user->id)
            ->join('course_materials', 'material_completions.course_material_id', '=', 'course_materials.id')
            ->select('course_materials.course_id', DB::raw('count(*) as cnt'))
            ->groupBy('course_materials.course_id')
            ->pluck('cnt', 'course_materials.course_id');

        $grades = AssignmentSubmission::where('student_id', $user->id)
            ->with('assignment.course')
            ->latest()
            ->get();

        $quizScores = QuizAttempt::where('student_id', $user->id)
            ->with('quiz.course')
            ->latest()
            ->get();

        $progressRows = [];
        foreach ($courses as $course) {
            $total = $course->materials_count;
            $done = (int) ($completedByCourse[$course->id] ?? 0);
            $percent = $total > 0 ? (int) round(($done / $total) * 100) : 0;
            $status = $percent >= 80 ? 'Lulus' : 'Belum';
            $progressRows[] = [
                'course' => $course,
                'percent' => $percent,
                'status' => $status,
            ];
        }

        return view('student.progress.index', compact('progressRows', 'grades', 'quizScores'));
    }
}
