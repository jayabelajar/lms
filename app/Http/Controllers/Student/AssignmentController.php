<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(): View
    {
        $courseIds = Auth::user()->coursesEnrolled()->pluck('courses.id');

        $assignments = Assignment::whereIn('course_id', $courseIds)
            ->with('course')
            ->latest()
            ->paginate(12);

        $assignmentIds = $assignments->pluck('id')->all();
        $submissions = AssignmentSubmission::where('student_id', Auth::id())
            ->whereIn('assignment_id', $assignmentIds)
            ->get()
            ->keyBy('assignment_id');

        $submitted = $submissions->keys()->all();

        return view('student.assignments.index', compact('assignments', 'submitted', 'submissions'));
    }

    public function show(Assignment $assignment): View
    {
        $this->ensureEnrolled($assignment->course_id);

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', Auth::id())
            ->first();

        return view('student.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment): RedirectResponse
    {
        $this->ensureEnrolled($assignment->course_id);

        if ($assignment->due_at && now()->gt($assignment->due_at->endOfDay())) {
            return back()->with('status', 'Deadline has passed.');
        }

        $data = $request->validate([
            'content' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:10240'],
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => Auth::id(),
            ],
            [
                'content' => $data['content'] ?? null,
                'file_path' => $filePath,
                'submitted_at' => now(),
            ]
        );

        return back()->with('status', 'Assignment submitted.');
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $this->ensureEnrolled($assignment->course_id);

        if ($assignment->due_at && now()->gt($assignment->due_at->endOfDay())) {
            return back()->with('status', 'Deadline has passed.');
        }

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', Auth::id())
            ->firstOrFail();

        $data = $request->validate([
            'content' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:10240'],
        ]);

        $filePath = $submission->file_path;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        $submission->update([
            'content' => $data['content'] ?? null,
            'file_path' => $filePath,
            'submitted_at' => now(),
        ]);

        return back()->with('status', 'Submission updated.');
    }

    private function ensureEnrolled(int $courseId): void
    {
        $isEnrolled = Auth::user()
            ->coursesEnrolled()
            ->where('courses.id', $courseId)
            ->exists();

        abort_unless($isEnrolled, 403);
    }
}
