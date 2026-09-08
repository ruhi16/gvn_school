<?php

namespace App\Livewire;

use App\Models\School;
use App\Models\StudentDb;
use App\Models\Teacher;
use App\Models\User;
use Livewire\Component;

class AdminDashboardComp extends Component
{
    public function render()
    {
        return view('livewire.admin-dashboard-comp', [
            'totalUsers' => User::count(),
            'totalTeachers' => Teacher::count(),
            'totalStudents' => StudentDb::count(),
            'totalSchools' => School::count(),
        ]);
    }
}
