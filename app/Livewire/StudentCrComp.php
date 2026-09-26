<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\Section;
use App\Models\Session;
use App\Models\Shreny;
use App\Models\ShrenySection;
use App\Models\StudentCr;
use App\Models\StudentDb;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class StudentCrComp extends Component
{
    use WithPagination;
    use UsesActiveSchoolSession;

    public string $search = '';
    public ?int $selectedShrenyId = null;
    public ?int $selectedSectionId = null;
    public array $rollNumbers = [];
    public ?int $currentSessionId = null;

    protected $queryString = ['search'];

    public function mount(): void
    {
        $this->currentSessionId = Session::query()->where('is_active', true)
            ->where(function ($query) {
                $query->whereRaw('LOWER(status) = ?', ['active'])->orWhereNull('status');
            })->orderByDesc('id')->value('id');
    }

    public function updatedSearch(): void { $this->resetPage(); }

    public function updatedSelectedShrenyId(): void
    {
        $this->selectedSectionId = null;
        $this->rollNumbers = [];
        $this->resetPage();
    }

    public function updatedSelectedSectionId(): void
    {
        $this->loadRollNumbers();
        $this->resetPage();
    }

    public function assignAutomatically(): void
    {
        if (!$this->canMutate()) return;
        $students = $this->selectedStudents();
        if ($students->isEmpty()) {
            session()->flash('error', 'No newly admitted students found for this Shreny and Section.');
            return;
        }

        DB::transaction(function () use ($students): void {
            $studentIds = $students->modelKeys();
            $existingRolls = StudentCr::query()
                ->where('session_id', $this->currentSessionId)
                ->where('curr_shreny_id', $this->selectedShrenyId)
                ->where('curr_section_id', $this->selectedSectionId)
                ->whereIn('studentdb_id', $studentIds)
                ->pluck('curr_roll_no', 'studentdb_id');
            $usedRolls = StudentCr::query()
                ->where('curr_shreny_id', $this->selectedShrenyId)
                ->where('curr_section_id', $this->selectedSectionId)
                ->whereNotNull('curr_roll_no')
                ->pluck('curr_roll_no')
                ->map(fn ($roll) => (int) $roll)
                ->all();
            $nextRoll = 1;

            foreach ($students as $student) {
                $rollNumber = (int) ($existingRolls[$student->id] ?? 0);
                if ($rollNumber < 1) {
                    while (in_array($nextRoll, $usedRolls, true)) {
                        $nextRoll++;
                    }
                    $rollNumber = $nextRoll++;
                    $usedRolls[] = $rollNumber;
                }

                $this->saveStudentCr($student, $rollNumber);
            }
        });
        $this->loadRollNumbers();
        session()->flash('success', 'Roll numbers assigned automatically.');
    }

    public function assignManually(): void
    {
        if (!$this->canMutate()) return;
        $students = $this->selectedStudents();
        if ($students->isEmpty()) {
            session()->flash('error', 'No newly admitted students found for this Shreny and Section.');
            return;
        }

        $rolls = $students->mapWithKeys(fn ($student) => [$student->id => (int) ($this->rollNumbers[$student->id] ?? 0)]);
        if ($rolls->contains(fn ($roll) => $roll < 1)) {
            $this->addError('rollNumbers', 'Enter a roll number for every student.');
            return;
        }
        if ($rolls->count() !== $rolls->unique()->count()) {
            $this->addError('rollNumbers', 'Roll numbers must be unique within this Shreny and Section.');
            return;
        }
        foreach ($rolls as $studentId => $rollNumber) {
            if (!$this->rollNumberIsAvailable($rollNumber, $studentId)) {
                $this->addError('rollNumbers', "Roll number {$rollNumber} is already assigned in this Shreny and Section.");
                return;
            }
        }

        DB::transaction(function () use ($students, $rolls): void {
            foreach ($students as $student) {
                $this->saveStudentCr($student, $rolls[$student->id]);
            }
        });
        $this->resetErrorBag();
        session()->flash('success', 'Manual roll numbers saved.');
    }

    public function updateRollNumber(int $studentId): void
    {
        if (!$this->canMutate()) return;
        $student = $this->selectedStudents()->firstWhere('id', $studentId);
        $rollNumber = (int) ($this->rollNumbers[$studentId] ?? 0);
        if (!$student || $rollNumber < 1) {
            $this->addError("rollNumbers.{$studentId}", 'Enter a valid roll number.');
            return;
        }
        if (!$this->rollNumberIsAvailable($rollNumber, $studentId)) {
            $this->addError("rollNumbers.{$studentId}", 'This roll number is already assigned in this Shreny and Section.');
            return;
        }
        $this->saveStudentCr($student, $rollNumber);
        $this->resetErrorBag("rollNumbers.{$studentId}");
        session()->flash('success', "Roll number updated for {$student->name}.");
    }

    private function selectedStudents()
    {
        if (!$this->currentSessionId || !$this->selectedShrenyId || !$this->selectedSectionId) {
            return collect();
        }

        return StudentDb::query()->where('session_id', $this->currentSessionId)
            ->where('adm_shreny_id', $this->selectedShrenyId)
            ->where('adm_section_id', $this->selectedSectionId)->where('is_active', true)
            ->when($this->search, fn ($query) => $query->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('fname', 'like', "%{$this->search}%")
                    ->orWhere('aadhaar_id', 'like', "%{$this->search}%")
                    ->orWhere('pen_id', 'like', "%{$this->search}%");
            }))->orderBy('name')->get();
    }

    private function loadRollNumbers(): void
    {
        $this->rollNumbers = $this->currentSessionId && $this->selectedShrenyId && $this->selectedSectionId
            ? StudentCr::query()->where('session_id', $this->currentSessionId)
                ->where('curr_shreny_id', $this->selectedShrenyId)->where('curr_section_id', $this->selectedSectionId)
                ->pluck('curr_roll_no', 'studentdb_id')->map(fn ($roll) => (string) $roll)->all() : [];
    }

    private function saveStudentCr(StudentDb $student, int $rollNumber): void
    {
        StudentCr::updateOrCreate(
            ['studentdb_id' => $student->id, 'session_id' => $this->currentSessionId],
            ['curr_shreny_id' => $this->selectedShrenyId, 'curr_section_id' => $this->selectedSectionId,
                'curr_roll_no' => $rollNumber, 'school_id' => $student->school_id, 'order_id' => $student->order_id,
                'is_promoted' => true, 'is_active' => true]
        );
    }

    private function rollNumberIsAvailable(int $rollNumber, int $studentId): bool
    {
        return !StudentCr::query()->where('session_id', $this->currentSessionId)
            ->where('curr_shreny_id', $this->selectedShrenyId)->where('curr_section_id', $this->selectedSectionId)
            ->where('curr_roll_no', $rollNumber)->where('studentdb_id', '!=', $studentId)->exists();
    }

    public function render()
    {
        $shrenies = Shreny::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get();
        $sectionIds = ShrenySection::query()->where('shreny_id', $this->selectedShrenyId)
            ->where(function ($query) {
                $query->where('session_id', $this->currentSessionId)->orWhereNull('session_id');
            })->where('is_active', true)->pluck('section_id');
        $sections = Section::query()->whereIn('id', $sectionIds)->where('is_active', true)
            ->orderBy('order_id')->orderBy('name')->get();
        $students = $this->selectedStudents();
        $assignedRolls = $this->rollNumbers;

        return view('livewire.student-cr-comp', compact('shrenies', 'sections', 'students', 'assignedRolls'));
    }
}
