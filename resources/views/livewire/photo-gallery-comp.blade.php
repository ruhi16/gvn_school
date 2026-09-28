<div class="space-y-4">
    <header class="flex flex-wrap items-end justify-between gap-3 border-b border-slate-200 pb-4">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-cyan-700">School media</p>
            <h2 class="mt-1 text-lg font-semibold text-slate-950">Photo gallery</h2>
            <p class="mt-1 text-xs text-slate-500">Photos are published to the welcome page by category.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search photos..." class="w-48 rounded-md border-slate-300 px-3 py-2 text-xs">
            <button type="button" wire:click="toggleMutations" role="switch" aria-checked="{{ $mutationsEnabled ? 'true' : 'false' }}" class="rounded border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">{{ $mutationsEnabled ? 'Editing enabled' : 'Enable editing' }}</button>
            <button type="button" wire:click="openCategoryModal" @disabled(!$mutationsEnabled) class="rounded-md border border-cyan-700 px-3 py-2 text-xs font-semibold text-cyan-800 disabled:opacity-50">Add category</button>
            <button type="button" wire:click="create" @disabled(!$mutationsEnabled || $categories->isEmpty()) class="rounded-md bg-cyan-700 px-3 py-2 text-xs font-semibold text-white disabled:opacity-50">Add photo</button>
        </div>
    </header>

    @if (session('success'))<div role="status" class="rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800">{{ session('success') }}</div>@endif
    @if (session('error'))<div role="alert" class="rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">{{ session('error') }}</div>@endif
    @if ($categories->isEmpty())
        <div class="rounded-md border border-dashed border-slate-300 bg-white px-4 py-8 text-center text-sm text-slate-500">Add a category before uploading photos.</div>
    @elseif ($photos->isEmpty())
        <div class="rounded-md border border-dashed border-slate-300 bg-white px-4 py-8 text-center text-sm text-slate-500">No photos found for this session.</div>
    @else
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($photos as $photo)
                <article wire:key="gallery-photo-{{ $photo->id }}" class="overflow-hidden rounded-md border border-slate-200 bg-white">
                    <img src="{{ Storage::url($photo->image_path) }}" alt="{{ $photo->image_alt_text ?: $photo->name }}" class="h-48 w-full object-cover">
                    <div class="space-y-2 p-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0"><h3 class="truncate text-sm font-semibold text-slate-900">{{ $photo->name }}</h3><p class="text-xs text-slate-500">{{ $photo->category_name }}</p></div>
                            <span class="shrink-0 rounded px-2 py-1 text-[10px] font-semibold {{ $photo->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $photo->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                        @if ($photo->image_caption)<p class="line-clamp-2 text-xs text-slate-600">{{ $photo->image_caption }}</p>@endif
                        <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-2 text-xs font-semibold">
                            <button type="button" wire:click="toggleActive({{ $photo->id }})" @disabled(!$mutationsEnabled) class="text-amber-700 disabled:opacity-50">{{ $photo->is_active ? 'Deactivate' : 'Activate' }}</button>
                            <button type="button" wire:click="edit({{ $photo->id }})" @disabled(!$mutationsEnabled) class="text-cyan-700 disabled:opacity-50">Edit</button>
                            <button type="button" wire:click="delete({{ $photo->id }})" wire:confirm="Delete this photo and its uploaded image?" @disabled(!$mutationsEnabled) class="text-rose-600 disabled:opacity-50">Delete</button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        <div>{{ $photos->links() }}</div>
    @endif

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/50 px-4 py-8" wire:click.self="$set('showModal', false)">
            <section role="dialog" aria-modal="true" aria-labelledby="gallery-modal-title" class="mx-auto max-w-xl rounded-lg bg-white shadow-xl">
                <header class="flex items-center justify-between border-b border-slate-200 px-5 py-4"><div><h3 id="gallery-modal-title" class="text-base font-semibold">{{ $recordId ? 'Edit photo' : 'Add photo' }}</h3><p class="text-xs text-slate-500">Choose a category for welcome-page grouping.</p></div><button type="button" wire:click="$set('showModal', false)" aria-label="Close" class="text-xl text-slate-400">&times;</button></header>
                <form wire:submit="save" enctype="multipart/form-data" class="space-y-3 p-5">
                    <label class="block text-xs font-semibold text-slate-600">Category<select wire:model="selectedCategoryId" class="gallery-field"><option value="">Select category</option>@foreach ($categories as $category)<option value="{{ $category->id }}">{{ $category->category_name }}</option>@endforeach</select>@error('selectedCategoryId')<span class="gallery-error">{{ $message }}</span>@enderror</label>
                    <label class="block text-xs font-semibold text-slate-600">Photo name<input wire:model="name" type="text" maxlength="255" class="gallery-field">@error('name')<span class="gallery-error">{{ $message }}</span>@enderror</label>
                    <label class="block text-xs font-semibold text-slate-600">Description<textarea wire:model="description" rows="2" maxlength="255" class="gallery-field"></textarea>@error('description')<span class="gallery-error">{{ $message }}</span>@enderror</label>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="block text-xs font-semibold text-slate-600">Image caption<input wire:model="image_caption" type="text" maxlength="255" class="gallery-field">@error('image_caption')<span class="gallery-error">{{ $message }}</span>@enderror</label>
                        <label class="block text-xs font-semibold text-slate-600">Alt text<input wire:model="image_alt_text" type="text" maxlength="255" class="gallery-field">@error('image_alt_text')<span class="gallery-error">{{ $message }}</span>@enderror</label>
                    </div>
                    <label class="block text-xs font-semibold text-slate-600">{{ $recordId ? 'Replace image (optional)' : 'Image' }}<input wire:model="image" type="file" accept="image/*" class="gallery-field">@error('image')<span class="gallery-error">{{ $message }}</span>@enderror</label>
                    @if ($image)<img src="{{ $image->temporaryUrl() }}" alt="Upload preview" class="h-36 w-full rounded border border-slate-200 object-cover">@endif
                    <div class="flex gap-5"><label class="flex items-center gap-2 text-xs text-slate-700"><input wire:model="is_active" type="checkbox" class="rounded border-slate-300 text-cyan-700"> Active</label><label class="flex items-center gap-2 text-xs text-slate-700"><input wire:model="is_featured" type="checkbox" class="rounded border-slate-300 text-cyan-700"> Featured</label></div>
                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4"><button type="button" wire:click="$set('showModal', false)" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button><button type="submit" wire:loading.attr="disabled" class="rounded-md bg-cyan-700 px-4 py-2 text-xs font-semibold text-white">Save photo</button></div>
                </form>
            </section>
        </div>
    @endif

    @if ($showCategoryModal)
        <div class="fixed inset-0 z-[60] overflow-y-auto bg-slate-950/60 px-4 py-8" wire:click.self="$set('showCategoryModal', false)">
            <section role="dialog" aria-modal="true" aria-labelledby="category-modal-title" class="mx-auto max-w-md rounded-lg bg-white shadow-xl">
                <header class="flex items-center justify-between border-b border-slate-200 px-5 py-4"><h3 id="category-modal-title" class="text-base font-semibold">Add photo category</h3><button type="button" wire:click="$set('showCategoryModal', false)" aria-label="Close" class="text-xl text-slate-400">&times;</button></header>
                <form wire:submit="saveCategory" class="space-y-3 p-5">
                    <label class="block text-xs font-semibold text-slate-600">Category name<input wire:model="category_name" type="text" maxlength="255" class="gallery-field">@error('category_name')<span class="gallery-error">{{ $message }}</span>@enderror</label>
                    <label class="block text-xs font-semibold text-slate-600">Description<textarea wire:model="category_description" rows="2" maxlength="255" class="gallery-field"></textarea>@error('category_description')<span class="gallery-error">{{ $message }}</span>@enderror</label>
                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4"><button type="button" wire:click="$set('showCategoryModal', false)" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button><button type="submit" class="rounded-md bg-cyan-700 px-4 py-2 text-xs font-semibold text-white">Save category</button></div>
                </form>
            </section>
        </div>
    @endif
    <style>.gallery-field{display:block;width:100%;margin-top:.3rem;border:1px solid #cbd5e1;border-radius:.375rem;background:#fff;padding:.5rem .65rem;font-size:.75rem}.gallery-error{display:block;margin-top:.25rem;color:#e11d48;font-size:.7rem}</style>
</div>