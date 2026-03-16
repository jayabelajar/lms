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

        $query = CourseMaterial::with(['course', 'section'])
            ->whereHas('course', function ($q) {
                $q->where('instructor_id', Auth::id());
            });

        if (request('q')) {
            $query->where('title', 'like', '%' . request('q') . '%');
        }

        if (request('course')) {
            $query->where('course_id', request('course'));
        }

        $materials = $query->orderBy('course_id')
            ->orderBy('course_section_id')
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();

        return view('instructor.materials.overview', compact('materials', 'courses'));
    }
}
