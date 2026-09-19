<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;

class SubjectComp extends Component
{
    use UsesActiveSchoolSession;
    use WithPagination;

    public string $search = '', $name = '', $short_name = '', $desc = '', $remarks = '';
    public ?int $recordId = null, $order_id = null, $school_id = null, $session_id = null;
    public bool $showModal = false, $is_active = true;

    public function updatedSearch(): void { $this->resetPage(); }
    public function create(): void { $this->resetForm(); $this->showModal = true; }
    public function edit(int $id): void { $record = Subject::findOrFail($id); foreach (['name', 'short_name', 'desc', 'remarks', 'order_id', 'school_id', 'session_id', 'is_active'] as $field) $this->{$field} = $record->{$field} ?? (in_array($field, ['order_id', 'school_id', 'session_id']) ? null : ''); $this->recordId = $id; $this->resetValidation(); $this->showModal = true; }
    public function save(): void { $editing = $this->recordId !== null; $data = $this->validate(['name' => ['required', 'string', 'max:255'], 'short_name' => ['required', 'string', 'max:255'], 'desc' => ['nullable', 'string', 'max:255'], 'order_id' => ['nullable', 'integer'], 'school_id' => ['nullable', 'integer'], 'session_id' => ['nullable', 'integer'], 'is_active' => ['boolean'], 'remarks' => ['nullable', 'string', 'max:255']]); Subject::updateOrCreate(['id' => $this->recordId], $data); $this->showModal = false; $this->resetForm(); session()->flash('success', $editing ? 'Subject updated.' : 'Subject created.'); }
    public function delete(int $id): void { Subject::findOrFail($id)->delete(); session()->flash('success', 'Subject deleted.'); }
    private function resetForm(): void { $this->reset(['recordId', 'name', 'short_name', 'desc', 'remarks', 'order_id', 'school_id', 'session_id']); $this->is_active = true; $this->resetValidation(); }
    public function render() { $records = Subject::query()->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('short_name', 'like', "%{$this->search}%"))->latest()->paginate(10); return view('livewire.subject-comp', ['records' => $records]); }
}
