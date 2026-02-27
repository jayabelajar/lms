<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MaterialOverviewController extends Controller
{
    public function index(): View
    {
        $courses = \App\Models\Course::where('instructor_id', Auth::id())
            ->with('sections')
            ->get();

        $materials = CourseMaterial::with(['course', 'section'])
            ->whereHas('course', function ($q) {
                $q->where('instructor_id', Auth::id());
            })
            ->orderBy('course_id')
            ->orderBy('course_section_id')
            ->orderBy('sort_order')
            ->paginate(15);

        return view('instructor.materials.overview', compact('materials', 'courses'));
    }
}
