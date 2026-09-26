<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\ExamName;
use App\Models\ExamPart;
use App\Models\ExamRoom;
use App\Models\ExamShrenyPartFmPm;
use App\Models\ExamType;
use App\Models\Room;
use App\Models\Section;
use App\Models\Shreny;
use App\Models\ShrenySection;
use App\Models\StudentCr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ExamRoomComp extends Component
{
    use UsesActiveSchoolSession;

    public ?int $selectedExamNameId = null, $selectedExamTypeId = null, $selectedExamPartId = null;
    public ?int $recordId = null, $selectedShrenyId = null, $selectedSectionId = null, $selectedRoomId = null;
    public ?int $roll_no_range_start = null, $roll_no_range_end = null, $no_of_students_per_bench = null;
    public int $capacityPreviewCount = 0;
    public array $capacityPreviewRanges = [];
    public string $remarks = '', $selectedGroupKey = '', $selectedCombinationKey = '';
    public bool $showModal = false, $autoAllocateByCapacity = false;

    public function updatedSelectedExamNameId(): void { $this->resetValidation(); }
    public function updatedSelectedExamTypeId(): void { $this->resetValidation(); }
    public function updatedSelectedExamPartId(): void { $this->resetValidation(); }
    public function updatedSelectedCombinationKey(string $key): void
    {
        [$nameId, $typeId, $partId] = array_pad(explode(':', $key, 3), 3, null);
        $this->selectedExamNameId = $nameId !== null && $nameId !== '' ? (int) $nameId : null;
        $this->selectedExamTypeId = $typeId !== null && $typeId !== '' ? (int) $typeId : null;
        $this->selectedExamPartId = $partId !== null && $partId !== '' ? (int) $partId : null;
        $this->resetValidation();
    }

    public function updatedSelectedGroupKey(string $key): void
    {
        [$shrenyId, $sectionId] = array_pad(explode(':', $key, 2), 2, null);
        $this->selectedShrenyId = $shrenyId !== null && $shrenyId !== '' ? (int) $shrenyId : null;
        $this->selectedSectionId = $sectionId !== null && $sectionId !== '' ? (int) $sectionId : null;
        $this->clearCapacityPreview();
    }
    public function updatedSelectedRoomId(): void { $this->clearCapacityPreview(); }

    public function useManualRollRange(): void
    {
        $this->clearCapacityPreview();
    }

    public function openAllocation(?int $shrenyId = null, ?int $sectionId = null, ?int $roomId = null): void
    {
        if (!$this->canMutate()) return;
        abort_unless($this->combinationIsConfigured(), 422, 'Choose a configured exam combination first.');
        abort_if($this->isCombinationFinalized(), 422, 'This exam combination has already been finalized.');
        $this->resetAllocationForm();
        $this->selectedShrenyId = $shrenyId;
        $this->selectedSectionId = $sectionId;
        $this->selectedGroupKey = $shrenyId && $sectionId ? "{$shrenyId}:{$sectionId}" : '';
        $this->selectedRoomId = $roomId;
        $this->showModal = true;
    }

    public function editAllocation(int $id): void
    {
        if (!$this->canMutate()) return;
        abort_if($this->isCombinationFinalized(), 422, 'Unfinalize this room allotment before editing it.');

        $allocation = $this->selectedAllocations()->findOrFail($id);
        $this->resetAllocationForm();
        $this->recordId = $allocation->id;
        $this->selectedShrenyId = (int) $allocation->shreny_id;
        $this->selectedSectionId = (int) $allocation->section_id;
        $this->selectedGroupKey = "{$allocation->shreny_id}:{$allocation->section_id}";
        $this->selectedRoomId = (int) $allocation->room_id;
        $this->roll_no_range_start = (int) $allocation->roll_no_range_start;
        $this->roll_no_range_end = (int) $allocation->roll_no_range_end;
        $this->no_of_students_per_bench = $allocation->no_of_students_per_bench;
        $this->remarks = $allocation->remarks ?? '';
        $this->showModal = true;
    }

    public function previewCapacityAllotment(): void
    {
        if (!$this->canMutate()) return;
        abort_unless($this->combinationIsConfigured(), 422, 'Choose a configured exam combination first.');
        if (!$this->selectedShrenyId || !$this->selectedSectionId || !$this->selectedRoomId) {
            $this->addError('selectedRoomId', 'Choose a Shreny-Section and room first.');
            return;
        }

        $room = $this->usableRooms()->findOrFail($this->selectedRoomId);
        $ranges = $this->capacityRangesFor($room);
        if ($ranges === []) {
            $this->clearCapacityPreview();
            $this->addError('selectedRoomId', 'The room has no remaining capacity or this section has no unassigned students.');
            return;
        }

        $this->autoAllocateByCapacity = true;
        $this->capacityPreviewRanges = $ranges;
        $this->capacityPreviewCount = array_sum(array_map(fn ($range) => $range['end'] - $range['start'] + 1, $ranges));
        $this->resetValidation();
    }

    public function saveAllocation(): void
    {
        if (!$this->canMutate()) return;
        $editing = $this->recordId !== null;
        abort_unless($this->combinationIsConfigured(), 422, 'Choose a configured exam combination first.');
        abort_if($this->isCombinationFinalized(), 422, 'This exam combination has already been finalized.');
        if (!$this->activeSession()) {
            $this->addError('selectedExamNameId', 'An active school session is required before assigning rooms.');
            return;
        }

        $rules = [
            'selectedExamNameId' => ['required', 'exists:exam_names,id'],
            'selectedExamTypeId' => ['required', 'exists:exam_types,id'],
            'selectedExamPartId' => ['required', 'exists:exam_parts,id'],
            'selectedShrenyId' => ['required', 'exists:shrenies,id'],
            'selectedSectionId' => ['required', 'exists:sections,id'],
            'selectedRoomId' => ['required', 'integer'],
            'no_of_students_per_bench' => ['nullable', 'integer', 'min:1'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ];
        if (!$this->autoAllocateByCapacity) {
            $rules['roll_no_range_start'] = ['required', 'integer', 'min:1'];
            $rules['roll_no_range_end'] = ['required', 'integer', 'gte:roll_no_range_start'];
        }
        $this->validate($rules);

        $this->scopedQuery(ExamName::query())->findOrFail($this->selectedExamNameId);
        $this->scopedQuery(ExamType::query())->findOrFail($this->selectedExamTypeId);
        $this->scopedQuery(ExamPart::query())->findOrFail($this->selectedExamPartId);
        $this->scopedQuery(ShrenySection::query())->where('is_active', true)
            ->where('shreny_id', $this->selectedShrenyId)
            ->where('section_id', $this->selectedSectionId)->firstOrFail();

        $room = $this->usableRooms()->findOrFail($this->selectedRoomId);
        $allocations = $this->selectedAllocations();
        $allocationsForCapacity = $allocations->reject(fn ($allocation) => (int) $allocation->id === $this->recordId);
        $capacity = $room->no_of_students_total
            ?? (($room->no_of_benches ?? 0) * ($room->no_of_students_per_bench ?? 0));
        $occupied = $this->roomStudentCount($room->id, $allocationsForCapacity);
        $ranges = $this->autoAllocateByCapacity
            ? $this->capacityRangesFor($room)
            : [['start' => $this->roll_no_range_start, 'end' => $this->roll_no_range_end]];
        $roster = $this->rosterFor($this->selectedShrenyId, $this->selectedSectionId);
        $rangeCount = $this->autoAllocateByCapacity
            ? array_sum(array_map(fn ($range) => $roster->filter(fn ($student) => $student->curr_roll_no >= $range['start'] && $student->curr_roll_no <= $range['end'])->count(), $ranges))
            : $roster->filter(fn ($student) => $student->curr_roll_no >= $this->roll_no_range_start && $student->curr_roll_no <= $this->roll_no_range_end)->count();
        if (!$this->autoAllocateByCapacity) {
            $overlap = $allocationsForCapacity->first(fn ($allocation) =>
                (int) $allocation->shreny_id === $this->selectedShrenyId
                && (int) $allocation->section_id === $this->selectedSectionId
                && $allocation->roll_no_range_start <= $this->roll_no_range_end
                && $allocation->roll_no_range_end >= $this->roll_no_range_start
            );
            if ($overlap) {
                $this->addError('roll_no_range_start', 'This range overlaps an existing room assignment for the selected Shreny-Section.');
                return;
            }
        }
        if ($rangeCount === 0) {
            $this->addError($this->autoAllocateByCapacity ? 'selectedRoomId' : 'roll_no_range_start', 'No unassigned students are available in this range.');
            return;
        }
        if ($capacity < 1 || $occupied + $rangeCount > $capacity) {
            $this->addError('selectedRoomId', 'The selected room does not have enough configured remaining capacity.');
            return;
        }

        DB::transaction(function () use ($ranges) {
            if ($this->recordId !== null) {
                $allocation = $this->selectedAllocations()->findOrFail($this->recordId);
                $allocation->update([
                    'shreny_id' => $this->selectedShrenyId,
                    'section_id' => $this->selectedSectionId,
                    'room_id' => $this->selectedRoomId,
                    'no_of_students_per_bench' => $this->no_of_students_per_bench,
                    'roll_no_range_start' => $ranges[0]['start'],
                    'roll_no_range_end' => $ranges[0]['end'],
                    'remarks' => $this->remarks,
                ]);
                return;
            }

            foreach ($ranges as $range) {
                ExamRoom::create([
                    'exam_name_id' => $this->selectedExamNameId,
                    'exam_type_id' => $this->selectedExamTypeId,
                    'exam_part_id' => $this->selectedExamPartId,
                    'shreny_id' => $this->selectedShrenyId,
                    'section_id' => $this->selectedSectionId,
                    'room_id' => $this->selectedRoomId,
                    'no_of_students_per_bench' => $this->no_of_students_per_bench,
                    'roll_no_range_start' => $range['start'],
                    'roll_no_range_end' => $range['end'],
                    'school_id' => $this->activeSchoolId(),
                    'session_id' => $this->activeSession()?->id,
                    'is_active' => true,
                    'is_finalized' => false,
                    'remarks' => $this->remarks,
                ]);
            }
        });

        $this->showModal = false;
        $this->resetAllocationForm();
        session()->flash('success', $editing ? 'Room allotment updated.' : "{$rangeCount} student(s) assigned to the room.");
    }

    public function finalizeAllotment(): void
    {
        if (!$this->canMutate()) return;
        abort_unless($this->combinationIsConfigured(), 422, 'Choose a configured exam combination first.');
        abort_if($this->isCombinationFinalized(), 422, 'This exam combination has already been finalized.');
        if ($this->selectedAllocations()->isEmpty()) {
            $this->addError('selectedCombinationKey', 'There are no room assignments to finalize.');
            return;
        }
        if (!$this->allMappedStudentsAllocated()) {
            $this->addError('selectedCombinationKey', 'Assign every current-session student before finalizing this combination.');
            return;
        }

        $this->scopedQuery(ExamRoom::query())
            ->where('exam_name_id', $this->selectedExamNameId)
            ->where('exam_type_id', $this->selectedExamTypeId)
            ->where('exam_part_id', $this->selectedExamPartId)
            ->where('is_active', true)
            ->update(['is_finalized' => true]);

        $this->resetValidation();
        session()->flash('success', 'Room allotment finalized.');
    }

    public function unfinalizeAllotment(): void
    {
        if (!$this->canMutate()) return;
        abort_unless($this->combinationIsConfigured(), 422, 'Choose a configured exam combination first.');
        abort_unless($this->isCombinationFinalized(), 422, 'This room allotment is not finalized.');

        $this->scopedQuery(ExamRoom::query())
            ->where('exam_name_id', $this->selectedExamNameId)
            ->where('exam_type_id', $this->selectedExamTypeId)
            ->where('exam_part_id', $this->selectedExamPartId)
            ->where('is_active', true)
            ->update(['is_finalized' => false]);

        session()->flash('success', 'Room allotment is editable again.');
    }

    public function deleteAllocation(int $id): void
    {
        if (!$this->canMutate()) return;
        abort_if($this->isCombinationFinalized(), 422, 'Finalized room allotments cannot be changed.');
        $this->selectedAllocations()->findOrFail($id)->delete();
        session()->flash('success', 'Room assignment removed.');
    }

    private function resetAllocationForm(): void
    {
        $this->reset(['recordId', 'selectedShrenyId', 'selectedSectionId', 'selectedRoomId', 'roll_no_range_start', 'roll_no_range_end', 'no_of_students_per_bench', 'remarks', 'selectedGroupKey', 'capacityPreviewCount', 'capacityPreviewRanges', 'autoAllocateByCapacity']);
        $this->resetValidation();
    }

    private function clearCapacityPreview(): void
    {
        $this->autoAllocateByCapacity = false;
        $this->capacityPreviewCount = 0;
        $this->capacityPreviewRanges = [];
    }

    private function combinationIsConfigured(): bool
    {
        if (!$this->selectedExamNameId || !$this->selectedExamTypeId || !$this->selectedExamPartId) {
            return false;
        }

        return $this->scopedQuery(ExamName::query())->whereKey($this->selectedExamNameId)->exists()
            && $this->scopedQuery(ExamType::query())->whereKey($this->selectedExamTypeId)->exists()
            && $this->scopedQuery(ExamPart::query())->whereKey($this->selectedExamPartId)->exists()
            && ExamShrenyPartFmPm::query()
            ->whereNull('shreny_id')->whereNull('subject_id')->where('is_active', true)
            ->where('exam_name_id', $this->selectedExamNameId)
            ->where('exam_type_id', $this->selectedExamTypeId)
            ->where('exam_part_id', $this->selectedExamPartId)
            ->exists();
    }

    private function isCombinationFinalized(): bool
    {
        if (!$this->selectedExamNameId || !$this->selectedExamTypeId || !$this->selectedExamPartId) {
            return false;
        }

        return $this->scopedQuery(ExamRoom::query())
            ->where('exam_name_id', $this->selectedExamNameId)
            ->where('exam_type_id', $this->selectedExamTypeId)
            ->where('exam_part_id', $this->selectedExamPartId)
            ->where('is_finalized', true)->exists();
    }

    private function capacityRangesFor(Room $room): array
    {
        $capacity = $room->no_of_students_total
            ?? (($room->no_of_benches ?? 0) * ($room->no_of_students_per_bench ?? 0));
        $allocations = $this->selectedAllocations();
        $remainingSeats = max(0, $capacity - $this->roomStudentCount((int) $room->id, $allocations));
        if ($remainingSeats === 0 || !$this->selectedShrenyId || !$this->selectedSectionId) {
            return [];
        }

        $roster = $this->rosterFor($this->selectedShrenyId, $this->selectedSectionId);
        $groupAllocations = $allocations->where('shreny_id', $this->selectedShrenyId)
            ->where('section_id', $this->selectedSectionId);
        $assignedRolls = $roster->filter(fn ($student) => $groupAllocations->contains(fn ($allocation) =>
            $student->curr_roll_no >= $allocation->roll_no_range_start
            && $student->curr_roll_no <= $allocation->roll_no_range_end
        ))->pluck('curr_roll_no')->map(fn ($roll) => (int) $roll)->all();
        $nextRolls = $roster->reject(fn ($student) => in_array((int) $student->curr_roll_no, $assignedRolls, true))
            ->take($remainingSeats)->pluck('curr_roll_no')->map(fn ($roll) => (int) $roll)->all();

        return $this->compressRollNumbers($nextRolls);
    }

    private function compressRollNumbers(array $rollNumbers): array
    {
        sort($rollNumbers, SORT_NUMERIC);
        $ranges = [];
        foreach (array_unique($rollNumbers) as $rollNumber) {
            $lastIndex = array_key_last($ranges);
            if ($lastIndex !== null && $ranges[$lastIndex]['end'] + 1 === $rollNumber) {
                $ranges[$lastIndex]['end'] = $rollNumber;
                continue;
            }
            $ranges[] = ['start' => $rollNumber, 'end' => $rollNumber];
        }

        return $ranges;
    }

    private function allMappedStudentsAllocated(): bool
    {
        if (!$this->activeSession()) {
            return false;
        }

        $allocations = $this->selectedAllocations();
        $mappings = $this->scopedQuery(ShrenySection::query())->where('is_active', true)->get();
        if ($mappings->isEmpty()) {
            return false;
        }

        foreach ($mappings as $mapping) {
            $students = $this->rosterFor($mapping->shreny_id, $mapping->section_id);
            $matchingAllocations = $allocations->where('shreny_id', $mapping->shreny_id)
                ->where('section_id', $mapping->section_id);
            $assignedCount = $students->filter(fn ($student) => $matchingAllocations->contains(fn ($allocation) =>
                $student->curr_roll_no >= $allocation->roll_no_range_start
                && $student->curr_roll_no <= $allocation->roll_no_range_end
            ))->count();
            if ($assignedCount < $students->count()) {
                return false;
            }
        }

        return true;
    }

    private function scopedQuery($model)
    {
        return $model->where('school_id', $this->activeSchoolId())
            ->where(function ($query) {
                $query->whereNull('session_id')->orWhere('session_id', $this->activeSession()?->id);
            });
    }

    private function selectedAllocations()
    {
        if (!$this->selectedExamNameId || !$this->selectedExamTypeId || !$this->selectedExamPartId) {
            return collect();
        }

        return $this->scopedQuery(ExamRoom::query())
            ->where('is_active', true)
            ->where('exam_name_id', $this->selectedExamNameId)
            ->where('exam_type_id', $this->selectedExamTypeId)
            ->where('exam_part_id', $this->selectedExamPartId)
            ->with(['room', 'shreny', 'section'])
            ->orderBy('room_id')->orderBy('shreny_id')->orderBy('section_id')->orderBy('roll_no_range_start')
            ->get();
    }

    private function usableRooms()
    {
        return $this->scopedQuery(Room::query())
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('room_status')
                    ->orWhereNotIn('room_status', ['Under Maintenance', 'Closed']);
            });
    }

    private function rosterFor(int $shrenyId, int $sectionId)
    {
        return StudentCr::query()
            ->where('school_id', $this->activeSchoolId())
            ->where('session_id', $this->activeSession()?->id)
            ->where('is_active', true)
            ->where('curr_shreny_id', $shrenyId)
            ->where('curr_section_id', $sectionId)
            ->whereNotNull('curr_roll_no')
            ->with('student')
            ->orderBy('curr_roll_no')->get();
    }

    private function rangeStudentCount(ExamRoom $allocation): int
    {
        return $this->rosterFor($allocation->shreny_id, $allocation->section_id)
            ->filter(fn ($student) => $student->curr_roll_no >= $allocation->roll_no_range_start && $student->curr_roll_no <= $allocation->roll_no_range_end)
            ->count();
    }

    private function roomStudentCount(int $roomId, $allocations): int
    {
        return $allocations->where('room_id', $roomId)->sum(fn ($allocation) => $allocation->rangeStudentCount ?? $this->rangeStudentCount($allocation));
    }

    public function render()
    {
        $examNames = $this->scopedQuery(ExamName::query())->where('is_active', true)->orderBy('order_id')->orderBy('name')->get();
        $examTypes = $this->scopedQuery(ExamType::query())->where('is_active', true)->orderBy('order_id')->orderBy('name')->get();
        $examParts = $this->scopedQuery(ExamPart::query())->where('is_active', true)->orderBy('order_id')->orderBy('name')->get();
        $finishedKeys = $this->scopedQuery(ExamRoom::query())->where('is_finalized', true)->get()
            ->map(fn ($allocation) => "{$allocation->exam_name_id}:{$allocation->exam_type_id}:{$allocation->exam_part_id}")->flip();
        $availableCombinations = ExamShrenyPartFmPm::query()
            ->whereNull('shreny_id')->whereNull('subject_id')->whereNotNull('exam_part_id')->where('is_active', true)
            ->get()->unique(fn ($configuration) => "{$configuration->exam_name_id}:{$configuration->exam_type_id}:{$configuration->exam_part_id}")
            ->filter(function ($configuration) use ($examNames, $examTypes, $examParts, $finishedKeys) {
                $key = "{$configuration->exam_name_id}:{$configuration->exam_type_id}:{$configuration->exam_part_id}";
                return $examNames->contains('id', (int) $configuration->exam_name_id)
                    && $examTypes->contains('id', (int) $configuration->exam_type_id)
                    && $examParts->contains('id', (int) $configuration->exam_part_id);
            })
            ->map(function ($configuration) use ($examNames, $examTypes, $examParts, $finishedKeys) {
                $examName = $examNames->firstWhere('id', (int) $configuration->exam_name_id);
                $examType = $examTypes->firstWhere('id', (int) $configuration->exam_type_id);
                $examPart = $examParts->firstWhere('id', (int) $configuration->exam_part_id);
                return [
                    'key' => "{$configuration->exam_name_id}:{$configuration->exam_type_id}:{$configuration->exam_part_id}",
                    'name_id' => (int) $configuration->exam_name_id,
                    'type_id' => (int) $configuration->exam_type_id,
                    'part_id' => (int) $configuration->exam_part_id,
                    'label' => "{$examName->name} / {$examType->name} / {$examPart->name}",
                    'finalized' => $finishedKeys->has("{$configuration->exam_name_id}:{$configuration->exam_type_id}:{$configuration->exam_part_id}"),
                ];
            })->values();

        $allocations = $this->selectedAllocations();
        $session = $this->activeSession();
        $rosterRecords = $session ? StudentCr::query()
            ->where('school_id', $this->activeSchoolId())
            ->where('session_id', $session->id)
            ->where('is_active', true)->whereNotNull('curr_roll_no')
            ->with('student')->orderBy('curr_roll_no')->get()
            : collect();
        $roster = $rosterRecords->groupBy(fn ($student) => $student->curr_shreny_id . ':' . $student->curr_section_id);
        $allocations->each(function ($allocation) use ($roster) {
            $students = $roster->get($allocation->shreny_id . ':' . $allocation->section_id, collect());
            $allocation->rangeStudentCount = $students->filter(fn ($student) => $student->curr_roll_no >= $allocation->roll_no_range_start && $student->curr_roll_no <= $allocation->roll_no_range_end)->count();
        });

        $groups = $this->scopedQuery(ShrenySection::query())
            ->where('is_active', true)->get()
            ->map(function ($mapping) use ($roster, $allocations) {
                $key = $mapping->shreny_id . ':' . $mapping->section_id;
                $students = $roster->get($key, collect());
                $groupAllocations = $allocations->where('shreny_id', $mapping->shreny_id)->where('section_id', $mapping->section_id);
                $assigned = $groupAllocations->sum(fn ($allocation) => $allocation->rangeStudentCount);
                $shreny = Shreny::find($mapping->shreny_id);
                $section = Section::find($mapping->section_id);
                return [
                    'key' => $key, 'shreny_id' => $mapping->shreny_id, 'section_id' => $mapping->section_id,
                    'shreny' => $shreny, 'section' => $section,
                    'students' => $students, 'total' => $students->count(), 'assigned' => $assigned,
                    'remaining' => max(0, $students->count() - $assigned),
                ];
            })->filter(fn ($group) => $group['shreny'] && $group['section'])->values();

        $rooms = $this->usableRooms()->orderBy('name')->get()->map(function ($room) use ($allocations) {
            $room->capacity = $room->no_of_students_total
                ?? (($room->no_of_benches ?? 0) * ($room->no_of_students_per_bench ?? 0));
            $room->assigned_students = $this->roomStudentCount($room->id, $allocations);
            $room->remaining_capacity = max(0, $room->capacity - $room->assigned_students);
            $room->allocation_status = $room->capacity > 0 && $room->assigned_students >= $room->capacity
                ? 'Occupied'
                : ($room->assigned_students > 0 ? 'Partially occupied' : ($room->room_status ?: 'Available'));
            return $room;
        });
        $allocations->each(function ($allocation) use ($allocations, $rooms) {
            $allocation->roomStudentCount = $this->roomStudentCount((int) $allocation->room_id, $allocations);
            $allocation->roomCapacity = $rooms->firstWhere('id', $allocation->room_id)?->capacity ?? 0;
        });

        $complete = $session !== null && $groups->isNotEmpty() && $groups->every(fn ($group) => $group['remaining'] === 0);
        return view('livewire.exam-room-comp', [
            'availableCombinations' => $availableCombinations,
            'groups' => $groups,
            'rooms' => $rooms,
            'allocations' => $allocations,
            'complete' => $complete,
            'isFinalized' => $this->isCombinationFinalized(),
            'hasActiveSession' => $session !== null,
        ]);
    }
}