<?php

namespace App\Livewire;

use App\Models\Section;
use App\Models\Shreny;
use App\Models\ShrenySection;
use Livewire\Component;

class ShrenySectionComp extends Component
{
    public bool $showAssignedOnly = false;

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

        return view('livewire.shreny-section-comp', [
            'shrenies' => $shrenies,
            'sections' => $sections,
            'assignedSections' => $assignedSections,
        ]);
    }
}