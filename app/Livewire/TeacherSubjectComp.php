<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubjects;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class TeacherSubjectComp extends Component
{
    use UsesActiveSchoolSession;
    use WithPagination;

    public string $search = '';
    public ?int $recordId = null;
    public ?int $teacher_id = null;
    public ?int $subject_id = null;
    public ?int $order_id = null;
    public ?string $subject_type = null;
    public string $remarks = '';
    public bool $showModal = false;
    public bool $is_active = true;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        if (!$this->canMutate()) {
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

        $record = $this->recordsQuery()->findOrFail($id);
        $this->recordId = $record->id;
        $this->teacher_id = $record->teacher_id;
        $this->subject_id = $record->subject_id;
        $this->order_id = $record->order_id;
        $this->subject_type = $record->subject_type;
        $this->remarks = $record->remarks ?? '';
        $this->is_active = $record->is_active;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $editing = $this->recordId !== null;
        $schoolId = $this->activeSchoolId();
        $data = $this->validate([
            'teacher_id' => ['required', 'integer', Rule::exists('teachers', 'id')->where('school_id', $schoolId)],
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')->where('school_id', $schoolId)],
            'order_id' => ['nullable', 'integer'],
            'subject_type' => ['required', Rule::in(['main_subject', 'secondary_subject', 'additional_subject', 'other'])],
            'is_active' => ['boolean'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $data['school_id'] = $schoolId;
        $data['session_id'] = $this->activeSession()?->id;
        $data['is_deleted'] = false;

        TeacherSubjects::updateOrCreate(['id' => $this->recordId], $data);
        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', $editing ? 'Teacher subject updated.' : 'Teacher subject created.');
    }

    public function delete(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $record = $this->recordsQuery()->findOrFail($id);
        $record->update(['is_deleted' => true, 'is_active' => false]);
        session()->flash('success', 'Teacher subject deleted.');
    }

    private function resetForm(): void
    {
        $this->reset(['recordId', 'teacher_id', 'subject_id', 'order_id', 'subject_type', 'remarks']);
        $this->is_active = true;
        $this->resetValidation();
    }

    private function recordsQuery()
    {
        return $this->scopeSchoolSession(TeacherSubjects::query())
            ->where('is_deleted', false);
    }

    public function render()
    {
        $schoolId = $this->activeSchoolId();
        $records = $this->recordsQuery()
            ->with(['teacher', 'subject'])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->whereHas('teacher', fn ($teacher) => $teacher->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('subject', fn ($subject) => $subject->where('name', 'like', "%{$this->search}%"));
                });
            })
            ->orderBy('order_id')
            ->latest('id')
            ->paginate(10);

        $teachers = Teacher::query()->where('school_id', $schoolId)->where('is_active', true)->orderBy('name')->get();
        $subjects = Subject::query()->where('school_id', $schoolId)->where('is_active', true)->orderBy('name')->get();

        return view('livewire.teacher-subject-comp', compact('records', 'teachers', 'subjects'));
    }
}