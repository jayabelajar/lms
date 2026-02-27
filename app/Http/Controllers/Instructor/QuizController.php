<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index(): View
    {
        $quizzes = Quiz::with('course')
            ->whereHas('course', function ($q) {
                $q->where('instructor_id', Auth::id());
            })
            ->latest()
            ->paginate(12);

        return view('instructor.quizzes.index', compact('quizzes'));
    }

    public function create(): View
    {
        $courses = Course::where('instructor_id', Auth::id())->get();

        return view('instructor.quizzes.create', compact('courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'published' => ['nullable', 'boolean'],
        ]);

        $course = Course::findOrFail($data['course_id']);
        $this->authorize('update', $course);

        $quiz = Quiz::create([
            'course_id' => $course->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'published' => (bool) ($data['published'] ?? false),
        ]);

        return redirect()->route('instructor.quizzes.edit', $quiz)->with('status', 'Quiz created.');
    }

    public function edit(Quiz $quiz): View
    {
        $this->authorize('update', $quiz->course);
        $quiz->load(['course', 'questions.options']);

        return view('instructor.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $quiz->course);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'published' => ['nullable', 'boolean'],
        ]);

        $quiz->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'published' => (bool) ($data['published'] ?? false),
        ]);

        return back()->with('status', 'Quiz updated.');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $quiz->course);

        $quiz->delete();

        return redirect()->route('instructor.quizzes.index')->with('status', 'Quiz deleted.');
    }
}
