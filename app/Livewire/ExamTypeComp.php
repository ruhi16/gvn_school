<?php

namespace App\Livewire;

use App\Models\ExamType;
use Livewire\Component;
use Livewire\WithPagination;

class ExamTypeComp extends Component
{
    use WithPagination;

    public string $search = '', $name = '', $description = '', $remarks = '';
    public ?int $recordId = null, $order_id = null, $school_id = null;
    public bool $showModal = false, $is_active = true;

    public function updatedSearch(): void { $this->resetPage(); }
    public function create(): void { $this->resetForm(); $this->showModal = true; }
    public function edit(int $id): void { $record = ExamType::findOrFail($id); foreach (['name', 'description', 'remarks', 'order_id', 'school_id', 'is_active'] as $field) $this->{$field} = $record->{$field} ?? (in_array($field, ['order_id', 'school_id']) ? null : ''); $this->recordId = $id; $this->resetValidation(); $this->showModal = true; }
    public function save(): void { $editing = $this->recordId !== null; $data = $this->validate(['name' => ['required', 'string', 'max:255'], 'description' => ['nullable', 'string', 'max:255'], 'order_id' => ['nullable', 'integer'], 'school_id' => ['nullable', 'integer'], 'is_active' => ['boolean'], 'remarks' => ['nullable', 'string', 'max:255']]); ExamType::updateOrCreate(['id' => $this->recordId], $data); $this->showModal = false; $this->resetForm(); session()->flash('success', $editing ? 'Exam type updated.' : 'Exam type created.'); }
    public function delete(int $id): void { ExamType::findOrFail($id)->delete(); session()->flash('success', 'Exam type deleted.'); }
    private function resetForm(): void { $this->reset(['recordId', 'name', 'description', 'remarks', 'order_id', 'school_id']); $this->is_active = true; $this->resetValidation(); }
    public function render() { $records = ExamType::query()->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('description', 'like', "%{$this->search}%"))->orderBy('order_id')->orderBy('name')->paginate(10); return view('livewire.exam-type-comp', ['records' => $records]); }
}
