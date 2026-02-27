<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $course->sections()->create([
            'title' => $data['title'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return back()->with('status', 'Section created.');
    }

    public function update(Request $request, CourseSection $section): RedirectResponse
    {
        $this->authorize('update', $section->course);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $section->update($data);

        return back()->with('status', 'Section updated.');
    }

    public function destroy(CourseSection $section): RedirectResponse
    {
        $this->authorize('update', $section->course);

        $section->delete();

        return back()->with('status', 'Section deleted.');
    }
}
