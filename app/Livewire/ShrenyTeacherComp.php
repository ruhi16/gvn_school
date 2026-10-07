<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\Shreny;
use App\Models\ShrenyTeachers;
use App\Models\Teacher;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ShrenyTeacherComp extends Component
{
    use UsesActiveSchoolSession;
    use WithPagination;

    public string $search = '';
    public ?int $recordId = null;
    public ?int $shreny_id = null;
    public ?int $teacher_id = null;
    public ?int $order_id = null;
    public ?string $teacher_type = null;
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
        $this->shreny_id = $record->shreny_id;
        $this->teacher_id = $record->teacher_id;
        $this->order_id = $record->order_id;
        $this->teacher_type = $record->teacher_type;
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
            'shreny_id' => ['required', 'integer', Rule::exists('shrenies', 'id')->where('school_id', $schoolId)],
            'teacher_id' => ['required', 'integer', Rule::exists('teachers', 'id')->where('school_id', $schoolId)],
            'order_id' => ['nullable', 'integer'],
            'teacher_type' => ['required', Rule::in(['class_teacher', 'subject_teacher', 'other'])],
            'is_active' => ['boolean'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $data['school_id'] = $schoolId;
        $data['session_id'] = $this->activeSession()?->id;
        $data['is_deleted'] = false;

        ShrenyTeachers::updateOrCreate(['id' => $this->recordId], $data);
        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', $editing ? 'Shreny teacher updated.' : 'Shreny teacher created.');
    }

    public function delete(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $record = $this->recordsQuery()->findOrFail($id);
        $record->update(['is_deleted' => true, 'is_active' => false]);
        session()->flash('success', 'Shreny teacher deleted.');
    }

    private function resetForm(): void
    {
        $this->reset(['recordId', 'shreny_id', 'teacher_id', 'order_id', 'teacher_type', 'remarks']);
        $this->is_active = true;
        $this->resetValidation();
    }

    private function recordsQuery()
    {
        return $this->scopeSchoolSession(ShrenyTeachers::query())
            ->where('is_deleted', false);
    }

    public function render()
    {
        $schoolId = $this->activeSchoolId();
        $records = $this->recordsQuery()
            ->with(['shreny', 'teacher'])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->whereHas('shreny', fn ($shreny) => $shreny->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('teacher', fn ($teacher) => $teacher->where('name', 'like', "%{$this->search}%"));
                });
            })
            ->orderBy('order_id')
            ->latest('id')
            ->paginate(10);

        $shrenies = Shreny::query()->where('school_id', $schoolId)->where('is_active', true)->orderBy('order_id')->orderBy('name')->get();
        $teachers = Teacher::query()->where('school_id', $schoolId)->where('is_active', true)->orderBy('name')->get();

        return view('livewire.shreny-teacher-comp', compact('records', 'shrenies', 'teachers'));
    }
}