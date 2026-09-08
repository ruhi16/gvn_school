<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Main dashboard - shows different content based on role
    public function index()
    {
        $user = Auth::user();
        
        // Redirect to role-specific dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    }

    // Admin Dashboard
    public function adminDashboard()
    {
        $data = [
            'totalUsers' => 150,
            'totalTeachers' => 25,
            'totalStudents' => 120,
            'recentActivities' => ['User added', 'Grade updated'],
        ];
        
        return view('dashboard.admin', compact('data'));
    }

    // Teacher Dashboard
    public function teacherDashboard()
    {
        $data = [
            'totalStudents' => 30,
            'pendingGrades' => 5,
            'classes' => ['Math 101', 'Science 102'],
        ];
        
        return view('dashboard.teacher', compact('data'));
    }

    // Student Dashboard
    public function studentDashboard()
    {
        $data = [
            'gpa' => 3.8,
            'subjects' => ['Math', 'Science', 'English'],
            'attendance' => 95,
        ];
        
        return view('dashboard.student', compact('data'));
    }

    // Admin: Manage Users
    public function manageUsers()
    {
        $users = \App\Models\User::all();
        return view('admin.users', compact('users'));
    }

    // Teacher: Manage Grades
    public function manageGrades()
    {
        // This would fetch grades data
        return view('teacher.grades');
    }

    // Student: View Grades
    public function viewGrades()
    {
        // This would fetch student's grades
        return view('student.grades');
    }

    // Admin & Teacher: Attendance
    public function attendance()
    {
        return view('attendance.index');
    }




}
