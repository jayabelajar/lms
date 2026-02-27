<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index(): View
    {
        $courseIds = Auth::user()->coursesEnrolled()->pluck('courses.id');

        $quizzes = Quiz::whereIn('course_id', $courseIds)
            ->where('published', true)
            ->with('course')
            ->latest()
            ->paginate(12);

        $attempted = QuizAttempt::where('student_id', Auth::id())
            ->pluck('quiz_id')
            ->toArray();

        return view('student.quizzes.index', compact('quizzes', 'attempted'));
    }

    public function show(Quiz $quiz): View
    {
        $this->ensureEnrolled($quiz->course_id);
        abort_unless($quiz->published, 403);

        $quiz->load(['questions.options']);
        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', Auth::id())
            ->first();

        return view('student.quizzes.show', compact('quiz', 'attempt'));
    }

    public function submit(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->ensureEnrolled($quiz->course_id);
        abort_unless($quiz->published, 403);

        $quiz->load(['questions.options']);

        $attempt = QuizAttempt::updateOrCreate(
            [
                'quiz_id' => $quiz->id,
                'student_id' => Auth::id(),
            ],
            [
                'submitted_at' => now(),
            ]
        );

        $totalScore = 0;

        foreach ($quiz->questions as $question) {
            $answerText = $request->input("essay.{$question->id}");
            $selectedOptionId = $request->input("mcq.{$question->id}");

            $isCorrect = null;
            $score = null;

            if ($question->type === 'mcq') {
                $correctOption = $question->options()->where('is_correct', true)->first();
                $isCorrect = $correctOption && (int) $selectedOptionId === $correctOption->id;
                $score = $isCorrect ? $question->points : 0;
                $totalScore += $score;
            }

            QuizAnswer::updateOrCreate(
                [
                    'quiz_attempt_id' => $attempt->id,
                    'quiz_question_id' => $question->id,
                ],
                [
                    'selected_option_id' => $question->type === 'mcq' ? $selectedOptionId : null,
                    'answer_text' => $question->type === 'essay' ? $answerText : null,
                    'is_correct' => $isCorrect,
                    'score' => $score,
                ]
            );
        }

        $attempt->score = $totalScore;
        $attempt->graded_at = now(); // MCQ auto grade, essay still needs manual grading (score null)
        $attempt->save();

        return redirect()->route('student.quizzes.review', $quiz)->with('status', 'Quiz submitted.');
    }

    public function review(Quiz $quiz): View
    {
        $this->ensureEnrolled($quiz->course_id);
        abort_unless($quiz->published, 403);

        $quiz->load(['questions.options']);
        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', Auth::id())
            ->with('answers')
            ->firstOrFail();

        return view('student.quizzes.review', compact('quiz', 'attempt'));
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
