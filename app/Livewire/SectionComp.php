<?php

namespace App\Livewire;

use App\Models\Section;
use Livewire\Component;
use Livewire\WithPagination;

class SectionComp extends Component
{
    use WithPagination;

    public string $search = '', $name = '', $desc = '', $remarks = '';
    public ?int $recordId = null, $order_id = null, $school_id = null, $session_id = null;
    public bool $showModal = false, $is_active = true;

    public function updatedSearch(): void { $this->resetPage(); }
    public function create(): void { $this->resetForm(); $this->showModal = true; }
    public function edit(int $id): void { $record = Section::findOrFail($id); foreach (['name', 'desc', 'remarks', 'order_id', 'school_id', 'session_id', 'is_active'] as $field) $this->{$field} = $record->{$field} ?? (in_array($field, ['order_id', 'school_id', 'session_id']) ? null : ''); $this->recordId = $id; $this->resetValidation(); $this->showModal = true; }
    public function save(): void { $editing = $this->recordId !== null; $data = $this->validate(['name' => ['required', 'string', 'max:255'], 'desc' => ['nullable', 'string', 'max:255'], 'order_id' => ['nullable', 'integer'], 'school_id' => ['nullable', 'integer'], 'session_id' => ['nullable', 'integer'], 'is_active' => ['boolean'], 'remarks' => ['nullable', 'string', 'max:255']]); Section::updateOrCreate(['id' => $this->recordId], $data); $this->showModal = false; $this->resetForm(); session()->flash('success', $editing ? 'Section updated.' : 'Section created.'); }
    public function delete(int $id): void { Section::findOrFail($id)->delete(); session()->flash('success', 'Section deleted.'); }
    private function resetForm(): void { $this->reset(['recordId', 'name', 'desc', 'remarks', 'order_id', 'school_id', 'session_id']); $this->is_active = true; $this->resetValidation(); }
    public function render() { $records = Section::query()->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))->latest()->paginate(10); return view('livewire.section-comp', ['records' => $records]); }
}
