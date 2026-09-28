<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\ExamDateSchedule as ExamDateScheduleRecord;
use App\Models\ExamHalf;
use App\Models\ExamMode;
use App\Models\ExamName;
use App\Models\ExamPart;
use App\Models\ExamShrenyPartFmPm;
use App\Models\ExamType;
use App\Models\Section;
use App\Models\Shreny;
use App\Models\ShrenySection;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ExamDateScheduleComp extends Component
{
    use UsesActiveSchoolSession;

    public string $selectedCombinationKey = '';
    public ?int $recordId = null;
    public ?int $exam_mode_id = null;
    public ?int $shreny_id = null;
    public array $section_ids = [];
    public ?int $subject_id = null;
    public ?int $exam_half_id = null;
    public ?int $order_id = null;
    public string $name = '';
    public string $description = '';
    public string $exam_date = '';
    public string $remarks = '';
    public bool $is_active = true;
    public bool $showModal = false;

    public function updatedSelectedCombinationKey(): void
    {
        $this->resetForm();
        $this->showModal = false;
        $this->resetValidation();
    }

    public function updatedShrenyId(): void
    {
        $this->section_ids = [];
        $this->subject_id = null;
        $this->resetValidation('section_ids');
        $this->resetValidation('subject_id');
    }

    public function updatedSectionIds(): void
    {
        $this->resetValidation('section_ids');
    }

    public function create(): void
    {
        if (!$this->canMutate()) {
            return;
        }
        if (!$this->selectedCombinationExists()) {
            $this->addError('selectedCombinationKey', 'Select a configured exam combination first.');
            return;
        }
        if ($this->isFinalized()) {
            session()->flash('error', 'Reopen this finalized schedule before making changes.');
            return;
        }

        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }
        if ($this->isFinalized()) {
            session()->flash('error', 'Reopen this finalized schedule before making changes.');
            return;
        }

        $record = $this->selectedSchedules()->findOrFail($id);
        $this->recordId = $record->id;
        $this->name = $record->name;
        $this->description = $record->description ?? '';
        $this->exam_mode_id = $record->exam_mode_id;
        $this->shreny_id = $record->shreny_id;
        $this->section_ids = [$record->section_id];
        $this->subject_id = $record->subject_id;
        $this->exam_half_id = $record->exam_half_id;
        $this->exam_date = $record->getRawOriginal('exam_date') ?? '';
        $this->order_id = $record->order_id;
        $this->is_active = $record->is_active;
        $this->remarks = $record->remarks ?? '';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        if (!$this->canMutate()) {
            return;
        }
        if (!$this->selectedCombinationExists()) {
            $this->addError('selectedCombinationKey', 'Select a configured exam combination first.');
            return;
        }
        if ($this->isFinalized()) {
            session()->flash('error', 'Reopen this finalized schedule before making changes.');
            return;
        }

        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'exam_mode_id' => ['required', 'integer'],
            'shreny_id' => ['required', 'integer'],
            'section_ids' => ['required', 'array', 'min:1'],
            'section_ids.*' => ['required', 'integer', 'distinct'],
            'subject_id' => ['required', 'integer'],
            'exam_half_id' => ['required', 'integer'],
            'exam_date' => ['required', 'date'],
            'order_id' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $key = $this->combinationIds();
        [$examNameId, $examTypeId, $examPartId] = $key;
        $session = $this->activeSession();
        $schoolId = $this->activeSchoolId();
        abort_unless($session && $schoolId, 422, 'An active school session is required.');

        $this->availableModes()->findOrFail($data['exam_mode_id']);
        $sectionIds = array_map('intval', $data['section_ids']);
        $validSectionIds = $this->availableGroups()
            ->where('shreny_id', (int) $data['shreny_id'])
            ->pluck('section_id')->map(fn($id) => (int) $id)->all();
        abort_unless(!array_diff($sectionIds, $validSectionIds), 422, 'Choose active sections assigned to this Shreny.');
        abort_unless($this->availableSubjects($data['shreny_id'])->contains('id', $data['subject_id']), 422, 'Choose a subject assigned to this exam and Shreny.');
        $this->availableHalves()->findOrFail($data['exam_half_id']);

        $editing = $this->recordId !== null;
        if ($editing) {
            $this->selectedSchedules()->findOrFail($this->recordId);
        }

        unset($data['section_ids']);
        $attributes = $data + [
            'exam_name_id' => $examNameId,
            'exam_type_id' => $examTypeId,
            'exam_part_id' => $examPartId,
            'school_id' => $schoolId,
            'session_id' => $session->id,
            'is_finalized' => false,
        ];

        DB::transaction(function () use ($sectionIds, $attributes, $editing) {
            foreach ($sectionIds as $index => $sectionId) {
                $attributes['section_id'] = $sectionId;
                if ($editing && $index === 0) {
                    $this->selectedSchedules()->findOrFail($this->recordId)->update($attributes);
                } else {
                    ExamDateScheduleRecord::query()->create($attributes);
                }
            }
        });

        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', $editing ? 'Exam date schedule updated.' : 'Exam date schedule created.');
    }

    public function delete(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }
        if ($this->isFinalized()) {
            session()->flash('error', 'Reopen this finalized schedule before making changes.');
            return;
        }

        $this->selectedSchedules()->findOrFail($id)->delete();
        session()->flash('success', 'Exam date schedule deleted.');
    }

    public function finalizeSchedule(): void
    {
        if (!$this->canMutate()) {
            return;
        }
        if (!$this->selectedCombinationExists() || $this->selectedSchedules()->doesntExist()) {
            $this->addError('selectedCombinationKey', 'Add at least one schedule before finalizing.');
            return;
        }

        $this->selectedSchedules()->update(['is_finalized' => true]);
        session()->flash('success', 'Schedules finalized for this exam combination.');
    }

    public function reopenSchedule(): void
    {
        if (!$this->canMutate()) {
            return;
        }
        if (!$this->isFinalized()) {
            return;
        }

        $this->selectedSchedules()->update(['is_finalized' => false]);
        session()->flash('success', 'Schedules reopened for editing.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'recordId',
            'exam_mode_id',
            'shreny_id',
            'section_ids',
            'subject_id',
            'exam_half_id',
            'order_id',
            'name',
            'description',
            'exam_date',
            'remarks',
        ]);
        $this->is_active = true;
        $this->resetValidation();
    }

    private function combinationIds(): array
    {
        $ids = explode(':', $this->selectedCombinationKey);

        return count($ids) === 3 && collect($ids)->every(fn($id) => ctype_digit($id) && (int) $id > 0)
            ? array_map('intval', $ids)
            : [];
    }

    private function configurationQuery()
    {
        $session = $this->activeSession();
        $schoolId = $this->activeSchoolId();

        return ExamShrenyPartFmPm::query()
            ->where('is_active', true)
            ->where(function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId)->orWhereNull('school_id');
            })
            ->where(function ($query) use ($session) {
                $query->where('session_id', $session?->id)->orWhereNull('session_id');
            });
    }

    private function selectedCombinationExists(): bool
    {
        $ids = $this->combinationIds();
        if (count($ids) !== 3 || !$this->activeSession() || !$this->activeSchoolId()) {
            return false;
        }

        return $this->configurationQuery()
            ->whereNull('shreny_id')->whereNull('subject_id')
            ->where('exam_name_id', $ids[0])->where('exam_type_id', $ids[1])->where('exam_part_id', $ids[2])
            ->exists();
    }

    private function selectedSchedules()
    {
        $ids = $this->combinationIds();
        if (count($ids) !== 3 || !$this->activeSession() || !$this->activeSchoolId()) {
            return ExamDateScheduleRecord::query()->whereRaw('1 = 0');
        }

        return ExamDateScheduleRecord::query()
            ->where('school_id', $this->activeSchoolId())
            ->where('session_id', $this->activeSession()->id)
            ->where('exam_name_id', $ids[0])->where('exam_type_id', $ids[1])->where('exam_part_id', $ids[2]);
    }

    private function isFinalized(): bool
    {
        return $this->selectedSchedules()->where('is_finalized', true)->exists();
    }

    private function availableGroups()
    {
        return ShrenySection::query()
            ->where('is_active', true)
            ->where('school_id', $this->activeSchoolId())
            ->where(function ($query) {
                $query->where('session_id', $this->activeSession()?->id)->orWhereNull('session_id');
            })
            ->when($this->shreny_id, fn($query) => $query->where('shreny_id', $this->shreny_id))
            ->get();
    }

    private function availableSubjects(?int $shrenyId = null)
    {
        $ids = $this->combinationIds();
        if (count($ids) !== 3 || !$shrenyId) {
            return collect();
        }

        $subjectIds = $this->configurationQuery()
            ->where('exam_name_id', $ids[0])->where('exam_type_id', $ids[1])->where('exam_part_id', $ids[2])
            ->where('shreny_id', $shrenyId)->whereNotNull('subject_id')->pluck('subject_id')->unique();

        $scheduledSubjectIds = $this->selectedSchedules()
            ->where('shreny_id', $shrenyId)
            ->when($this->recordId, fn($query) => $query->where('id', '!=', $this->recordId))
            ->pluck('subject_id')->unique();
        if ($this->recordId) {
            $currentSubjectId = $this->selectedSchedules()->whereKey($this->recordId)->value('subject_id');
            if ($currentSubjectId) {
                $scheduledSubjectIds = $scheduledSubjectIds->reject(fn($id) => (int) $id === (int) $currentSubjectId);
            }
        }

        return Subject::query()->where('school_id', $this->activeSchoolId())
            ->where('is_active', true)->whereIn('id', $subjectIds)->whereNotIn('id', $scheduledSubjectIds)
            ->orderBy('order_id')->orderBy('name')->get();
    }

    private function availableModes()
    {
        return ExamMode::query()->where('school_id', $this->activeSchoolId())->where('is_active', true);
    }

    private function availableHalves()
    {
        if (!$this->activeSession() || !$this->activeSchoolId()) {
            return ExamHalf::query()->whereRaw('1 = 0');
        }

        return ExamHalf::query()->where('school_id', $this->activeSchoolId())
            ->where('session_id', $this->activeSession()->id)->where('is_active', true);
    }

    private function scheduleGroups()
    {
        $mappings = ShrenySection::query()->where('is_active', true)
            ->where(function ($query) {
                $query->where('school_id', $this->activeSchoolId())->orWhereNull('school_id');
            })
            ->where(function ($query) {
                $query->where('session_id', $this->activeSession()?->id)->orWhereNull('session_id');
            })->get();
        $shrenies = Shreny::query()->where('is_active', true)->get()->keyBy('id');
        $sections = Section::query()->where('is_active', true)->get()->keyBy('id');

        return $mappings->filter(fn($mapping) => $shrenies->has($mapping->shreny_id) && $sections->has($mapping->section_id))
            ->map(fn($mapping) => [
                'key' => "{$mapping->shreny_id}:{$mapping->section_id}",
                'shreny_id' => (int) $mapping->shreny_id,
                'section_id' => (int) $mapping->section_id,
                'label' => $shrenies[$mapping->shreny_id]->name . ' / ' . $sections[$mapping->section_id]->name,
                'shreny_name' => $shrenies[$mapping->shreny_id]->name,
                'section_name' => $sections[$mapping->section_id]->name,
            ])->unique('key')->values();
    }

    private function subjectStatusRows()
    {
        $ids = $this->combinationIds();
        if (count($ids) !== 3) {
            return collect();
        }

        [$examNameId, $examTypeId, $examPartId] = $ids;
        $configurations = $this->configurationQuery()
            ->where('exam_name_id', $examNameId)
            ->where('exam_type_id', $examTypeId)
            ->where('exam_part_id', $examPartId)
            ->whereNotNull('shreny_id')
            ->whereNotNull('subject_id')
            ->get();

        $shrenies = Shreny::query()->whereIn('id', $configurations->pluck('shreny_id')->unique())
            ->get()->keyBy('id');
        $subjects = Subject::query()->whereIn('id', $configurations->pluck('subject_id')->unique())
            ->get()->keyBy('id');
        $scheduled = $this->selectedSchedules()->get(['shreny_id', 'subject_id'])
            ->mapWithKeys(fn($schedule) => ["{$schedule->shreny_id}:{$schedule->subject_id}" => true]);
        $examName = ExamName::query()->find($examNameId);
        $examType = ExamType::query()->find($examTypeId);
        $examPart = ExamPart::query()->find($examPartId);

        return $configurations
            ->filter(fn($configuration) => $shrenies->has($configuration->shreny_id) && $subjects->has($configuration->subject_id))
            ->groupBy('shreny_id')
            ->map(fn($rows, $shrenyId) => [
                'exam_name' => $examName?->name ?? '',
                'exam_type' => $examType?->name ?? '',
                'shreny' => $shrenies[$shrenyId]->name,
                'exam_part' => $examPart?->name ?? '',
                'subjects' => $rows->pluck('subject_id')->unique()->map(fn($subjectId) => [
                    'id' => (int) $subjectId,
                    'name' => $subjects[$subjectId]->name,
                    'scheduled' => $scheduled->has("{$shrenyId}:{$subjectId}"),
                ])->values(),
            ])->values();
    }

    public function render()
    {
        $session = $this->activeSession();
        $configurations = $session && $this->activeSchoolId()
            ? $this->configurationQuery()->whereNull('shreny_id')->whereNull('subject_id')
                ->whereNotNull('exam_name_id')->whereNotNull('exam_type_id')->whereNotNull('exam_part_id')->get()
                ->unique(fn($row) => "{$row->exam_name_id}:{$row->exam_type_id}:{$row->exam_part_id}")
            : collect();
        $names = ExamName::query()->where('school_id', $this->activeSchoolId())->where('is_active', true)->get()->keyBy('id');
        $types = ExamType::query()->where('school_id', $this->activeSchoolId())->where('is_active', true)->get()->keyBy('id');
        $parts = ExamPart::query()->where('school_id', $this->activeSchoolId())->where('is_active', true)->get()->keyBy('id');
        $combinations = $configurations->filter(fn($row) => $names->has($row->exam_name_id) && $types->has($row->exam_type_id) && $parts->has($row->exam_part_id))
            ->map(fn($row) => [
                'key' => "{$row->exam_name_id}:{$row->exam_type_id}:{$row->exam_part_id}",
                'label' => $names[$row->exam_name_id]->name . ' / ' . $types[$row->exam_type_id]->name . ' / ' . $parts[$row->exam_part_id]->name,
            ])->values();

        $records = $this->selectedCombinationExists()
            ? $this->selectedSchedules()->with(['examMode', 'shreny', 'section', 'subject', 'examHalf'])
                ->orderBy('exam_date')->orderBy('order_id')->orderBy('name')->get()
            : collect();
        $groups = $this->scheduleGroups();
        $sections = $this->shreny_id
            ? $groups->where('shreny_id', $this->shreny_id)->unique('section_id')->values()
            : collect();

        return view('livewire.exam-date-schedule-comp', [
            'combinations' => $combinations,
            'records' => $records,
            'subjectStatusRows' => $this->selectedCombinationExists() ? $this->subjectStatusRows() : collect(),
            'groups' => $groups,
            'sections' => $sections,
            'subjects' => $this->availableSubjects($this->shreny_id),
            'modes' => $this->availableModes()->orderBy('order_id')->orderBy('name')->get(),
            'halves' => $this->availableHalves()->orderBy('order_id')->orderBy('start_time')->get(),
            'hasActiveSession' => $session !== null,
            'isFinalized' => $records->contains('is_finalized', true),
        ]);
    }
}