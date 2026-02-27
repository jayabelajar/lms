<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function index(Assignment $assignment): View
    {
        $this->authorize('view', $assignment->course);

        $submissions = $assignment->submissions()->with('student')->latest()->get();

        return view('instructor.assignments.submissions', compact('assignment', 'submissions'));
    }

    public function grade(Request $request, AssignmentSubmission $submission): RedirectResponse
    {
        $this->authorize('update', $submission->assignment->course);

        $data = $request->validate([
            'score' => ['required', 'integer', 'min:0'],
            'feedback' => ['nullable', 'string'],
        ]);

        $submission->update([
            'score' => $data['score'],
            'feedback' => $data['feedback'] ?? null,
            'graded_at' => now(),
        ]);

        return back()->with('status', 'Submission graded.');
    }
}
