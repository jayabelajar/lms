<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(): View
    {
        $enrollments = Enrollment::with(['course', 'user'])
            ->latest()
            ->paginate(12);

        return view('admin.enrollments.index', compact('enrollments'));
    }

    public function update(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        $enrollment->update([
            'status' => $request->string('status'),
        ]);

        return redirect()->route('admin.enrollments.index')->with('status', 'Enrollment updated.');
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $enrollment->delete();

        return redirect()->route('admin.enrollments.index')->with('status', 'Enrollment removed.');
    }
}
