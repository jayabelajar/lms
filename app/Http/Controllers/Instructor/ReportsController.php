<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportsController extends Controller
{
    public function index(): View
    {
        $courses = Course::where('instructor_id', Auth::id())->count();
        $assignments = Assignment::whereHas('course', function ($q) {
            $q->where('instructor_id', Auth::id());
        })->count();

        $submissions = AssignmentSubmission::whereHas('assignment.course', function ($q) {
            $q->where('instructor_id', Auth::id());
        })->count();

        $graded = AssignmentSubmission::whereHas('assignment.course', function ($q) {
            $q->where('instructor_id', Auth::id());
        })->whereNotNull('graded_at')->count();

        return view('instructor.reports.index', compact('courses', 'assignments', 'submissions', 'graded'));
    }

    public function exportCsv(): Response
    {
        $rows = Course::where('instructor_id', Auth::id())
            ->withCount('students')
            ->get();

        $header = ['Course', 'Students'];
        $lines = [$header];
        foreach ($rows as $row) {
            $lines[] = [$row->title, $row->students_count];
        }

        $output = '';
        foreach ($lines as $line) {
            $output .= '"' . implode('","', array_map('strval', $line)) . "\"\n";
        }

        return response($output, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="report-courses.csv"',
        ]);
    }
}
