<div>
    <div class="mb-4 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-base font-semibold tracking-tight">Shreny teachers</h2>
            <p class="text-xs text-slate-500">Assign teachers to shrenies and define their role.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search assignments..."
                class="w-52 rounded-md border-slate-300 px-3 py-2 text-xs shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
            <button wire:click="toggleMutations" type="button" role="switch"
                aria-checked="{{ $mutationsEnabled ? 'true' : 'false' }}"
                class="rounded-md border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">{{ $mutationsEnabled ? 'Editing enabled' : 'Enable editing' }}</button>
            <button wire:click="create" type="button" @disabled(!$mutationsEnabled)
                class="rounded-md bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700">+ Add assignment</button>
        </div>
    </div>
    @if (session('success'))
        <div class="mb-3 rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-3 rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">{{ session('error') }}</div>
    @endif
    <div class="overflow-x-auto rounded-md border border-slate-200">
        <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
            <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-3 py-2 font-semibold">Shreny</th>
                    <th class="px-3 py-2 font-semibold">Teacher</th>
                    <th class="px-3 py-2 font-semibold">Type</th>
                    <th class="px-3 py-2 font-semibold">Status</th>
                    <th class="px-3 py-2 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($records as $record)
                    <tr wire:key="shreny-teacher-{{ $record->id }}" class="hover:bg-slate-50">
                        <td class="px-3 py-2.5 font-semibold text-slate-800">{{ $record->shreny?->name ?? '—' }}</td>
                        <td class="px-3 py-2.5 text-slate-600">{{ $record->teacher?->name ?? '—' }}</td>
                        <td class="px-3 py-2.5 text-slate-600">{{ str($record->teacher_type)->replace('_', ' ')->title() }}</td>
                        <td class="px-3 py-2.5"><span class="rounded-full px-2 py-1 text-[10px] font-semibold {{ $record->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $record->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="whitespace-nowrap px-3 py-2.5 text-right">
                            <button wire:click="edit({{ $record->id }})" @disabled(!$mutationsEnabled) class="mr-2 font-semibold text-cyan-700">Edit</button>
                            <button wire:click="delete({{ $record->id }})" wire:confirm="Delete this assignment?" @disabled(!$mutationsEnabled) class="font-semibold text-rose-600">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-3 py-10 text-center text-xs text-slate-500">No shreny teacher assignments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $records->links() }}</div>

    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/50 px-4 py-8">
            <div class="mx-auto max-w-2xl rounded-xl bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="text-base font-semibold">{{ $recordId ? 'Edit' : 'Add' }} shreny teacher</h3>
                    <button wire:click="$set('showModal', false)" class="text-xl text-slate-400" aria-label="Close">&times;</button>
                </div>
                <form wire:submit="save" class="space-y-4 p-5">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="field-label">Shreny *</label>
                            <select wire:model="shreny_id" class="field-input"><option value="">Select shreny</option>@foreach($shrenies as $shreny)<option value="{{ $shreny->id }}">{{ $shreny->name }}</option>@endforeach</select>
                            @error('shreny_id')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="field-label">Teacher *</label>
                            <select wire:model="teacher_id" class="field-input"><option value="">Select teacher</option>@foreach($teachers as $teacher)<option value="{{ $teacher->id }}">{{ $teacher->name }}</option>@endforeach</select>
                            @error('teacher_id')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="field-label">Teacher type *</label>
                            <select wire:model="teacher_type" class="field-input">
                                <option value="">Select type</option>
                                <option value="class_teacher">Class teacher</option>
                                <option value="subject_teacher">Subject teacher</option>
                                <option value="other">Other</option>
                            </select>
                            @error('teacher_type')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="field-label">Order</label>
                            <input wire:model="order_id" type="number" class="field-input">
                            @error('order_id')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="flex items-center gap-2 pt-2 text-xs"><input wire:model="is_active" type="checkbox" class="rounded border-slate-300 text-cyan-600"> Active</label>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="field-label">Remarks</label>
                            <textarea wire:model="remarks" rows="2" class="field-input"></textarea>
                            @error('remarks')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                        <button type="button" wire:click="$set('showModal', false)" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button>
                        <button type="submit" class="rounded-md bg-cyan-600 px-4 py-2 text-xs font-semibold text-white">Save assignment</button>
                    </div>
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