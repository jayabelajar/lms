<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AssignmentOverviewController extends Controller
{
    public function index(): View
    {
        $courses = \App\Models\Course::where('instructor_id', Auth::id())->get();

        $assignments = Assignment::with(['course'])
            ->withCount('submissions')
            ->whereHas('course', function ($q) {
                $q->where('instructor_id', Auth::id());
            })
            ->latest()
            ->paginate(12);

        return view('instructor.assignments.overview', compact('assignments', 'courses'));
    }
}
