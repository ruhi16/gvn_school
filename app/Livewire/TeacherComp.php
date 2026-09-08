<?php

namespace App\Livewire;

use App\Models\Teacher;
use Livewire\Component;
use Livewire\WithPagination;

class TeacherComp extends Component
{
    use WithPagination;

    public string $search = '', $name = '', $desc = '', $email = '', $mobile = '', $high_qual = '', $high_qual_subject = '', $prof_qual = '', $prof_qual_subject = '', $vill = '', $post_office = '', $police_station = '', $district = '', $block = '', $pincode = '', $remarks = '';
    public ?int $recordId = null, $order_id = null, $school_id = null, $session_id = null;
    public bool $showModal = false, $is_active = true;

    public function updatedSearch(): void { $this->resetPage(); }
    public function create(): void { $this->resetForm(); $this->showModal = true; }
    public function edit(int $id): void { $record = Teacher::findOrFail($id); foreach (['name', 'desc', 'email', 'mobile', 'high_qual', 'high_qual_subject', 'prof_qual', 'prof_qual_subject', 'vill', 'post_office', 'police_station', 'district', 'block', 'pincode', 'remarks', 'order_id', 'school_id', 'session_id', 'is_active'] as $field) $this->{$field} = $record->{$field} ?? (in_array($field, ['order_id', 'school_id', 'session_id']) ? null : ''); $this->recordId = $id; $this->resetValidation(); $this->showModal = true; }
    public function save(): void { $editing = $this->recordId !== null; $data = $this->validate(['name' => ['required', 'string', 'max:255'], 'email' => ['nullable', 'email', 'max:255'], 'mobile' => ['nullable', 'string', 'max:255'], 'high_qual' => ['nullable', 'in:Secondary,Higer Secondary,Bachelor,Master,PhD'], 'high_qual_subject' => ['nullable', 'string', 'max:255'], 'prof_qual' => ['nullable', 'in:BEd,Med,Ph Ed,Other'], 'prof_qual_subject' => ['nullable', 'string', 'max:255'], 'desc' => ['nullable', 'string', 'max:255'], 'vill' => ['nullable', 'string', 'max:255'], 'post_office' => ['nullable', 'string', 'max:255'], 'police_station' => ['nullable', 'string', 'max:255'], 'district' => ['nullable', 'string', 'max:255'], 'block' => ['nullable', 'string', 'max:255'], 'pincode' => ['nullable', 'string', 'max:255'], 'order_id' => ['nullable', 'integer'], 'school_id' => ['nullable', 'integer'], 'session_id' => ['nullable', 'integer'], 'is_active' => ['boolean'], 'remarks' => ['nullable', 'string', 'max:255']]); Teacher::updateOrCreate(['id' => $this->recordId], $data); $this->showModal = false; $this->resetForm(); session()->flash('success', $editing ? 'Teacher updated.' : 'Teacher created.'); }
    public function delete(int $id): void { Teacher::findOrFail($id)->delete(); session()->flash('success', 'Teacher deleted.'); }
    private function resetForm(): void { $this->reset(['recordId', 'name', 'desc', 'email', 'mobile', 'high_qual', 'high_qual_subject', 'prof_qual', 'prof_qual_subject', 'vill', 'post_office', 'police_station', 'district', 'block', 'pincode', 'remarks', 'order_id', 'school_id', 'session_id']); $this->is_active = true; $this->resetValidation(); }
    public function render() { $records = Teacher::query()->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%"))->latest()->paginate(10); return view('livewire.teacher-comp', ['records' => $records]); }
}
