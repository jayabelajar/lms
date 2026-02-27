<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MyCoursesController extends Controller
{
    public function index(): View
    {
        $courses = Auth::user()
            ->coursesEnrolled()
            ->with('instructor')
            ->latest('enrollments.created_at')
            ->paginate(10);

        return view('student.my-courses', compact('courses'));
    }
}
