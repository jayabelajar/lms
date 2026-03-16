<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\CourseStoreRequest;
use App\Http\Requests\Instructor\CourseUpdateRequest;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Course::class);

        $courses = Course::where('instructor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('instructor.courses.index', compact('courses'));
    }

    public function show(Course $course): View
    {
        $this->authorize('view', $course);

        $course->load(['students', 'sections.materials', 'assignments']);

        return view('instructor.courses.show', compact('course'));
    }
}
