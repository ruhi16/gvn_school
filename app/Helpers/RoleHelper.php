<?php

namespace App\Helpers;

use App\Enums\UserRole;

class RoleHelper
{
    public static function getNavigation($user)
    {
        $navItems = [];

        if (!$user) {
            return [
                ['label' => 'Login', 'route' => 'login'],
                ['label' => 'Register', 'route' => 'register'],
            ];
        }

        // Common navigation for all logged-in users
        $navItems[] = ['label' => 'Dashboard', 'route' => 'dashboard'];

        // Role-specific navigation
        switch ($user->role) {
            case UserRole::ADMIN:
                $navItems[] = ['label' => 'Admin Panel', 'route' => 'admin.dashboard'];
                $navItems[] = ['label' => 'Manage Users', 'route' => 'admin.users'];
                break;
            case UserRole::TEACHER:
                $navItems[] = ['label' => 'Teacher Panel', 'route' => 'teacher.dashboard'];
                $navItems[] = ['label' => 'Manage Grades', 'route' => 'teacher.grades'];
                break;
            case UserRole::STUDENT:
                $navItems[] = ['label' => 'Student Panel', 'route' => 'student.dashboard'];
                $navItems[] = ['label' => 'View Grades', 'route' => 'student.grades'];
                break;
        }

        // Navigation for multiple roles
        if ($user->isAdmin() || $user->isTeacher()) {
            $navItems[] = ['label' => 'Attendance', 'route' => 'attendance'];
        }

        return $navItems;
    }
}


// // In Blade
// @if(Auth::user()->isAdmin())
//     <a href="/admin">Admin Panel</a>
// @endif

// // In Controller
// if (!Auth::user()->isAdmin()) {
//     abort(403);
// }