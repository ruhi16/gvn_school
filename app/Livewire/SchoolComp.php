<?php

namespace App\Livewire;

use App\Models\School;
use Livewire\Component;
use Livewire\WithPagination;

class SchoolComp extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public ?int $schoolId = null;
    public string $name = '';
    public string $dise_code = '';
    public string $udise_code = '';
    public string $school_type = '';
    public string $vill = '';
    public string $post_office = '';
    public string $police_station = '';
    public string $district = '';
    public string $block = '';
    public string $pincode = '';
    public bool $is_active = true;
    public string $remarks = '';

    protected $queryString = ['search'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $school = School::findOrFail($id);
        $this->schoolId = $school->id;
        $this->name = $school->name;
        $this->dise_code = $school->dise_code ?? '';
        $this->udise_code = $school->udise_code ?? '';
        $this->school_type = $school->school_type ?? '';
        $this->vill = $school->vill ?? '';
        $this->post_office = $school->post_office ?? '';
        $this->police_station = $school->police_station ?? '';
        $this->district = $school->district ?? '';
        $this->block = $school->block ?? '';
        $this->pincode = $school->pincode ?? '';
        $this->is_active = $school->is_active;
        $this->remarks = $school->remarks ?? '';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $isEditing = $this->schoolId !== null;
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'dise_code' => ['nullable', 'string', 'max:255'],
            'udise_code' => ['nullable', 'string', 'max:255'],
            'school_type' => ['nullable', 'string', 'max:255'],
            'vill' => ['nullable', 'string', 'max:255'],
            'post_office' => ['nullable', 'string', 'max:255'],
            'police_station' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'block' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        School::updateOrCreate(['id' => $this->schoolId], $validated);
        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', $isEditing ? 'School updated.' : 'School created.');
    }

    public function delete(int $id): void
    {
        School::findOrFail($id)->delete();
        session()->flash('success', 'School deleted.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'schoolId', 'name', 'dise_code', 'udise_code', 'school_type', 'vill',
            'post_office', 'police_station', 'district', 'block', 'pincode', 'remarks',
        ]);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $schools = School::query()
            ->when($this->search, fn ($query) => $query->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('district', 'like', "%{$this->search}%")
                    ->orWhere('udise_code', 'like', "%{$this->search}%");
            }))
            ->latest()
            ->paginate(10);

        return view('livewire.school-comp', compact('schools'));
    }
}
