<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\InstructorDashboardController;
use App\Http\Controllers\ProfileController;
// removed legacy StudentDashboardController
use App\Http\Controllers\Student\MyCoursesController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardControllerV2;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\MaterialController as StudentMaterialController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Student\ProgressController as StudentProgressController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\EnrollmentController as AdminEnrollmentController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\SectionController as InstructorSectionController;
use App\Http\Controllers\Instructor\MaterialController as InstructorMaterialController;
use App\Http\Controllers\Instructor\MaterialOverviewController as InstructorMaterialOverviewController;
use App\Http\Controllers\Instructor\AssignmentController as InstructorAssignmentController;
use App\Http\Controllers\Instructor\SubmissionController as InstructorSubmissionController;
use App\Http\Controllers\Instructor\AssignmentOverviewController as InstructorAssignmentOverviewController;
use App\Http\Controllers\Instructor\GradesController as InstructorGradesController;
use App\Http\Controllers\Instructor\ReportsController as InstructorReportsController;
use App\Http\Controllers\Instructor\StudentController as InstructorStudentController;
use App\Http\Controllers\Instructor\QuizController as InstructorQuizController;
use App\Http\Controllers\Instructor\QuizQuestionController as InstructorQuizQuestionController;
use App\Http\Controllers\Instructor\QuizOptionController as InstructorQuizOptionController;
use App\Http\Controllers\Instructor\QuizAttemptController as InstructorQuizAttemptController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role.redirect'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/instructor/dashboard', [InstructorDashboardController::class, 'index'])
        ->middleware('role:instructor')
        ->name('instructor.dashboard');

    Route::get('/student/dashboard', [StudentDashboardControllerV2::class, 'index'])
        ->middleware('role:student')
        ->name('student.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::resource('courses', AdminCourseController::class);
        Route::post('courses/{course}/students', [AdminCourseController::class, 'addStudent'])->name('courses.students.add');
        Route::delete('courses/{course}/students/{student}', [AdminCourseController::class, 'removeStudent'])->name('courses.students.remove');
        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::patch('users/{user}/toggle-suspend', [AdminUserController::class, 'toggleSuspend'])->name('users.toggle-suspend');

        Route::get('enrollments', [AdminEnrollmentController::class, 'index'])->name('enrollments.index');
        Route::patch('enrollments/{enrollment}', [AdminEnrollmentController::class, 'update'])->name('enrollments.update');
        Route::delete('enrollments/{enrollment}', [AdminEnrollmentController::class, 'destroy'])->name('enrollments.destroy');

        Route::get('settings', [AdminSettingsController::class, 'edit'])->name('settings.edit');
        Route::post('settings', [AdminSettingsController::class, 'update'])->name('settings.update');
    });

    Route::prefix('instructor')->name('instructor.')->middleware('role:instructor')->group(function () {
        Route::resource('courses', InstructorCourseController::class)->only(['index', 'show', 'edit', 'update']);

        Route::get('materials', [InstructorMaterialOverviewController::class, 'index'])->name('materials.overview');
        Route::get('assignments', [InstructorAssignmentOverviewController::class, 'index'])->name('assignments.overview');
        Route::post('assignments/quick-store', [InstructorAssignmentController::class, 'quickStore'])->name('assignments.quick-store');

        Route::post('courses/{course}/sections', [InstructorSectionController::class, 'store'])->name('sections.store');
        Route::put('sections/{section}', [InstructorSectionController::class, 'update'])->name('sections.update');
        Route::delete('sections/{section}', [InstructorSectionController::class, 'destroy'])->name('sections.destroy');

        Route::post('courses/{course}/materials', [InstructorMaterialController::class, 'store'])->name('materials.store');
        Route::post('materials/quick-store', [InstructorMaterialController::class, 'quickStore'])->name('materials.quick-store');
        Route::put('materials/{material}', [InstructorMaterialController::class, 'update'])->name('materials.update');
        Route::delete('materials/{material}', [InstructorMaterialController::class, 'destroy'])->name('materials.destroy');

        Route::get('courses/{course}/assignments', [InstructorAssignmentController::class, 'index'])->name('assignments.index');
        Route::get('courses/{course}/assignments/create', [InstructorAssignmentController::class, 'create'])->name('assignments.create');
        Route::post('courses/{course}/assignments', [InstructorAssignmentController::class, 'store'])->name('assignments.store');
        Route::get('assignments/{assignment}/edit', [InstructorAssignmentController::class, 'edit'])->name('assignments.edit');
        Route::put('assignments/{assignment}', [InstructorAssignmentController::class, 'update'])->name('assignments.update');
        Route::delete('assignments/{assignment}', [InstructorAssignmentController::class, 'destroy'])->name('assignments.destroy');

        Route::get('assignments/{assignment}/submissions', [InstructorSubmissionController::class, 'index'])->name('assignments.submissions');
        Route::patch('submissions/{submission}/grade', [InstructorSubmissionController::class, 'grade'])->name('submissions.grade');

        Route::get('grades', [InstructorGradesController::class, 'index'])->name('grades.index');
        Route::get('grades/export/csv', [InstructorGradesController::class, 'exportCsv'])->name('grades.export.csv');

        Route::get('reports', [InstructorReportsController::class, 'index'])->name('reports.index');
        Route::get('reports/export/csv', [InstructorReportsController::class, 'exportCsv'])->name('reports.export.csv');

        Route::get('students', [InstructorStudentController::class, 'index'])->name('students.index');
        Route::get('students/export/csv', [InstructorStudentController::class, 'exportCsv'])->name('students.export.csv');

        Route::get('quizzes', [InstructorQuizController::class, 'index'])->name('quizzes.index');
        Route::get('quizzes/create', [InstructorQuizController::class, 'create'])->name('quizzes.create');
        Route::post('quizzes', [InstructorQuizController::class, 'store'])->name('quizzes.store');
        Route::get('quizzes/{quiz}/edit', [InstructorQuizController::class, 'edit'])->name('quizzes.edit');
        Route::put('quizzes/{quiz}', [InstructorQuizController::class, 'update'])->name('quizzes.update');
        Route::delete('quizzes/{quiz}', [InstructorQuizController::class, 'destroy'])->name('quizzes.destroy');

        Route::post('quizzes/{quiz}/questions', [InstructorQuizQuestionController::class, 'store'])->name('quizzes.questions.store');
        Route::put('questions/{question}', [InstructorQuizQuestionController::class, 'update'])->name('questions.update');
        Route::delete('questions/{question}', [InstructorQuizQuestionController::class, 'destroy'])->name('questions.destroy');

        Route::post('questions/{question}/options', [InstructorQuizOptionController::class, 'store'])->name('questions.options.store');
        Route::put('options/{option}', [InstructorQuizOptionController::class, 'update'])->name('options.update');
        Route::delete('options/{option}', [InstructorQuizOptionController::class, 'destroy'])->name('options.destroy');

        Route::get('quizzes/{quiz}/attempts', [InstructorQuizAttemptController::class, 'index'])->name('quizzes.attempts');
        Route::patch('answers/{answer}/grade', [InstructorQuizAttemptController::class, 'grade'])->name('quiz-answers.grade');
    });

    Route::prefix('student')->name('student.')->middleware('role:student')->group(function () {
        Route::get('dashboard', [StudentDashboardControllerV2::class, 'index'])->name('dashboard');
        Route::get('my-courses', [StudentCourseController::class, 'index'])->name('my-courses');
        Route::get('my-courses/{course}', [StudentCourseController::class, 'show'])->name('my-courses.show');

        Route::post('materials/{material}/complete', [StudentMaterialController::class, 'complete'])->name('materials.complete');

        Route::get('assignments', [StudentAssignmentController::class, 'index'])->name('assignments.index');
        Route::get('assignments/{assignment}', [StudentAssignmentController::class, 'show'])->name('assignments.show');
        Route::post('assignments/{assignment}/submit', [StudentAssignmentController::class, 'submit'])->name('assignments.submit');
        Route::put('assignments/{assignment}/update', [StudentAssignmentController::class, 'update'])->name('assignments.update');

        Route::get('quizzes', [StudentQuizController::class, 'index'])->name('quizzes.index');
        Route::get('quizzes/{quiz}', [StudentQuizController::class, 'show'])->name('quizzes.show');
        Route::post('quizzes/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('quizzes.submit');
        Route::get('quizzes/{quiz}/review', [StudentQuizController::class, 'review'])->name('quizzes.review');

        Route::get('progress', [StudentProgressController::class, 'index'])->name('progress.index');
    });

});

require __DIR__.'/auth.php';
