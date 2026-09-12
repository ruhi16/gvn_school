<?php

namespace App\Livewire;

use App\Models\Section;
use App\Models\Shreny;
use App\Models\ShrenySection;
use App\Models\StudentCr;
use App\Models\StudentDb;
use Livewire\Component;

class ShrenySectionComp extends Component
{
    public bool $showAssignedOnly = false;
    public ?int $selectedShrenyId = null;
    public ?int $selectedSectionId = null;
    public array $rollNumbers = [];

    public function toggleAssignedOnly(): void
    {
        $this->showAssignedOnly = !$this->showAssignedOnly;
    }

    public function toggleAssignment(int $shrenyId, int $sectionId): void
    {
        $mapping = ShrenySection::query()
            ->where('shreny_id', $shrenyId)
            ->where('section_id', $sectionId)
            ->first();

        if ($mapping) {
            $mapping->delete();
            return;
        }

        ShrenySection::create([
            'shreny_id' => $shrenyId,
            'section_id' => $sectionId,
            'is_active' => true,
        ]);
    }

    public function updatedSelectedShrenyId(): void
    {
        $this->rollNumbers = [];
    }

    public function updatedSelectedSectionId(): void
    {
        $this->loadRollNumbers();
    }

    public function assignAutomatically(): void
    {
        $students = $this->selectedStudents();

        if ($students->isEmpty()) {
            session()->flash('error', 'No admitted students found for this Shreny and Section.');
            return;
        }

        foreach ($students->values() as $index => $student) {
            $this->saveStudentCr($student, $index + 1);
        }

        $this->loadRollNumbers();
        session()->flash('success', 'Roll numbers assigned automatically.');
    }

    public function assignManually(): void
    {
        $students = $this->selectedStudents();

        if ($students->isEmpty()) {
            session()->flash('error', 'No admitted students found for this Shreny and Section.');
            return;
        }

        $rolls = collect($students)->mapWithKeys(fn($student) => [$student->id => (int) ($this->rollNumbers[$student->id] ?? 0)]);

        if ($rolls->contains(fn($roll) => $roll < 1)) {
            $this->addError('rollNumbers', 'Enter a roll number for every student.');
            return;
        }

        if ($rolls->count() !== $rolls->unique()->count()) {
            $this->addError('rollNumbers', 'Roll numbers must be unique within this Shreny and Section.');
            return;
        }

        foreach ($students as $student) {
            $this->saveStudentCr($student, $rolls[$student->id]);
        }

        $this->resetErrorBag();
        session()->flash('success', 'Manual roll numbers saved.');
    }

    public function removeAssignment(int $studentId): void
    {
        if (!$this->hasSelectedCombination()) {
            return;
        }

        StudentCr::query()
            ->where('studentdb_id', $studentId)
            ->where('curr_shreny_id', $this->selectedShrenyId)
            ->where('curr_section_id', $this->selectedSectionId)
            ->delete();

        $this->loadRollNumbers();
        session()->flash('success', 'Student roll assignment removed.');
    }

    private function hasSelectedCombination(): bool
    {
        return $this->selectedShrenyId !== null && $this->selectedSectionId !== null;
    }

    private function selectedStudents()
    {
        if (!$this->hasSelectedCombination()) {
            return collect();
        }

        return StudentDb::query()
            ->where('adm_shreny_id', $this->selectedShrenyId)
            ->where('adm_section_id', $this->selectedSectionId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    private function loadRollNumbers(): void
    {
        $this->rollNumbers = StudentCr::query()
            ->where('curr_shreny_id', $this->selectedShrenyId)
            ->where('curr_section_id', $this->selectedSectionId)
            ->pluck('curr_roll_no', 'studentdb_id')
            ->map(fn($roll) => (string) $roll)
            ->all();
    }

    private function saveStudentCr(StudentDb $student, int $rollNumber): void
    {
        StudentCr::updateOrCreate(
            [
                'studentdb_id' => $student->id,
                'curr_shreny_id' => $this->selectedShrenyId,
                'curr_section_id' => $this->selectedSectionId,
            ],
            [
                'curr_roll_no' => $rollNumber,
                'school_id' => $student->school_id,
                'session_id' => $student->session_id,
                'order_id' => $student->order_id,
                'is_promoted' => true,
                'is_active' => true,
            ]
        );
    }

    public function render()
    {
        $shrenies = Shreny::query()
            ->orderBy('order_id')
            ->orderBy('name')
            ->get();

        $sections = Section::query()
            ->when($this->showAssignedOnly, function ($query) {
                $query->whereIn('id', ShrenySection::query()->select('section_id'));
            })
            ->orderBy('order_id')
            ->orderBy('name')
            ->get();

        $assignedSections = ShrenySection::query()
            ->get(['shreny_id', 'section_id'])
            ->groupBy('shreny_id')
            ->map(fn($mappings) => $mappings->pluck('section_id')->map(fn($id) => (int) $id)->all())
            ->all();

        $students = $this->selectedStudents();
        $assignedRolls = $this->hasSelectedCombination()
            ? StudentCr::query()
                ->where('curr_shreny_id', $this->selectedShrenyId)
                ->where('curr_section_id', $this->selectedSectionId)
                ->pluck('curr_roll_no', 'studentdb_id')
                ->all()
            : [];

        return view('livewire.shreny-section-comp', [
            'shrenies' => $shrenies,
            'sections' => $sections,
            'assignedSections' => $assignedSections,
            'students' => $students,
            'assignedRolls' => $assignedRolls,
        ]);
    }
}