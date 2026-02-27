<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GradesController extends Controller
{
    public function index(): View
    {
        $submissions = AssignmentSubmission::with(['assignment.course', 'student'])
            ->whereHas('assignment.course', function ($q) {
                $q->where('instructor_id', Auth::id());
            })
            ->latest()
            ->paginate(15);

        return view('instructor.grades.index', compact('submissions'));
    }

    public function exportCsv(): Response
    {
        $rows = AssignmentSubmission::with(['assignment.course', 'student'])
            ->whereHas('assignment.course', function ($q) {
                $q->where('instructor_id', Auth::id());
            })
            ->latest()
            ->get();

        $header = ['Student', 'Course', 'Assignment', 'Score', 'Submitted At', 'Graded At'];

        $lines = [$header];
        foreach ($rows as $row) {
            $lines[] = [
                $row->student?->name ?? '',
                $row->assignment?->course?->title ?? '',
                $row->assignment?->title ?? '',
                $row->score ?? '',
                $row->submitted_at?->format('Y-m-d') ?? '',
                $row->graded_at?->format('Y-m-d') ?? '',
            ];
        }

        $output = '';
        foreach ($lines as $line) {
            $output .= '"' . implode('","', array_map('strval', $line)) . "\"\n";
        }

        return response($output, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="grades.csv"',
        ]);
    }
}
