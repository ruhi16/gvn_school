<?php

namespace App\Livewire;

use App\Models\StudentDb;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CurrentProfileComp extends Component
{
    public string $dob = '';
    public ?int $selectedStudentId = null;

    public function verify(): void
    {
        $this->validate(['dob' => ['required', 'date']]);
        if (!$this->selectedStudentId) {
            $this->addError('selectedStudentId', 'Select a matching student record.');
            return;
        }

        $student = StudentDb::query()->whereKey($this->selectedStudentId)->whereDate('dob', $this->dob)->first();
        if (!$student) {
            $this->addError('selectedStudentId', 'The selected record does not match the entered date of birth.');
            return;
        }

        $user = User::findOrFail(Auth::id());
        if ($user->student_id && $user->student_id !== $student->id) {
            $this->addError('selectedStudentId', 'Your student profile is already assigned.');
            return;
        }

        $user->student_id = $student->id;
        $user->save();
        $this->redirectRoute('student.dashboard');
    }

    public function render()
    {
        $matches = $this->dob === ''
            ? collect()
            : StudentDb::query()->whereDate('dob', $this->dob)->orderBy('name')->get();

        return view('livewire.current-profile-comp', compact('matches'));
    }
}