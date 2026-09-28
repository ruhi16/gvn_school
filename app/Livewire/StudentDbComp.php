<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\StudentDb;
use Livewire\Component;
use Livewire\WithPagination;

class StudentDbComp extends Component
{
    use WithPagination;
    use UsesActiveSchoolSession;

    public string $search = '';

    protected $queryString = ['search'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        if (!$this->canMutate()) return;
        $student = StudentDb::query()->where('school_id', $this->activeSchoolId())
            ->where('is_deleted', false)->findOrFail($id);
        $student->update(['is_deleted' => true]);
        session()->flash('success', 'Student deleted.');
    }

    public function render()
    {
        $students = StudentDb::query()
            ->where('school_id', $this->activeSchoolId())
            ->where('is_deleted', false)
            ->when($this->search, fn($query) => $query->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('fname', 'like', "%{$this->search}%")
                    ->orWhere('aadhaar_id', 'like', "%{$this->search}%")
                    ->orWhere('pen_id', 'like', "%{$this->search}%")
                    ->orWhere('mobile_1', 'like', "%{$this->search}%");
            }))
            ->latest()
            ->paginate(15);

        return view('livewire.student-db-comp', compact('students'));
    }
}