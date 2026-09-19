<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\School;
use App\Models\StudentDb;
use App\Models\Teacher;
use App\Models\User;
use Livewire\Component;

class AdminDashboardComp extends Component
{
    use UsesActiveSchoolSession;
    public string $activePanel = 'overview';

    public function mount(string $activePanel = 'overview'): void
    {
        $this->activePanel = $activePanel;
    }

    public function selectPanel(string $panel): void
    {
        $this->activePanel = $panel;
    }

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
