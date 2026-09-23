<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Models\Notice;
use App\Models\StudentDb;
use App\Models\StudentCr;
use App\Support\ExamMarksRegisterData;
use App\Support\ExamMarksSheetData;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPdf\Facades\Pdf;


Route::get('/', function () {
    $notices = Notice::query()
        ->where('is_active', true)
        ->orderByDesc('upload_dt')
        ->orderByDesc('id')
        ->get();

    return view('welcome', compact('notices'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::view('/admin/users', 'admin.users')->name('admin.users');
        Route::view('/admin/shreny-sections', 'admin.shreny-sections')->name('admin.shreny-sections');
        Route::view('/admin/shreny-subjects', 'admin.shreny-subjects')->name('admin.shreny-subjects');
        Route::view('/admin/exam-settings', 'admin.exam-settings')->name('admin.exam-settings');
        Route::view('/admin/exam-settings/exam-names', 'admin.exam-names')->name('admin.exam-names');
        Route::view('/admin/exam-settings/exam-types', 'admin.exam-types')->name('admin.exam-types');
        Route::view('/admin/exam-settings/exam-parts', 'admin.exam-parts')->name('admin.exam-parts');
        Route::view('/admin/exam-settings/exam-modes', 'admin.exam-modes')->name('admin.exam-modes');
        Route::view('/admin/exam-settings/exam-grades', 'admin.exam-grades')->name('admin.exam-grades');
        Route::view('/admin/exam-settings/details', 'admin.exam-details')->name('admin.exam-details');
        Route::view('/admin/exam-settings/marks', 'admin.exam-marks')->name('admin.exam-marks');
        Route::view('/admin/exam-settings/marks/sheets', 'admin.exam-marks-sheets')->name('admin.exam-marks.sheets');
        Route::get('/admin/exam-settings/marks/register/pdf', function (ExamMarksRegisterData $registerData) {
            return Pdf::view('pdfs.exam-marks-register', $registerData->build() + compact('registerData'))
                ->format('a3')
                ->withBrowsershot(function ($browsershot) {
                    $browsershot
                        ->setChromePath('C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe')
                        ->setNodeModulePath(base_path('node_modules'))
                        ->noSandbox();
                })
                ->download('exam-marks-register.pdf');
        })->name('admin.exam-marks.register.pdf');
        Route::get('/admin/exam-settings/marks/sheet/{studentCr}', function (StudentCr $studentCr, ExamMarksSheetData $sheetData) {
            return view('admin.exam-marks-sheet', $sheetData->build($studentCr) + ['registerData' => $sheetData, 'studentCr' => $studentCr]);
        })->name('admin.exam-marks.sheet');
        Route::get('/admin/exam-settings/marks/sheet/{studentCr}/pdf', function (StudentCr $studentCr, ExamMarksSheetData $sheetData) {
            return Pdf::view('pdfs.exam-marks-sheet', $sheetData->build($studentCr) + ['registerData' => $sheetData])
                ->format('a4')->landscape()
                ->withBrowsershot(function ($browsershot) {
                    $browsershot
                        ->setChromePath('C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe')
                        ->setNodeModulePath(base_path('node_modules'))
                        ->noSandbox();
                })
                ->download("marks-sheet-{$studentCr->id}.pdf");
        })->name('admin.exam-marks.sheet.pdf');
        Route::view('/admin/students', 'admin.students')->name('admin.students');
        Route::get('/admin/students/new', fn() => view('admin.student-form'))->name('admin.students.create');
        Route::get('/admin/students/{studentDb}/edit', fn(StudentDb $studentDb) => view('admin.student-form', compact('studentDb')))->name('admin.students.edit');
        Route::get('/admin/students/{studentDb}/pdf', function (StudentDb $studentDb) {
            return Pdf::view('pdfs.student', [
                'student' => $studentDb->load(['classRecords.shreny', 'classRecords.section']),
            ])->format('a4')
                ->withBrowsershot(function ($browsershot) {
                    $browsershot
                        ->setChromePath('C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe')
                        ->setNodeModulePath(base_path('node_modules'))
                        ->noSandbox();
                })
                ->name("student-{$studentDb->id}.pdf");
        })->name('admin.students.pdf');


        Route::view('/admin/studentcrs', 'admin.studentcrs')->name('admin.studentcrs');


    });

    // Teacher only routes
    Route::middleware(['role:teacher'])->group(function () {
        Route::get('/teacher/dashboard', [DashboardController::class, 'teacherDashboard'])
            ->name('teacher.dashboard');
        Route::get('/teacher/grades', [DashboardController::class, 'manageGrades'])
            ->name('teacher.grades');
    });

    // Student only routes
    Route::middleware(['role:student'])->group(function () {
        Route::get('/student/dashboard', [DashboardController::class, 'studentDashboard'])
            ->name('student.dashboard');
        Route::get('/student/grades', [DashboardController::class, 'viewGrades'])
            ->name('student.grades');
    });

    // Routes accessible by multiple roles
    Route::middleware(['role:admin,teacher'])->group(function () {
        Route::get('/attendance', [DashboardController::class, 'attendance'])
            ->name('attendance');
    });
});



require __DIR__ . '/auth.php';
