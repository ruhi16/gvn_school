<div>
    <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
        <div><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-cyan-700">Exam settings</p><h2 class="text-base font-semibold">Exam room allocation</h2><p class="text-xs text-slate-500">Allocate current-session students by Shreny-Section and roll range.</p></div>
        <div class="flex flex-wrap items-center gap-2"><button wire:click="toggleMutations" type="button" class="rounded-md border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">{{ $mutationsEnabled ? 'Editing enabled' : 'Enable editing' }}</button>
            @if ($selectedExamNameId && $selectedExamTypeId && $selectedExamPartId && $allocations->isNotEmpty())
                @if ($isFinalized)
                    <button wire:click="unfinalizeAllotment" wire:confirm="Unfinalize this allotment and allow edits?" type="button" @disabled(!$mutationsEnabled) class="rounded-md border border-amber-300 px-3 py-2 text-xs font-semibold text-amber-800 disabled:opacity-50">Unfinalize</button>
                @else
                    <button wire:click="finalizeAllotment" wire:confirm="Finalize this room allotment?" type="button" @disabled(!$mutationsEnabled || !$complete) class="rounded-md bg-emerald-700 px-3 py-2 text-xs font-semibold text-white disabled:cursor-not-allowed disabled:bg-slate-200 disabled:text-slate-500">Finalize allotment</button>
                @endif
            @endif
        </div>
    </div>
    @if (session('success'))<div class="mb-3 rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="mb-3 rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">{{ session('error') }}</div>@endif
    @unless ($hasActiveSession)<div class="mb-3 rounded-md border border-rose-200 bg-rose-50 px-3 py-2 text-xs text-rose-800">No active session is configured for this school. Room assignments are unavailable.</div>@endunless

    <div class="mb-5 max-w-2xl rounded-md border border-slate-200 bg-white p-4">
        <label class="block text-xs font-semibold text-slate-600">Exam combination<select wire:model.live="selectedCombinationKey" class="exam-field"><option value="">Choose exam combination</option>@foreach ($availableCombinations as $combination)<option value="{{ $combination['key'] }}">{{ $combination['label'] }}{{ $combination['finalized'] ? ' (Finalized)' : '' }}</option>@endforeach</select></label>
        @error('selectedCombinationKey')<span class="exam-error">{{ $message }}</span>@enderror
        @if ($availableCombinations->isEmpty())<p class="mt-2 text-xs text-slate-500">No configured exam combinations are available for this school.</p>@endif
    </div>
    @if (!$selectedExamNameId || !$selectedExamTypeId || !$selectedExamPartId)<p class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">Select an exam name, type, and part to view allocation status.</p>@endif

    <section class="mb-6"><div class="mb-2 flex items-center justify-between"><h3 class="text-sm font-semibold text-slate-800">Shreny-Section roster</h3><span class="text-[10px] text-slate-500">Green = complete · Red = students remaining</span></div>
        <div class="grid gap-2 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($groups as $group)
                <div wire:key="group-{{ $group['key'] }}" class="border-l-4 p-3 {{ $group['remaining'] === 0 ? 'border-emerald-500 bg-emerald-50' : 'border-rose-500 bg-rose-50' }}">
                    <div class="flex items-start justify-between gap-2"><div><p class="text-xs font-semibold text-slate-800">{{ $group['shreny']->name }} / {{ $group['section']->name }}</p><p class="mt-1 text-[11px] text-slate-600">{{ $group['total'] }} students · {{ $group['assigned'] }} assigned</p></div><span class="text-xs font-bold {{ $group['remaining'] === 0 ? 'text-emerald-800' : 'text-rose-800' }}">{{ $group['remaining'] }} left</span></div>
                    @if ($selectedExamNameId && $selectedExamTypeId && $selectedExamPartId)<button wire:click="openAllocation({{ $group['shreny_id'] }}, {{ $group['section_id'] }})" @disabled(!$mutationsEnabled || $group['remaining'] === 0 || $isFinalized) class="mt-2 text-[11px] font-semibold text-cyan-800 disabled:text-slate-400">Assign roll range</button>@endif
                </div>
            @empty<div class="border border-slate-200 bg-white px-3 py-5 text-xs text-slate-500">No active Shreny-Section records or current-session students.</div>@endforelse
        </div>
    </section>

    <section class="mb-6"><div class="mb-2 flex items-center justify-between"><h3 class="text-sm font-semibold text-slate-800">Usable rooms</h3><span class="text-[10px] text-slate-500">Capacity uses total capacity, or benches × students per bench</span></div>
        <div class="grid gap-2 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($rooms as $room)
                <div wire:key="exam-room-{{ $room->id }}" class="border-l-4 p-3 {{ $room->remaining_capacity > 0 && $room->capacity > 0 ? 'border-emerald-500 bg-emerald-50' : 'border-rose-500 bg-rose-50' }}">
                    <div class="flex items-start justify-between gap-2"><div><p class="text-xs font-semibold text-slate-800">{{ $room->name }}</p><p class="mt-1 text-[11px] text-slate-600">{{ $room->no_of_benches ?? 0 }} benches · {{ $room->no_of_students_per_bench ?? 0 }} / bench</p></div><span class="text-right text-xs font-bold text-slate-800">{{ $room->assigned_students }} / {{ $room->capacity ?: '—' }}<small class="block font-normal text-slate-600">{{ $room->remaining_capacity }} seats left</small></span></div>
                    <p class="mt-1 text-[10px] text-slate-500">{{ $room->allocation_status }}{{ $room->room_condition ? ' · '.$room->room_condition : '' }}</p>
                    @if ($selectedExamNameId && $selectedExamTypeId && $selectedExamPartId)<button wire:click="openAllocation(null, null, {{ $room->id }})" @disabled(!$mutationsEnabled || $room->remaining_capacity === 0 || $isFinalized) class="mt-2 text-[11px] font-semibold text-cyan-800 disabled:text-slate-400">Room-wise Allotment</button>@endif
                </div>
            @empty<div class="border border-slate-200 bg-white px-3 py-5 text-xs text-slate-500">No active rooms available for allocation.</div>@endforelse
        </div>
    </section>

    <section><div class="mb-2 flex flex-wrap items-center justify-between gap-2"><div><h3 class="text-sm font-semibold text-slate-800">Room-wise assignments</h3><span class="text-[10px] text-slate-500">{{ $allocations->count() }} ranges assigned</span>@if ($selectedExamNameId && $allocations->isNotEmpty())<span class="ml-2 rounded px-2 py-1 text-[10px] font-semibold {{ $isFinalized ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ $isFinalized ? 'Finalized' : 'Editable' }}</span>@endif</div>
        @if ($complete && $selectedExamNameId && $selectedExamTypeId && $selectedExamPartId && $allocations->isNotEmpty())<a href="{{ route('admin.exam-rooms.pdf', ['exam_name_id' => $selectedExamNameId, 'exam_type_id' => $selectedExamTypeId, 'exam_part_id' => $selectedExamPartId]) }}" target="_blank" class="rounded-md bg-slate-900 px-3 py-2 text-xs font-semibold text-white">Download room plan PDF</a>@endif</div>
        <div class="overflow-x-auto rounded-md border border-slate-200 bg-white"><table class="min-w-full divide-y divide-slate-200 text-left text-xs">
            <thead class="bg-slate-50 text-[10px] uppercase text-slate-500"><tr><th class="px-3 py-2">Room / capacity</th><th class="px-3 py-2">Shreny-Section</th><th class="px-3 py-2">Roll range</th><th class="px-3 py-2">Students</th><th class="px-3 py-2">Bench detail</th><th class="px-3 py-2">Status</th><th class="px-3 py-2 text-right">Actions</th></tr></thead>
            <tbody class="divide-y divide-slate-100">@forelse ($allocations as $allocation)
                <tr wire:key="allocation-{{ $allocation->id }}" class="hover:bg-slate-50"><td class="px-3 py-2.5 font-semibold text-slate-800">{{ $allocation->room?->name ?? 'Room removed' }}<span class="ml-2 rounded px-1.5 py-1 text-[10px] {{ $allocation->roomCapacity > $allocation->roomStudentCount ? 'bg-emerald-50 text-emerald-800' : 'bg-rose-50 text-rose-800' }}">{{ $allocation->roomStudentCount }} / {{ $allocation->roomCapacity }} occupied</span></td><td class="px-3 py-2.5">{{ $allocation->shreny?->name }} / {{ $allocation->section?->name }}</td><td class="px-3 py-2.5">{{ $allocation->roll_no_range_start }}–{{ $allocation->roll_no_range_end }}</td><td class="px-3 py-2.5">{{ $allocation->rangeStudentCount }} students</td><td class="px-3 py-2.5">{{ $allocation->no_of_students_per_bench ?? $allocation->room?->no_of_students_per_bench ?? '—' }} per bench</td><td class="px-3 py-2.5"><span class="rounded px-2 py-1 text-[10px] font-semibold {{ $allocation->is_finalized ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ $allocation->is_finalized ? 'Finalized' : 'Editable' }}</span></td><td class="whitespace-nowrap px-3 py-2.5 text-right">@unless ($isFinalized)<button wire:click="editAllocation({{ $allocation->id }})" @disabled(!$mutationsEnabled) class="mr-3 font-semibold text-cyan-700 disabled:opacity-50">Edit</button><button wire:click="deleteAllocation({{ $allocation->id }})" wire:confirm="Remove this room assignment?" @disabled(!$mutationsEnabled) class="font-semibold text-rose-600 disabled:opacity-50">Remove</button>@else<span class="text-[10px] text-slate-500">Locked</span>@endunless</td></tr>
            @empty<tr><td colspan="7" class="px-3 py-8 text-center text-slate-500">Choose an exam combination to view its room assignments.</td></tr>@endforelse</tbody>
        </table></div>
    </section>

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/50 px-4 py-8"><div class="mx-auto max-w-xl rounded-lg bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4"><div><h3 class="text-base font-semibold">{{ $recordId ? 'Edit room allotment' : 'Room-wise Allotment' }}</h3><p class="text-xs text-slate-500">Select a Shreny-Section and roll-number range for {{ $rooms->firstWhere('id', $selectedRoomId)?->name ?? 'a room' }}.</p></div><button wire:click="$set('showModal', false)" aria-label="Close" class="text-xl text-slate-400">&times;</button></div>
            <form wire:submit="saveAllocation" class="space-y-3 p-5">
                <label class="block text-xs font-semibold text-slate-600">Shreny-Section<select wire:model.live="selectedGroupKey" class="exam-field"><option value="">Select roster</option>@foreach ($groups as $group)<option value="{{ $group['key'] }}">{{ $group['shreny']->name }} / {{ $group['section']->name }} ({{ $group['remaining'] }} remaining)</option>@endforeach</select></label>
                <div class="grid gap-3 sm:grid-cols-2"><label class="text-xs font-semibold text-slate-600">Room<select wire:model.live="selectedRoomId" class="exam-field"><option value="">Select room</option>@foreach ($rooms as $room)<option value="{{ $room->id }}">{{ $room->name }} · {{ $room->remaining_capacity }} seats left</option>@endforeach</select>@error('selectedRoomId')<span class="exam-error">{{ $message }}</span>@enderror</label>
                    <label class="text-xs font-semibold text-slate-600">Students per bench<input wire:model="no_of_students_per_bench" type="number" min="1" class="exam-field">@error('no_of_students_per_bench')<span class="exam-error">{{ $message }}</span>@enderror</label>
                </div>
                @unless ($recordId)<div class="flex flex-wrap items-center gap-2"><button type="button" wire:click="previewCapacityAllotment" @disabled(!$selectedRoomId || !$selectedGroupKey) class="rounded-md border border-cyan-300 px-3 py-2 text-xs font-semibold text-cyan-800 disabled:border-slate-200 disabled:text-slate-400">Allot by room capacity</button>@if ($autoAllocateByCapacity)<button type="button" wire:click="useManualRollRange" class="rounded-md px-3 py-2 text-xs font-semibold text-slate-600">Use manual roll range</button>@endif</div>@endunless
                @if ($autoAllocateByCapacity)
                    <div class="rounded-md border border-emerald-200 bg-emerald-50 p-3 text-xs text-emerald-900"><p class="font-semibold">{{ $capacityPreviewCount }} student(s) fit in the room's remaining seats.</p><p class="mt-1">Roll ranges: @foreach ($capacityPreviewRanges as $range){{ $range['start'] }}–{{ $range['end'] }}@if (!$loop->last), @endif @endforeach</p><p class="mt-1 text-[11px]">The preview is recalculated before saving.</p></div>
                @else
                    <div class="grid gap-3 sm:grid-cols-2"><label class="text-xs font-semibold text-slate-600">Roll range start<input wire:model="roll_no_range_start" type="number" min="1" class="exam-field">@error('roll_no_range_start')<span class="exam-error">{{ $message }}</span>@enderror</label><label class="text-xs font-semibold text-slate-600">Roll range end<input wire:model="roll_no_range_end" type="number" min="1" class="exam-field">@error('roll_no_range_end')<span class="exam-error">{{ $message }}</span>@enderror</label></div>
                @endif
                <label class="block text-xs font-semibold text-slate-600">Remarks<textarea wire:model="remarks" rows="2" class="exam-field"></textarea>@error('remarks')<span class="exam-error">{{ $message }}</span>@enderror</label>
                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4"><button type="button" wire:click="$set('showModal', false)" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button><button type="submit" class="rounded-md bg-cyan-700 px-4 py-2 text-xs font-semibold text-white">{{ $recordId ? 'Update allotment' : ($autoAllocateByCapacity ? 'Confirm capacity allotment' : 'Save assignment') }}</button></div>
            </form>
        </div></div>
    @endif
    <style>.exam-field{display:block;width:100%;margin-top:.3rem;border:1px solid #cbd5e1;border-radius:.375rem;background:#fff;padding:.5rem .65rem;font-size:.75rem}.exam-error{display:block;margin-top:.25rem;color:#e11d48;font-size:.7rem}</style>
</div>