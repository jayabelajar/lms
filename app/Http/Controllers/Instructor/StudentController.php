<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        $courses = Course::where('instructor_id', Auth::id())->get();
        $courseId = request('course_id') ?: $courses->first()?->id;

        $selectedCourse = null;
        $students = collect();
        $progressByStudent = [];

        if ($courseId) {
            $selectedCourse = Course::where('instructor_id', Auth::id())->findOrFail($courseId);

            $students = $selectedCourse->students()->get();

            $assignmentIds = Assignment::where('course_id', $selectedCourse->id)->pluck('id');
            $assignmentsCount = $assignmentIds->count();

            $submissionCounts = AssignmentSubmission::whereIn('assignment_id', $assignmentIds)
                ->selectRaw('student_id, COUNT(*) as cnt')
                ->groupBy('student_id')
                ->pluck('cnt', 'student_id');

            foreach ($students as $student) {
                $submitted = (int) ($submissionCounts[$student->id] ?? 0);
                $progressByStudent[$student->id] = $assignmentsCount > 0
                    ? (int) round(($submitted / $assignmentsCount) * 100)
                    : 0;
            }
        }

        return view('instructor.students.index', compact('courses', 'selectedCourse', 'students', 'progressByStudent'));
    }

    public function exportCsv(): Response
    {
        $courseId = request('course_id');
        $course = Course::where('instructor_id', Auth::id())->findOrFail($courseId);

        $students = $course->students()->get();
        $assignmentIds = Assignment::where('course_id', $course->id)->pluck('id');
        $assignmentsCount = $assignmentIds->count();

        $submissionCounts = AssignmentSubmission::whereIn('assignment_id', $assignmentIds)
            ->selectRaw('student_id, COUNT(*) as cnt')
            ->groupBy('student_id')
            ->pluck('cnt', 'student_id');

        $avgScores = AssignmentSubmission::whereIn('assignment_id', $assignmentIds)
            ->selectRaw('student_id, AVG(score) as avg_score')
            ->groupBy('student_id')
            ->pluck('avg_score', 'student_id');

        $lines = [['Student', 'Email', 'Submitted', 'Progress(%)', 'Avg Score']];
        foreach ($students as $student) {
            $submitted = (int) ($submissionCounts[$student->id] ?? 0);
            $progress = $assignmentsCount > 0 ? (int) round(($submitted / $assignmentsCount) * 100) : 0;
            $avg = $avgScores[$student->id] ?? null;
            $lines[] = [
                $student->name,
                $student->email,
                $submitted,
                $progress,
                $avg !== null ? round((float) $avg, 2) : '',
            ];
        }

        $output = '';
        foreach ($lines as $line) {
            $output .= '"' . implode('","', array_map('strval', $line)) . "\"\n";
        }

        return response($output, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="students-'.$course->id.'.csv"',
        ]);
    }
}
