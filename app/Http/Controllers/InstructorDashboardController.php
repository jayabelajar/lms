<?php

namespace App\Http\Controllers;

use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InstructorDashboardController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        $courses = Course::where('instructor_id', $userId)->withCount('students')->get();
        $totalCourses = $courses->count();
        $totalStudents = $courses->sum('students_count');

        $pendingGrading = AssignmentSubmission::whereHas('assignment.course', function ($q) use ($userId) {
            $q->where('instructor_id', $userId);
        })->whereNull('graded_at')->count();

        return view('instructor.dashboard', compact('courses', 'totalCourses', 'totalStudents', 'pendingGrading'));
    }
}
