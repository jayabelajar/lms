<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function quickStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'course_section_id' => ['nullable', 'exists:course_sections,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:text,file,video'],
            'content' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'max:10240'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $course = Course::findOrFail($data['course_id']);
        $this->authorize('update', $course);

        $section = $this->resolveSection($course, $data['course_section_id'] ?? null);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials', 'public');
        }

        CourseMaterial::create([
            'course_id' => $course->id,
            'course_section_id' => $section->id,
            'title' => $data['title'],
            'type' => $data['type'],
            'content' => $data['type'] === 'text' ? $data['content'] : null,
            'video_url' => $data['type'] === 'video' ? $data['video_url'] : null,
            'file_path' => $data['type'] === 'file' ? $filePath : null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('instructor.materials.overview')->with('status', 'Material added.');
    }
    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $data = $request->validate([
            'course_section_id' => ['nullable', 'exists:course_sections,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:text,file,video'],
            'content' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'max:10240'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $section = $this->resolveSection($course, $data['course_section_id'] ?? null);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials', 'public');
        }

        CourseMaterial::create([
            'course_id' => $course->id,
            'course_section_id' => $section->id,
            'title' => $data['title'],
            'type' => $data['type'],
            'content' => $data['type'] === 'text' ? $data['content'] : null,
            'video_url' => $data['type'] === 'video' ? $data['video_url'] : null,
            'file_path' => $data['type'] === 'file' ? $filePath : null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return back()->with('status', 'Material added.');
    }

    public function update(Request $request, CourseMaterial $material): RedirectResponse
    {
        $this->authorize('update', $material->course);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'max:10240'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $filePath = $material->file_path;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials', 'public');
        }

        $material->update([
            'title' => $data['title'],
            'content' => $material->type === 'text' ? ($data['content'] ?? null) : null,
            'video_url' => $material->type === 'video' ? ($data['video_url'] ?? null) : null,
            'file_path' => $material->type === 'file' ? $filePath : null,
            'sort_order' => $data['sort_order'] ?? $material->sort_order,
        ]);

        return back()->with('status', 'Material updated.');
    }

    public function destroy(CourseMaterial $material): RedirectResponse
    {
        $this->authorize('update', $material->course);

        $material->delete();

        return back()->with('status', 'Material deleted.');
    }

    private function resolveSection(Course $course, ?int $sectionId): CourseSection
    {
        if ($sectionId) {
            $section = CourseSection::findOrFail($sectionId);
            $this->authorize('update', $section->course);
            abort_unless($section->course_id === $course->id, 422);
            return $section;
        }

        return CourseSection::firstOrCreate(
            ['course_id' => $course->id, 'title' => 'Umum'],
            ['sort_order' => 0]
        );
    }
}
