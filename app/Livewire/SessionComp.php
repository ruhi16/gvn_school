<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\Session;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SessionComp extends Component
{
    use WithPagination;
    use UsesActiveSchoolSession;

    // Component Properties
    public string $search = '', $name = '', $status = '', $start_date = '', $end_date = '', $remarks = '';
    public ?int $recordId = null;
    public ?int $order_id = null, $school_id = null;
    public bool $showModal = false, $is_active = true;

    // Lifecycle Hooks
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // Action Methods
    public function create(): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $this->resetForm();
        $this->school_id = $this->activeSchoolId();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $record = Session::query()->where('school_id', $this->activeSchoolId())->findOrFail($id);

        foreach (['name', 'status', 'remarks', 'start_date', 'end_date', 'order_id', 'school_id', 'is_active'] as $field) {
            $this->{$field} = $record->{$field} instanceof \DateTimeInterface
                ? $record->{$field}->format('Y-m-d')
                : ($record->{$field} ?? (in_array($field, ['order_id', 'school_id']) ? null : ''));
        }

        $this->recordId = $id;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $editing = $this->recordId !== null;

        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'string', 'max:255'],
            'order_id' => ['nullable', 'integer'],
            'school_id' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
            'remarks' => ['nullable', 'string', 'max:255']
        ]);

        $data['school_id'] = $this->activeSchoolId();

        DB::transaction(function () use ($data): void {
            $session = Session::updateOrCreate(
                ['id' => $this->recordId, 'school_id' => $this->activeSchoolId()],
                $data,
            );

            if ($session->is_active) {
                Session::query()
                    ->where('school_id', $this->activeSchoolId())
                    ->where('id', '!=', $session->id)
                    ->update(['is_active' => false]);
            }
        });

        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', $editing ? 'Session updated.' : 'Session created.');
    }

    public function delete(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }

        Session::query()->where('school_id', $this->activeSchoolId())->findOrFail($id)->delete();
        session()->flash('success', 'Session deleted.');
    }

    // Helper Methods
    private function resetForm(): void
    {
        $this->reset(['recordId', 'name', 'status', 'start_date', 'end_date', 'remarks', 'order_id', 'school_id']);
        $this->is_active = true;
        $this->resetValidation();
    }

    // Render Method
    public function render()
    {
        $records = Session::query()
            ->where('school_id', $this->activeSchoolId())
            ->when($this->search, fn($query) => $query->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('status', 'like', "%{$this->search}%");
            }))
            ->latest()
            ->paginate(10);

        return view('livewire.session-comp', ['records' => $records]);
    }
}
