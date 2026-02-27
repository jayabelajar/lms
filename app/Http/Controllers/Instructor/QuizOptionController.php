<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuizOptionController extends Controller
{
    public function store(Request $request, QuizQuestion $question): RedirectResponse
    {
        $this->authorize('update', $question->quiz->course);

        $data = $request->validate([
            'option_text' => ['required', 'string'],
            'is_correct' => ['nullable', 'boolean'],
        ]);

        if (($data['is_correct'] ?? false) && $question->options()->where('is_correct', true)->exists()) {
            $question->options()->where('is_correct', true)->update(['is_correct' => false]);
        }

        $question->options()->create([
            'option_text' => $data['option_text'],
            'is_correct' => (bool) ($data['is_correct'] ?? false),
        ]);

        return back()->with('status', 'Option added.');
    }

    public function update(Request $request, QuizOption $option): RedirectResponse
    {
        $this->authorize('update', $option->question->quiz->course);

        $data = $request->validate([
            'option_text' => ['required', 'string'],
            'is_correct' => ['nullable', 'boolean'],
        ]);

        if (($data['is_correct'] ?? false)) {
            $option->question->options()->where('is_correct', true)->update(['is_correct' => false]);
        }

        $option->update([
            'option_text' => $data['option_text'],
            'is_correct' => (bool) ($data['is_correct'] ?? false),
        ]);

        return back()->with('status', 'Option updated.');
    }

    public function destroy(QuizOption $option): RedirectResponse
    {
        $this->authorize('update', $option->question->quiz->course);

        $option->delete();

        return back()->with('status', 'Option deleted.');
    }
}
