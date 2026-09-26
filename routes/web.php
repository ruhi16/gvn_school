<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Models\Notice;
use App\Models\ExamRoom;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\ShrenySection;
use App\Models\StudentDb;
use App\Models\StudentCr;
use App\Support\ExamMarksRegisterData;
use App\Support\ExamMarksSheetData;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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
        Route::view('/admin/school', 'admin.school')->name('admin.school');
        Route::view('/admin/exam-settings', 'admin.exam-settings')->name('admin.exam-settings');
        Route::view('/admin/exam-settings/exam-names', 'admin.exam-names')->name('admin.exam-names');
        Route::view('/admin/exam-settings/exam-types', 'admin.exam-types')->name('admin.exam-types');
        Route::view('/admin/exam-settings/exam-parts', 'admin.exam-parts')->name('admin.exam-parts');
        Route::view('/admin/exam-settings/exam-modes', 'admin.exam-modes')->name('admin.exam-modes');
        Route::view('/admin/exam-settings/exam-halves', 'admin.exam-halves')->name('admin.exam-halves');
        Route::view('/admin/exam-settings/date-schedules', 'admin.exam-date-schedules')->name('admin.exam-date-schedules');
        Route::view('/admin/exam-settings/exam-grades', 'admin.exam-grades')->name('admin.exam-grades');
        Route::view('/admin/exam-settings/details', 'admin.exam-details')->name('admin.exam-details');
        Route::view('/admin/rooms', 'admin.rooms')->name('admin.rooms');
        Route::view('/admin/exam-settings/rooms', 'admin.exam-rooms')->name('admin.exam-rooms');
        Route::get('/admin/exam-settings/rooms/pdf', function (Request $request) {
            $filters = $request->validate([
                'exam_name_id' => ['required', 'integer', 'exists:exam_names,id'],
                'exam_type_id' => ['required', 'integer', 'exists:exam_types,id'],
                'exam_part_id' => ['required', 'integer', 'exists:exam_parts,id'],
            ]);
            $schoolId = Auth::user()?->school_id;
            $session = Session::query()->where('school_id', $schoolId)->where('is_active', true)->orderByDesc('id')->first();
            abort_unless($session !== null, 422, 'There is no active school session.');
            \App\Models\ExamName::query()->where('school_id', $schoolId)->findOrFail($filters['exam_name_id']);
            \App\Models\ExamType::query()->where('school_id', $schoolId)->findOrFail($filters['exam_type_id']);
            \App\Models\ExamPart::query()->where('school_id', $schoolId)->findOrFail($filters['exam_part_id']);

            $allocations = ExamRoom::query()->where('school_id', $schoolId)->where('session_id', $session->id)
                ->where('is_active', true)->where('exam_name_id', $filters['exam_name_id'])
                ->where('exam_type_id', $filters['exam_type_id'])->where('exam_part_id', $filters['exam_part_id'])
                ->with(['room', 'shreny', 'section'])->orderBy('room_id')->orderBy('shreny_id')->orderBy('section_id')->orderBy('roll_no_range_start')->get();

            $mappings = ShrenySection::query()->where('school_id', $schoolId)->where('is_active', true)
                ->where(function ($query) use ($session) { $query->whereNull('session_id')->orWhere('session_id', $session->id); })->get();
            foreach ($mappings as $mapping) {
                $students = StudentCr::query()->where('school_id', $schoolId)->where('session_id', $session->id)->where('is_active', true)
                    ->where('curr_shreny_id', $mapping->shreny_id)->where('curr_section_id', $mapping->section_id)->whereNotNull('curr_roll_no')->get();
                $assignedCount = $allocations->where('shreny_id', $mapping->shreny_id)->where('section_id', $mapping->section_id)
                    ->sum(fn ($allocation) => $students->filter(fn ($student) => $student->curr_roll_no >= $allocation->roll_no_range_start && $student->curr_roll_no <= $allocation->roll_no_range_end)->count());
                abort_if($assignedCount < $students->count(), 422, 'Complete all student allocations before downloading the room plan.');
            }

            $roomPlans = $allocations->groupBy('room_id')->map(function ($roomAllocations) use ($schoolId, $session) {
                $room = $roomAllocations->first()->room;
                $entries = $roomAllocations->map(function ($allocation) use ($schoolId, $session) {
                    $students = StudentCr::query()->where('school_id', $schoolId)->where('session_id', $session->id)->where('is_active', true)
                        ->where('curr_shreny_id', $allocation->shreny_id)->where('curr_section_id', $allocation->section_id)
                        ->whereBetween('curr_roll_no', [$allocation->roll_no_range_start, $allocation->roll_no_range_end])
                        ->with('student')->orderBy('curr_roll_no')->get();
                    return ['allocation' => $allocation, 'students' => $students];
                });
                $capacity = $room?->no_of_students_total ?? (($room?->no_of_benches ?? 0) * ($room?->no_of_students_per_bench ?? 0));
                return ['room' => $room, 'entries' => $entries, 'students' => $entries->sum(fn ($entry) => $entry['students']->count()), 'capacity' => $capacity];
            })->values();

            $examName = \App\Models\ExamName::find($filters['exam_name_id']);
            $examType = \App\Models\ExamType::find($filters['exam_type_id']);
            $examPart = \App\Models\ExamPart::find($filters['exam_part_id']);
            return Pdf::view('pdfs.exam-room-plan', compact('roomPlans', 'session', 'examName', 'examType', 'examPart'))
                ->format('a4')->landscape()
                ->withBrowsershot(function ($browsershot) {
                    $browsershot->setChromePath('C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe')
                        ->setNodeModulePath(base_path('node_modules'))->noSandbox();
                })->download('exam-room-plan.pdf');
        })->name('admin.exam-rooms.pdf');
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
