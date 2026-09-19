<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Support\ExamMarksRegisterData;
use Livewire\Component;

class ExamMarksRegisterComp extends Component
{
    use UsesActiveSchoolSession;
    public string $viewMode = 'compact';

    public function setViewMode(string $viewMode): void
    {
        abort_unless(in_array($viewMode, ['compact', 'classic'], true), 422);
        $this->viewMode = $viewMode;
    }

    public function render(ExamMarksRegisterData $registerData)
    {
        $data = $registerData->build();

        return view('livewire.exam-marks-register-comp', $data + compact('registerData'));
    }
}
