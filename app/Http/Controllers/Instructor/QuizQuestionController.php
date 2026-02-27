<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuizQuestionController extends Controller
{
    public function store(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $quiz->course);

        $data = $request->validate([
            'type' => ['required', 'in:mcq,essay'],
            'question' => ['required', 'string'],
            'points' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $quiz->questions()->create([
            'type' => $data['type'],
            'question' => $data['question'],
            'points' => $data['points'] ?? 1,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return back()->with('status', 'Question added.');
    }

    public function update(Request $request, QuizQuestion $question): RedirectResponse
    {
        $this->authorize('update', $question->quiz->course);

        $data = $request->validate([
            'question' => ['required', 'string'],
            'points' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $question->update($data);

        return back()->with('status', 'Question updated.');
    }

    public function destroy(QuizQuestion $question): RedirectResponse
    {
        $this->authorize('update', $question->quiz->course);

        $question->delete();

        return back()->with('status', 'Question deleted.');
    }
}
