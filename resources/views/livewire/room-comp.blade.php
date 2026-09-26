<div>
    <div class="mb-4 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-cyan-700">Facilities</p><h2 class="text-base font-semibold">Rooms</h2><p class="text-xs text-slate-500">Room inventory and seating capacity.</p></div>
        <div class="flex flex-wrap gap-2">
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search rooms" class="w-48 rounded-md border-slate-300 px-3 py-2 text-xs">
            <button wire:click="toggleMutations" type="button" class="rounded-md border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">{{ $mutationsEnabled ? 'Editing enabled' : 'Enable editing' }}</button>
            <button wire:click="create" type="button" @disabled(!$mutationsEnabled) class="rounded-md bg-slate-900 px-3 py-2 text-xs font-semibold text-white">Add room</button>
        </div>
    </div>
    @if (session('success'))<div class="mb-3 rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="mb-3 rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">{{ session('error') }}</div>@endif
    <div class="overflow-x-auto rounded-md border border-slate-200 bg-white">
        <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
            <thead class="bg-slate-50 text-[10px] uppercase text-slate-500"><tr><th class="px-3 py-2">Room</th><th class="px-3 py-2">Floor / type</th><th class="px-3 py-2">Seating</th><th class="px-3 py-2">Status / condition</th><th class="px-3 py-2">Active</th><th class="px-3 py-2 text-right">Actions</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($records as $record)
                    @php($capacity = $record->no_of_students_total ?? (($record->no_of_benches ?? 0) * ($record->no_of_students_per_bench ?? 0)))
                    <tr wire:key="room-{{ $record->id }}" class="hover:bg-slate-50">
                        <td class="px-3 py-3"><div class="font-semibold text-slate-800">{{ $record->name }}</div><div class="text-[11px] text-slate-500">{{ $record->description ?: 'No description' }}</div></td>
                        <td class="px-3 py-3 text-slate-600">{{ $record->floor ?: '—' }}<br>{{ $record->room_type ?: '—' }}</td>
                        <td class="px-3 py-3 text-slate-600">{{ $record->no_of_benches ?? '—' }} benches<br>{{ $capacity ?: 'Capacity not set' }} students</td>
                        <td class="px-3 py-3"><span class="font-medium text-slate-700">{{ $record->room_status ?: 'Unspecified' }}</span><br><span class="text-[11px] text-slate-500">{{ $record->room_condition ?: 'Condition not set' }}</span></td>
                        <td class="px-3 py-3"><span class="rounded px-2 py-1 text-[10px] font-semibold {{ $record->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $record->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="whitespace-nowrap px-3 py-3 text-right"><button wire:click="edit({{ $record->id }})" @disabled(!$mutationsEnabled) class="mr-3 font-semibold text-cyan-700">Edit</button><button wire:click="delete({{ $record->id }})" wire:confirm="Delete this room?" @disabled(!$mutationsEnabled) class="font-semibold text-rose-600">Delete</button></td>
                    </tr>
                @empty<tr><td colspan="6" class="px-3 py-10 text-center text-slate-500">No rooms found.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $records->links() }}</div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/50 px-4 py-8"><div class="mx-auto max-w-2xl rounded-lg bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4"><h3 class="text-base font-semibold">{{ $recordId ? 'Edit room' : 'Add room' }}</h3><button wire:click="$set('showModal', false)" aria-label="Close" class="text-xl text-slate-400">&times;</button></div>
            <form wire:submit="save" class="space-y-4 p-5"><div class="grid gap-3 sm:grid-cols-2">
                <label class="sm:col-span-2 text-xs font-medium text-slate-600">Name *<input wire:model="name" class="room-field">@error('name')<span class="room-error">{{ $message }}</span>@enderror</label>
                <label class="sm:col-span-2 text-xs font-medium text-slate-600">Description<textarea wire:model="description" rows="2" class="room-field"></textarea>@error('description')<span class="room-error">{{ $message }}</span>@enderror</label>
                <label class="text-xs font-medium text-slate-600">Floor<select wire:model="floor" class="room-field"><option value="">Select floor</option>@foreach ($floors as $option)<option value="{{ $option }}">{{ $option }}</option>@endforeach</select>@error('floor')<span class="room-error">{{ $message }}</span>@enderror</label>
                <label class="text-xs font-medium text-slate-600">Room type<select wire:model="room_type" class="room-field"><option value="">Select type</option>@foreach ($roomTypes as $option)<option value="{{ $option }}">{{ $option }}</option>@endforeach</select>@error('room_type')<span class="room-error">{{ $message }}</span>@enderror</label>
                <label class="text-xs font-medium text-slate-600">Room status<select wire:model="room_status" class="room-field"><option value="">Select status</option>@foreach ($roomStatuses as $option)<option value="{{ $option }}">{{ $option }}</option>@endforeach</select>@error('room_status')<span class="room-error">{{ $message }}</span>@enderror</label>
                <label class="text-xs font-medium text-slate-600">Condition<select wire:model="room_condition" class="room-field"><option value="">Select condition</option>@foreach ($conditions as $option)<option value="{{ $option }}">{{ $option }}</option>@endforeach</select>@error('room_condition')<span class="room-error">{{ $message }}</span>@enderror</label>
                <label class="text-xs font-medium text-slate-600">Benches<input wire:model="no_of_benches" type="number" min="0" class="room-field">@error('no_of_benches')<span class="room-error">{{ $message }}</span>@enderror</label>
                <label class="text-xs font-medium text-slate-600">Students per bench<input wire:model="no_of_students_per_bench" type="number" min="1" class="room-field">@error('no_of_students_per_bench')<span class="room-error">{{ $message }}</span>@enderror</label>
                <label class="text-xs font-medium text-slate-600">Total student capacity<input wire:model="no_of_students_total" type="number" min="1" class="room-field">@error('no_of_students_total')<span class="room-error">{{ $message }}</span>@enderror</label>
                <label class="sm:col-span-2 text-xs font-medium text-slate-600">Remarks<textarea wire:model="remarks" rows="2" class="room-field"></textarea>@error('remarks')<span class="room-error">{{ $message }}</span>@enderror</label>
                <label class="sm:col-span-2 flex items-center gap-2 text-xs text-slate-700"><input wire:model="is_active" type="checkbox" class="rounded border-slate-300 text-cyan-700"> Active</label>
            </div><div class="flex justify-end gap-2 border-t border-slate-100 pt-4"><button type="button" wire:click="$set('showModal', false)" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button><button type="submit" class="rounded-md bg-cyan-700 px-4 py-2 text-xs font-semibold text-white">Save room</button></div></form>
        </div></div>
    @endif
    <style>.room-field{display:block;width:100%;margin-top:.3rem;border:1px solid #cbd5e1;border-radius:.375rem;padding:.5rem .65rem;font-size:.75rem}.room-error{display:block;margin-top:.25rem;color:#e11d48;font-size:.7rem}</style>
</div>