<?php

namespace App\Livewire;

use App\Models\Shreny;
use App\Models\ShrenySubject;
use App\Models\Subject;
use Livewire\Component;

class ShrenySubjectComp extends Component
{
    public bool $showAssignedOnly = false;

    public function toggleAssignedOnly(): void
    {
        $this->showAssignedOnly = !$this->showAssignedOnly;
    }

    public function toggleAssignment(int $shrenyId, int $subjectId): void
    {
        $mapping = ShrenySubject::query()
            ->where('shreny_id', $shrenyId)
            ->where('subject_id', $subjectId)
            ->first();

        if ($mapping) {
            $mapping->delete();
            return;
        }

        ShrenySubject::create([
            'shreny_id' => $shrenyId,
            'subject_id' => $subjectId,
            'is_active' => true,
        ]);
    }

    public function render()
    {
        $shrenies = Shreny::query()->orderBy('order_id')->orderBy('name')->get();
        $subjects = Subject::query()->orderBy('order_id')->orderBy('name')->get();
        $assignedSubjects = ShrenySubject::query()
            ->get(['shreny_id', 'subject_id'])
            ->groupBy('shreny_id')
            ->map(fn($mappings) => $mappings->pluck('subject_id')->map(fn($id) => (int) $id)->all())
            ->all();

        return view('livewire.shreny-subject-comp', compact('shrenies', 'subjects', 'assignedSubjects'));
    }
}