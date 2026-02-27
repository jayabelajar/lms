<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizAttemptController extends Controller
{
    public function index(Quiz $quiz): View
    {
        $this->authorize('view', $quiz->course);

        $attempts = $quiz->attempts()->with('student')->latest()->get();

        return view('instructor.quizzes.attempts', compact('quiz', 'attempts'));
    }

    public function grade(Request $request, QuizAnswer $answer): RedirectResponse
    {
        $this->authorize('update', $answer->question->quiz->course);

        $data = $request->validate([
            'score' => ['required', 'integer', 'min:0'],
            'is_correct' => ['nullable', 'boolean'],
        ]);

        $answer->update([
            'score' => $data['score'],
            'is_correct' => $data['is_correct'] ?? null,
        ]);

        $attempt = $answer->attempt;
        $attempt->score = (int) $attempt->answers()->sum('score');
        $attempt->graded_at = now();
        $attempt->save();

        return back()->with('status', 'Answer graded.');
    }
}
