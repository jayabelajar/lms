<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function quickStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_at' => ['nullable', 'date'],
            'max_score' => ['nullable', 'integer', 'min:1'],
        ]);

        $course = Course::findOrFail($data['course_id']);
        $this->authorize('update', $course);

        $course->assignments()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'due_at' => $data['due_at'] ?? null,
            'max_score' => $data['max_score'] ?? 100,
        ]);

        return redirect()->route('instructor.assignments.overview')->with('status', 'Assignment created.');
    }
    public function index(Course $course): View
    {
        $this->authorize('view', $course);

        $assignments = $course->assignments()->withCount('submissions')->get();

        return view('instructor.assignments.index', compact('course', 'assignments'));
    }

    public function create(Course $course): View
    {
        $this->authorize('update', $course);

        return view('instructor.assignments.create', compact('course'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_at' => ['nullable', 'date'],
            'max_score' => ['nullable', 'integer', 'min:1'],
        ]);

        $course->assignments()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'due_at' => $data['due_at'] ?? null,
            'max_score' => $data['max_score'] ?? 100,
        ]);

        return redirect()->route('instructor.assignments.index', $course)->with('status', 'Assignment created.');
    }

    public function edit(Assignment $assignment): View
    {
        $this->authorize('update', $assignment->course);

        return view('instructor.assignments.edit', compact('assignment'));
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $this->authorize('update', $assignment->course);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_at' => ['nullable', 'date'],
            'max_score' => ['nullable', 'integer', 'min:1'],
        ]);

        $assignment->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'due_at' => $data['due_at'] ?? null,
            'max_score' => $data['max_score'] ?? 100,
        ]);

        return redirect()->route('instructor.assignments.index', $assignment->course)->with('status', 'Assignment updated.');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        $this->authorize('update', $assignment->course);

        $course = $assignment->course;
        $assignment->delete();

        return redirect()->route('instructor.assignments.index', $course)->with('status', 'Assignment deleted.');
    }
}
