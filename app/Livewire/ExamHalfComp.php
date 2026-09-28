<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\ExamHalf;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ExamHalfComp extends Component
{
    use UsesActiveSchoolSession;

    private const DAYS = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    ];

    public string $name = '';
    public string $description = '';
    public string $start_time = '';
    public string $end_time = '';
    public array $active_exam_days = [];
    public string $remarks = '';
    public ?int $recordId = null;
    public ?int $order_id = null;
    public bool $showModal = false;
    public bool $is_active = true;

    public function create(): void
    {
        if (!$this->canMutate()) {
            return;
        }

        if (!$this->activeSession() || !$this->activeSchoolId()) {
            session()->flash('error', 'An active school session is required.');
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

        $record = $this->selectedHalves()->findOrFail($id);
        $this->recordId = $record->id;
        $this->name = $record->name;
        $this->description = $record->description ?? '';
        $this->start_time = $record->start_time ? substr((string) $record->start_time, 0, 5) : '';
        $this->end_time = $record->end_time ? substr((string) $record->end_time, 0, 5) : '';
        $this->active_exam_days = $record->active_exam_days ?? [];
        $this->remarks = $record->remarks ?? '';
        $this->order_id = $record->order_id;
        $this->is_active = $record->is_active;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        if (!$this->canMutate()) {
            return;
        }

        if (!$this->activeSession() || !$this->activeSchoolId()) {
            $this->addError('name', 'An active school session is required.');
            return;
        }

        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'active_exam_days' => ['required', 'array', 'min:1'],
            'active_exam_days.*' => ['required', Rule::in(self::DAYS)],
            'order_id' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        if (($data['start_time'] === null) !== ($data['end_time'] === null)) {
            $this->addError('end_time', 'Enter both start and end times, or leave both empty.');
            return;
        }

        $session = $this->activeSession();
        if (!$session || !$this->activeSchoolId()) {
            $this->addError('name', 'An active school session is required.');
            return;
        }

        $editing = $this->recordId !== null;
        if ($editing) {
            $this->selectedHalves()->findOrFail($this->recordId);
        }

        ExamHalf::query()->updateOrCreate(
            ['id' => $this->recordId],
            $data + [
                'school_id' => $this->activeSchoolId(),
                'session_id' => $session->id,
            ]
        );

        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', $editing ? 'Exam half updated.' : 'Exam half created.');
    }

    public function delete(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $this->selectedHalves()->findOrFail($id)->delete();
        session()->flash('success', 'Exam half deleted.');
    }

    public function getDurationPreviewProperty(): ?string
    {
        if ($this->start_time === '' || $this->end_time === '') {
            return null;
        }

        return $this->formatDuration($this->start_time, $this->end_time);
    }

    public function formatDuration(?string $startTime, ?string $endTime): string
    {
        if (!$startTime || !$endTime) {
            return '—';
        }

        [$startHour, $startMinute] = array_map('intval', explode(':', substr($startTime, 0, 5)));
        [$endHour, $endMinute] = array_map('intval', explode(':', substr($endTime, 0, 5)));
        $minutes = ($endHour * 60 + $endMinute) - ($startHour * 60 + $startMinute);
        if ($minutes < 0) {
            $minutes += 24 * 60;
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return trim((($hours ? "{$hours} hr" . ($hours === 1 ? '' : 's') : '')
            . ($hours && $remainingMinutes ? ' ' : '')
            . ($remainingMinutes ? "{$remainingMinutes} min" : '')
        ) ?: '0 min');
    }

    private function resetForm(): void
    {
        $this->reset(['recordId', 'name', 'description', 'start_time', 'end_time', 'active_exam_days', 'remarks', 'order_id']);
        $this->is_active = true;
        $this->resetValidation();
    }

    private function selectedHalves()
    {
        if (!$this->activeSession() || !$this->activeSchoolId()) {
            return ExamHalf::query()->whereRaw('1 = 0');
        }

        return ExamHalf::query()
            ->where('school_id', $this->activeSchoolId())
            ->where('session_id', $this->activeSession()->id);
    }

    public function render()
    {
        $session = $this->activeSession();
        $records = $this->selectedHalves()->orderBy('order_id')->orderBy('start_time')->orderBy('name')->get();

        return view('livewire.exam-half-comp', [
            'records' => $records,
            'days' => self::DAYS,
            'durationPreview' => $this->durationPreview,
            'hasActiveSession' => $session !== null,
            'activeSessionName' => $session?->name,
        ]);
    }
}