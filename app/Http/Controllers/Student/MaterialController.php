<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseMaterial;
use App\Models\MaterialCompletion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function complete(CourseMaterial $material): RedirectResponse
    {
        $this->ensureEnrolled($material->course_id);

        MaterialCompletion::updateOrCreate(
            [
                'course_material_id' => $material->id,
                'student_id' => Auth::id(),
            ],
            [
                'completed_at' => now(),
            ]
        );

        return back()->with('status', 'Material marked as completed.');
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
