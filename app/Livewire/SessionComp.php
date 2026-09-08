<?php

namespace App\Livewire;

use App\Models\Session;
use Livewire\Component;
use Livewire\WithPagination;

class SessionComp extends Component
{
    use WithPagination;

    public string $search = '', $name = '', $status = '', $start_date = '', $end_date = '', $remarks = '';
    public ?int $recordId = null;
    public ?int $order_id = null, $school_id = null, $session_id = null;
    public bool $showModal = false, $is_active = true;

    public function updatedSearch(): void { $this->resetPage(); }
    public function create(): void { $this->resetForm(); $this->showModal = true; }
    public function edit(int $id): void
    {
        $record = Session::findOrFail($id);
        foreach (['name', 'status', 'remarks', 'start_date', 'end_date', 'order_id', 'school_id', 'session_id', 'is_active'] as $field) {
            $this->{$field} = $record->{$field} instanceof \DateTimeInterface ? $record->{$field}->format('Y-m-d') : ($record->{$field} ?? (in_array($field, ['order_id', 'school_id', 'session_id']) ? null : ''));
        }
        $this->recordId = $id; $this->resetValidation(); $this->showModal = true;
    }
    public function save(): void
    {
        $editing = $this->recordId !== null;
        $data = $this->validate(['name' => ['required', 'string', 'max:255'], 'start_date' => ['nullable', 'date'], 'end_date' => ['nullable', 'date', 'after_or_equal:start_date'], 'status' => ['nullable', 'string', 'max:255'], 'order_id' => ['nullable', 'integer'], 'school_id' => ['nullable', 'integer'], 'session_id' => ['nullable', 'integer'], 'is_active' => ['boolean'], 'remarks' => ['nullable', 'string', 'max:255']]);
        Session::updateOrCreate(['id' => $this->recordId], $data); $this->showModal = false; $this->resetForm(); session()->flash('success', $editing ? 'Session updated.' : 'Session created.');
    }
    public function delete(int $id): void { Session::findOrFail($id)->delete(); session()->flash('success', 'Session deleted.'); }
    private function resetForm(): void { $this->reset(['recordId', 'name', 'status', 'start_date', 'end_date', 'remarks', 'order_id', 'school_id', 'session_id']); $this->is_active = true; $this->resetValidation(); }
    public function render() { $records = Session::query()->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('status', 'like', "%{$this->search}%"))->latest()->paginate(10); return view('livewire.session-comp', ['records' => $records]); }
}
