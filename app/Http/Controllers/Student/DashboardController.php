<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\MaterialCompletion;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $courseIds = $user->coursesEnrolled()->pluck('courses.id');

        $totalCourses = $courseIds->count();

        $totalMaterials = CourseMaterial::whereIn('course_id', $courseIds)->count();
        $completedMaterials = MaterialCompletion::where('student_id', $user->id)
            ->whereIn('course_material_id', CourseMaterial::whereIn('course_id', $courseIds)->select('id'))
            ->count();

        $progressPercent = $totalMaterials > 0 ? (int) round(($completedMaterials / $totalMaterials) * 100) : 0;

        $assignmentDue = Assignment::whereIn('course_id', $courseIds)
            ->whereDate('due_at', '>=', now()->toDateString())
            ->whereDoesntHave('submissions', function ($q) use ($user) {
                $q->where('student_id', $user->id);
            })
            ->count();

        $quizPending = Quiz::whereIn('course_id', $courseIds)
            ->where('published', true)
            ->whereDoesntHave('attempts', function ($q) use ($user) {
                $q->where('student_id', $user->id);
            })
            ->count();

        $courses = Course::whereIn('id', $courseIds)->withCount('materials')->get();
        $completedByCourse = MaterialCompletion::where('student_id', $user->id)
            ->join('course_materials', 'material_completions.course_material_id', '=', 'course_materials.id')
            ->select('course_materials.course_id', DB::raw('count(*) as cnt'))
            ->groupBy('course_materials.course_id')
            ->pluck('cnt', 'course_materials.course_id');

        return view('student.dashboard', compact(
            'totalCourses',
            'progressPercent',
            'assignmentDue',
            'quizPending',
            'courses',
            'completedByCourse'
        ));
    }
}
