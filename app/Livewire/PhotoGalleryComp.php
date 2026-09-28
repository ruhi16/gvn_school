<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\PhotoGallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class PhotoGalleryComp extends Component
{
    use UsesActiveSchoolSession;
    use WithFileUploads;
    use WithPagination;

    public string $search = '';
    public ?int $recordId = null;
    public ?int $selectedCategoryId = null;
    public string $name = '';
    public string $description = '';
    public string $image_caption = '';
    public string $image_alt_text = '';
    public $image;
    public bool $is_featured = false;
    public bool $is_active = true;
    public bool $showModal = false;
    public bool $showCategoryModal = false;
    public string $category_name = '';
    public string $category_description = '';

    protected $queryString = ['search'];

    public function create(): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $photo = $this->photoQuery()->findOrFail($id);
        $this->recordId = $photo->id;
        $this->selectedCategoryId = $photo->category_id;
        $this->name = $photo->name;
        $this->description = $photo->description ?? '';
        $this->image_caption = $photo->image_caption ?? '';
        $this->image_alt_text = $photo->image_alt_text ?? '';
        $this->is_featured = (bool) $photo->is_featured;
        $this->is_active = (bool) $photo->is_active;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        if (!$this->canMutate()) {
            return;
        }
        if (!$this->activeSession()) {
            session()->flash('error', 'Configure an active school session before managing gallery photos.');
            return;
        }

        $editing = $this->recordId !== null;
        $data = $this->validate([
            'selectedCategoryId' => ['required', 'integer', Rule::exists('photo_galleries', 'id')->whereNull('category_id')->whereNotNull('category_name')->where('is_deleted', false)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'image_caption' => ['nullable', 'string', 'max:255'],
            'image_alt_text' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:10240'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        if (!$editing && !$this->image) {
            $this->addError('image', 'Choose an image to upload.');
            return;
        }

        $category = $this->categoryQuery()->findOrFail($this->selectedCategoryId);
        $photo = $editing ? $this->photoQuery()->findOrFail($this->recordId) : new PhotoGallery();
        $attributes = [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'category_id' => $category->id,
            'category_name' => $category->category_name,
            'category_slug' => $category->category_slug,
            'category_description' => $category->category_description,
            'image_caption' => $data['image_caption'] ?? null,
            'image_alt_text' => $data['image_alt_text'] ?? null,
            'is_featured' => $data['is_featured'],
            'is_active' => $data['is_active'],
            'school_id' => $this->activeSchoolId(),
            'session_id' => $this->activeSession()->id,
            'is_deleted' => false,
        ];

        if ($this->image) {
            $newPath = $this->image->store('photo-gallery', 'public');
            if ($photo->image_path) {
                Storage::disk('public')->delete($photo->image_path);
            }
            $attributes['image_path'] = $newPath;
        }

        $photo->fill($attributes)->save();
        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', $editing ? 'Photo updated.' : 'Photo added.');
    }

    public function openCategoryModal(): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $this->resetValidation();
        $this->category_name = '';
        $this->category_description = '';
        $this->showCategoryModal = true;
    }

    public function saveCategory(): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $data = $this->validate([
            'category_name' => [
                'required', 'string', 'max:255',
                Rule::unique('photo_galleries', 'category_name')->whereNull('category_id')->where('is_deleted', false),
            ],
            'category_description' => ['nullable', 'string', 'max:255'],
        ]);

        PhotoGallery::create([
            'name' => $data['category_name'],
            'category_name' => $data['category_name'],
            'category_slug' => Str::slug($data['category_name']),
            'category_description' => $data['category_description'] ?? null,
            'is_active' => true,
        ]);

        $this->showCategoryModal = false;
        $this->selectedCategoryId = $this->categoryQuery()->where('category_name', $data['category_name'])->value('id');
        $this->resetValidation();
        session()->flash('success', 'Photo category added.');
    }

    public function toggleActive(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $photo = $this->photoQuery()->findOrFail($id);
        $photo->update(['is_active' => !$photo->is_active]);
    }

    public function delete(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $photo = $this->photoQuery()->findOrFail($id);
        Storage::disk('public')->delete($photo->image_path);
        $photo->delete();
        session()->flash('success', 'Photo deleted.');
    }

    private function categoryQuery()
    {
        return PhotoGallery::query()->whereNull('category_id')->whereNotNull('category_name')
            ->where('is_deleted', false)->where('is_active', true);
    }

    private function photoQuery()
    {
        return PhotoGallery::query()->whereNotNull('category_id')->where('school_id', $this->activeSchoolId())
            ->where('session_id', $this->activeSession()?->id)->where('is_deleted', false);
    }

    private function resetForm(): void
    {
        $this->reset(['recordId', 'selectedCategoryId', 'name', 'description', 'image_caption', 'image_alt_text', 'image']);
        $this->is_featured = false;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $categories = $this->categoryQuery()->orderBy('category_name')->get();
        $photos = $this->photoQuery()
            ->when($this->search, fn ($query) => $query->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('category_name', 'like', "%{$this->search}%")
                    ->orWhere('image_caption', 'like', "%{$this->search}%");
            }))
            ->orderBy('category_name')->orderBy('order_id')->orderByDesc('id')->paginate(12);

        return view('livewire.photo-gallery-comp', compact('categories', 'photos'));
    }
}