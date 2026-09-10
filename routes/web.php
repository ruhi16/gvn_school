<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
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
        Route::get('/admin/users', [DashboardController::class, 'manageUsers'])->name('admin.users');
        Route::view('/admin/shreny-sections', 'admin.shreny-sections')->name('admin.shreny-sections');



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
