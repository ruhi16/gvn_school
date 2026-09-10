<div>
    <div class="mb-4 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">Exam settings</p>
            <h2 class="text-base font-semibold tracking-tight">{{ $title }}</h2>
            <p class="text-xs text-slate-500">Manage {{ strtolower($title) }} records.</p>
        </div>
        <div class="flex gap-2">
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search {{ strtolower($title) }}..."
                class="w-52 rounded-md border-slate-300 px-3 py-2 text-xs shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
            <button wire:click="create" type="button" class="rounded-md bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700">+ Add {{ strtolower($singular) }}</button>
        </div>
    </div>
    @if (session('success'))
    <div class="mb-3 rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700">{{ session('success') }}</div>
    @endif
    <div class="overflow-x-auto rounded-md border border-slate-200">
        <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
            <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                <tr><th class="w-14 px-3 py-2 font-semibold">SL</th><th class="px-3 py-2 font-semibold">Name</th><th class="px-3 py-2 font-semibold">Description</th><th class="px-3 py-2 font-semibold">Status</th><th class="px-3 py-2 text-right font-semibold">Actions</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($records as $record)
                <tr wire:key="{{ $type }}-{{ $record->id }}" class="hover:bg-slate-50">
                    <td class="px-3 py-2.5 text-slate-500">{{ $records->firstItem() + $loop->index }}</td>
                    <td class="px-3 py-2.5 font-semibold text-slate-800">{{ $record->name }}</td>
                    <td class="px-3 py-2.5 text-slate-600">{{ $record->description ?: '—' }}</td>
                    <td class="px-3 py-2.5"><span class="rounded-full px-2 py-1 text-[10px] font-semibold {{ $record->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $record->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td class="whitespace-nowrap px-3 py-2.5 text-right"><button wire:click="edit({{ $record->id }})" class="mr-2 font-semibold text-cyan-700">Edit</button><button wire:click="delete({{ $record->id }})" wire:confirm="Delete this record?" class="font-semibold text-rose-600">Delete</button></td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-3 py-10 text-center text-xs text-slate-500">No {{ strtolower($title) }} found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $records->links() }}</div>

    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/50 px-4 py-8">
        <div class="mx-auto max-w-2xl rounded-xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h3 class="text-base font-semibold">{{ $recordId ? 'Edit' : 'Add' }} {{ $singular }}</h3>
                <button wire:click="$set('showModal', false)" class="text-xl text-slate-400" aria-label="Close">&times;</button>
            </div>
            <form wire:submit="save" class="space-y-4 p-5">
                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="sm:col-span-2"><label class="field-label">Name *</label><input wire:model="name" class="field-input">@error('name')<span class="field-error">{{ $message }}</span>@enderror</div>
                    <div class="sm:col-span-2"><label class="field-label">Description</label><textarea wire:model="description" rows="2" class="field-input"></textarea>@error('description')<span class="field-error">{{ $message }}</span>@enderror</div>
                    <div><label class="field-label">Order</label><input wire:model="order_id" type="number" class="field-input">@error('order_id')<span class="field-error">{{ $message }}</span>@enderror</div>
                    <div><label class="field-label">School ID</label><input wire:model="school_id" type="number" class="field-input">@error('school_id')<span class="field-error">{{ $message }}</span>@enderror</div>
                    <div class="sm:col-span-2"><label class="field-label">Remarks</label><textarea wire:model="remarks" rows="2" class="field-input"></textarea>@error('remarks')<span class="field-error">{{ $message }}</span>@enderror</div>
                    <div class="sm:col-span-2"><label class="flex items-center gap-2 text-xs"><input wire:model="is_active" type="checkbox" class="rounded border-slate-300 text-cyan-600"> Active</label></div>
                </div>
                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4"><button type="button" wire:click="$set('showModal', false)" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button><button type="submit" class="rounded-md bg-cyan-600 px-4 py-2 text-xs font-semibold text-white">Save {{ $singular }}</button></div>
            </form>
        </div>
    </div>
    @endif
    <style>
        .field-label { display: block; font-size: .7rem; font-weight: 600; color: #475569; margin-bottom: .3rem }
        .field-input { width: 100%; border-radius: .375rem; border-color: #cbd5e1; padding: .5rem .65rem; font-size: .75rem }
        .field-error { display: block; color: #e11d48; font-size: .7rem; margin-top: .25rem }
    </style>
</div>
