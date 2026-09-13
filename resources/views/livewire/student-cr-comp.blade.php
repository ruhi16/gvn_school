<section class="space-y-4">
    <div class="flex flex-col gap-3 border-b border-slate-200 pb-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">Current session admissions</p>
            <h2 class="mt-1 text-lg font-semibold text-slate-950">Assign class records and roll numbers</h2>
            @if($currentSessionId)
                <p class="mt-1 text-xs text-slate-500">Showing newly admitted active students for the selected Shreny and Section.</p>
            @else
                <p class="mt-1 text-xs text-rose-600">No active session is configured.</p>
            @endif
        </div>
        <div class="grid gap-2 sm:grid-cols-2 lg:min-w-[420px]">
            <select wire:model.live="selectedShrenyId" class="rounded border-slate-300 px-3 py-2 text-xs">
                <option value="">Select Shreny</option>
                @foreach($shrenies as $shreny)<option value="{{ $shreny->id }}">{{ $shreny->name }}</option>@endforeach
            </select>
            <select wire:model.live="selectedSectionId" class="rounded border-slate-300 px-3 py-2 text-xs" @disabled(!$selectedShrenyId)>
                <option value="">Select Section</option>
                @foreach($sections as $section)<option value="{{ $section->id }}">{{ $section->name }}</option>@endforeach
            </select>
        </div>
    </div>

    @if($selectedShrenyId && $selectedSectionId)
        <div class="flex flex-wrap items-center justify-between gap-3 rounded bg-slate-50 px-3 py-2">
            <div class="text-xs text-slate-600">{{ $students->count() }} newly admitted student(s), {{ count(array_filter($assignedRolls)) }} assigned</div>
            <div class="flex gap-2">
                <button type="button" wire:click="assignAutomatically" class="rounded bg-cyan-600 px-3 py-2 text-xs font-semibold text-white hover:bg-cyan-500">Assign automatically</button>
                <button type="button" wire:click="assignManually" class="rounded border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-white">Save manual rolls</button>
            </div>
        </div>
        @error('rollNumbers')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
        <div class="overflow-x-auto rounded border border-slate-200">
            <table class="w-full min-w-[760px] text-left text-xs">
                <thead class="border-b border-slate-200 bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr><th class="px-3 py-2">Student</th><th class="px-3 py-2">Father / guardian</th><th class="px-3 py-2">Admission ID</th><th class="px-3 py-2">Current roll no.</th><th class="px-3 py-2">Action</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                        <tr>
                            <td class="px-3 py-2 font-semibold text-slate-900">{{ $student->name }}</td>
                            <td class="px-3 py-2 text-slate-600">{{ $student->fname ?: '-' }}</td>
                            <td class="px-3 py-2 text-slate-500">#{{ $student->id }}</td>
                            <td class="px-3 py-2"><input wire:model="rollNumbers.{{ $student->id }}" type="number" min="1" class="w-24 rounded border-slate-300 px-2 py-1.5 text-xs" placeholder="Unassigned">
                                @error("rollNumbers.{$student->id}")<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                            </td>
                            <td class="px-3 py-2"><button type="button" wire:click="updateRollNumber({{ $student->id }})" class="font-semibold text-cyan-700 hover:text-cyan-600">{{ isset($assignedRolls[$student->id]) ? 'Update' : 'Assign' }}</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-3 py-8 text-center text-sm text-slate-500">No newly admitted students match this Shreny and Section.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="rounded border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">Select a Shreny and its mapped Section to list newly admitted students.</div>
    @endif
</section>
