<?php

namespace App\Livewire;

use App\Models\StudentDb;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class StudentDbComp extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $student = StudentDb::findOrFail($id);

        foreach (['dp_img_ref', 'dob_cert_img_ref', 'aadhaar_img_ref'] as $column) {
            if ($student->{$column}) {
                Storage::disk('public')->delete($student->{$column});
            }
        }

        $student->delete();
        session()->flash('success', 'Student deleted.');
    }

    public function render()
    {
        $students = StudentDb::query()
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