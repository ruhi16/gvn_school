<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\Room;
use Livewire\Component;
use Livewire\WithPagination;

class RoomComp extends Component
{
    use UsesActiveSchoolSession;
    use WithPagination;

    private const FLOORS = ['Ground', 'First', 'Second', 'Third', 'Fourth', 'Other'];
    private const ROOM_TYPES = ['Classroom', 'Laboratory', 'Library', 'Auditorium', 'Other'];
    private const ROOM_STATUSES = ['Available', 'Occupied', 'Under Maintenance', 'Closed'];
    private const CONDITIONS = ['Good', 'Needs Repair', 'Under Renovation'];

    public string $search = '', $name = '', $description = '', $remarks = '';
    public ?string $floor = null, $room_type = null, $room_status = null, $room_condition = null;
    public ?int $recordId = null, $no_of_benches = null, $no_of_students_per_bench = null, $no_of_students_total = null;
    public bool $showModal = false, $is_active = true;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        if (!$this->canMutate()) return;
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        if (!$this->canMutate()) return;
        $record = $this->roomQuery()->findOrFail($id);
        foreach (['name', 'description', 'floor', 'room_type', 'room_status', 'room_condition', 'no_of_benches', 'no_of_students_per_bench', 'no_of_students_total', 'remarks', 'is_active'] as $field) {
            $this->{$field} = $record->{$field};
        }
        $this->recordId = $record->id;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        if (!$this->canMutate()) return;

        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'floor' => ['nullable', 'in:' . implode(',', self::FLOORS)],
            'room_type' => ['nullable', 'in:' . implode(',', self::ROOM_TYPES)],
            'room_status' => ['nullable', 'in:' . implode(',', self::ROOM_STATUSES)],
            'room_condition' => ['nullable', 'in:' . implode(',', self::CONDITIONS)],
            'no_of_benches' => ['nullable', 'integer', 'min:0'],
            'no_of_students_per_bench' => ['nullable', 'integer', 'min:1'],
            'no_of_students_total' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['boolean'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);
        $data['school_id'] = $this->activeSchoolId();
        $data['session_id'] = $this->activeSession()?->id;

        $this->roomQuery()->updateOrCreate(['id' => $this->recordId], $data);
        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', 'Room saved.');
    }

    public function delete(int $id): void
    {
        if (!$this->canMutate()) return;
        $this->roomQuery()->findOrFail($id)->delete();
        session()->flash('success', 'Room deleted.');
    }

    private function roomQuery()
    {
        return Room::query()->where('school_id', $this->activeSchoolId());
    }

    private function resetForm(): void
    {
        $this->reset(['recordId', 'name', 'description', 'floor', 'room_type', 'room_status', 'room_condition', 'no_of_benches', 'no_of_students_per_bench', 'no_of_students_total', 'remarks']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $records = $this->roomQuery()
            ->when($this->search, fn ($query) => $query->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('room_type', 'like', "%{$this->search}%");
            }))
            ->orderBy('name')->paginate(10);

        return view('livewire.room-comp', [
            'records' => $records,
            'floors' => self::FLOORS,
            'roomTypes' => self::ROOM_TYPES,
            'roomStatuses' => self::ROOM_STATUSES,
            'conditions' => self::CONDITIONS,
        ]);
    }
}