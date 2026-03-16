<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseStoreRequest;
use App\Http\Requests\Admin\CourseUpdateRequest;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Course::class);

        $query = Course::with('instructor')->latest();

        if ($request->q) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $courses = $query->paginate(10)->withQueryString();

        $instructors = \App\Models\User::role('instructor')->get();

        return view('admin.courses.index', compact('courses', 'instructors'));
    }

    public function create(): View
    {
        $this->authorize('create', Course::class);

        $instructors = User::role('instructor')->orderBy('name')->get();

        return view('admin.courses.create', compact('instructors'));
    }

    public function store(CourseStoreRequest $request): RedirectResponse
    {
        $course = Course::create($request->validated());

        return redirect()->route('admin.courses.show', $course)->with('status', 'Course created.');
    }

    public function show(Course $course): View
    {
        $this->authorize('view', $course);

        $course->load(['instructor', 'students']);
        $availableStudents = User::role('student')
            ->whereNotIn('id', $course->students->pluck('id'))
            ->orderBy('name')
            ->get();

        return view('admin.courses.show', compact('course', 'availableStudents'));
    }

    public function addStudent(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $data = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
        ]);

        $student = User::findOrFail($data['student_id']);
        if (! $student->hasRole('student')) {
            return back()->with('status', 'User bukan mahasiswa.');
        }

        $course->students()->syncWithoutDetaching([
            $student->id => ['status' => 'approved'],
        ]);

        return back()->with('status', 'Mahasiswa berhasil ditambahkan.');
    }

    public function removeStudent(Course $course, User $student): RedirectResponse
    {
        $this->authorize('update', $course);

        $course->students()->detach($student->id);

        return back()->with('status', 'Mahasiswa berhasil dihapus dari mata kuliah.');
    }

    public function edit(Course $course): View
    {
        $this->authorize('update', $course);

        $instructors = User::role('instructor')->orderBy('name')->get();

        return view('admin.courses.edit', compact('course', 'instructors'));
    }

    public function update(CourseUpdateRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()->route('admin.courses.show', $course)->with('status', 'Course updated.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $this->authorize('delete', $course);

        $course->delete();

        return redirect()->route('admin.courses.index')->with('status', 'Course deleted.');
    }
}
