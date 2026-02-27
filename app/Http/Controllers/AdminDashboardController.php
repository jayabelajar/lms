<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalCourses = Course::count();
        $totalInstructors = User::role('instructor')->count();
        $totalStudents = User::role('student')->count();
        $totalEnrollments = Enrollment::count();

        $publishedCourses = Course::where('status', 'published')->count();
        $draftCourses = Course::where('status', 'draft')->count();

        $recentCourses = Course::with('instructor')->latest()->take(5)->get();
        $recentEnrollments = Enrollment::with(['course', 'user'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalCourses',
            'totalInstructors',
            'totalStudents',
            'totalEnrollments',
            'publishedCourses',
            'draftCourses',
            'recentCourses',
            'recentEnrollments'
        ));
    }
}
