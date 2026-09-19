<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\ExamGrade;
use Livewire\Component;
use Livewire\WithPagination;

class ExamGradeComp extends Component
{
    use UsesActiveSchoolSession;
    use WithPagination;

    public string $search = '', $name = '', $description = '', $remarks = '';
    public ?int $recordId = null, $order_id = null, $school_id = null;
    public bool $showModal = false, $is_active = true;

    public function updatedSearch(): void { $this->resetPage(); }
    public function create(): void { if (!$this->canMutate()) return; $this->resetForm(); $this->showModal = true; }
    public function edit(int $id): void { if (!$this->canMutate()) return; $record = ExamGrade::findOrFail($id); foreach (['name', 'description', 'remarks', 'order_id', 'school_id', 'is_active'] as $field) $this->{$field} = $record->{$field} ?? (in_array($field, ['order_id', 'school_id']) ? null : ''); $this->recordId = $id; $this->resetValidation(); $this->showModal = true; }
    public function save(): void { if (!$this->canMutate()) return; $editing = $this->recordId !== null; $data = $this->validate(['name' => ['required', 'string', 'max:255'], 'description' => ['nullable', 'string', 'max:255'], 'order_id' => ['nullable', 'integer'], 'school_id' => ['nullable', 'integer'], 'is_active' => ['boolean'], 'remarks' => ['nullable', 'string', 'max:255']]); ExamGrade::updateOrCreate(['id' => $this->recordId], $data); $this->showModal = false; $this->resetForm(); session()->flash('success', $editing ? 'Exam grade updated.' : 'Exam grade created.'); }
    public function delete(int $id): void { if (!$this->canMutate()) return; ExamGrade::findOrFail($id)->delete(); session()->flash('success', 'Exam grade deleted.'); }
    private function resetForm(): void { $this->reset(['recordId', 'name', 'description', 'remarks', 'order_id', 'school_id']); $this->is_active = true; $this->resetValidation(); }
    public function render() { $records = ExamGrade::query()->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('description', 'like', "%{$this->search}%"))->orderBy('order_id')->orderBy('name')->paginate(10); return view('livewire.exam-grade-comp', ['records' => $records]); }
}
