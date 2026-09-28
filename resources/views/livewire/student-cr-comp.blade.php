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
        <div class="flex flex-wrap items-start gap-2 lg:min-w-[420px]">
            <button type="button" wire:click="toggleMutations" role="switch" aria-checked="{{ $mutationsEnabled ? 'true' : 'false' }}" class="rounded border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">{{ $mutationsEnabled ? 'Editing enabled' : 'Enable editing' }}</button>
            <div class="grid flex-1 gap-2 sm:grid-cols-2">
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
    </div>

    @if($selectedShrenyId && $selectedSectionId)
        <div class="flex flex-wrap items-center justify-between gap-3 rounded bg-slate-50 px-3 py-2">
            <div class="text-xs text-slate-600">{{ $students->count() }} newly admitted student(s), {{ count(array_filter($assignedRolls)) }} assigned</div>
            <div class="flex gap-2">
                <button type="button" wire:click="assignAutomatically" @disabled(!$mutationsEnabled) class="rounded bg-cyan-600 px-3 py-2 text-xs font-semibold text-white hover:bg-cyan-500">Assign automatically</button>
                <button type="button" wire:click="assignManually" @disabled(!$mutationsEnabled) class="rounded border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-white">Save manual rolls</button>
            </div>
        </div>
        @error('rollNumbers')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
        <div class="overflow-x-auto rounded border border-slate-200">
            <table class="w-full min-w-[1100px] text-left text-xs">
                <thead class="border-b border-slate-200 bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr><th class="px-3 py-2">Student</th><th class="px-3 py-2">Father / guardian</th><th class="px-3 py-2">Admission ID</th><th class="px-3 py-2">Current roll no.</th><th class="px-3 py-2">Promoted</th><th class="px-3 py-2">Active</th><th class="px-3 py-2">Remarks</th><th class="px-3 py-2">Action</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                        @php($classRecord = $classRecords->get($student->id))
                        <tr>
                            <td class="px-3 py-2 font-semibold text-slate-900">
                                {{ $student->name }}
                                <p class="mt-0.5 text-[10px] font-normal text-slate-500">{{ $student->dob?->format('d M Y') ?: 'DOB not set' }} · {{ $student->gender ?: 'Gender not set' }}</p>
                                <details class="mt-1 font-normal">
                                    <summary class="cursor-pointer text-[10px] text-cyan-700">Class record details</summary>
                                    @if ($classRecord)
                                        <dl class="mt-2 grid gap-x-3 gap-y-1 sm:grid-cols-2">
                                            <div><dt class="text-[10px] font-semibold text-slate-500">Record ID</dt><dd>{{ $classRecord->id }}</dd></div>
                                            <div><dt class="text-[10px] font-semibold text-slate-500">StudentDB ID</dt><dd>{{ $classRecord->studentdb_id }}</dd></div>
                                            <div><dt class="text-[10px] font-semibold text-slate-500">Shreny ID</dt><dd>{{ $classRecord->curr_shreny_id }}</dd></div>
                                            <div><dt class="text-[10px] font-semibold text-slate-500">Section ID</dt><dd>{{ $classRecord->curr_section_id }}</dd></div>
                                            <div><dt class="text-[10px] font-semibold text-slate-500">Roll number</dt><dd>{{ $classRecord->curr_roll_no ?? '—' }}</dd></div>
                                            <div><dt class="text-[10px] font-semibold text-slate-500">School / session</dt><dd>{{ $classRecord->school_id }} / {{ $classRecord->session_id }}</dd></div>
                                            <div><dt class="text-[10px] font-semibold text-slate-500">Order</dt><dd>{{ $classRecord->order_id ?? '—' }}</dd></div>
                                            <div><dt class="text-[10px] font-semibold text-slate-500">Editable / deleted / finalized</dt><dd>{{ $classRecord->is_editable ? 'Yes' : 'No' }} / {{ $classRecord->is_deleted ? 'Yes' : 'No' }} / {{ $classRecord->is_finalized ? 'Yes' : 'No' }}</dd></div>
                                            <div><dt class="text-[10px] font-semibold text-slate-500">Created</dt><dd>{{ $classRecord->created_at?->toDateTimeString() ?? '—' }}</dd></div>
                                            <div><dt class="text-[10px] font-semibold text-slate-500">Updated</dt><dd>{{ $classRecord->updated_at?->toDateTimeString() ?? '—' }}</dd></div>
                                        </dl>
                                    @else
                                        <p class="mt-1 text-[10px] text-slate-500">No StudentCR record has been saved for this student in this session.</p>
                                    @endif
                                </details>
                            </td>
                            <td class="px-3 py-2 text-slate-600">{{ $student->fname ?: '-' }}</td>
                            <td class="px-3 py-2 text-slate-500">#{{ $student->id }}</td>
                            <td class="px-3 py-2"><input wire:model="rollNumbers.{{ $student->id }}" @disabled(!$mutationsEnabled) type="number" min="1" class="w-24 rounded border-slate-300 px-2 py-1.5 text-xs" placeholder="Unassigned">
                                @error("rollNumbers.{$student->id}")<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                            </td>
                            <td class="px-3 py-2"><label class="inline-flex items-center gap-1.5"><input wire:model="promotedByStudent.{{ $student->id }}" @disabled(!$mutationsEnabled) type="checkbox" class="rounded border-slate-300 text-cyan-700"><span class="text-[10px]">Yes</span></label></td>
                            <td class="px-3 py-2"><label class="inline-flex items-center gap-1.5"><input wire:model="activeByStudent.{{ $student->id }}" @disabled(!$mutationsEnabled) type="checkbox" class="rounded border-slate-300 text-cyan-700"><span class="text-[10px]">Yes</span></label></td>
                            <td class="px-3 py-2"><input wire:model="remarksByStudent.{{ $student->id }}" @disabled(!$mutationsEnabled) type="text" maxlength="255" class="w-40 rounded border-slate-300 px-2 py-1.5 text-xs" placeholder="Optional"></td>
                            <td class="px-3 py-2"><button type="button" wire:click="updateRollNumber({{ $student->id }})" @disabled(!$mutationsEnabled) class="font-semibold text-cyan-700 hover:text-cyan-600">{{ $classRecord ? 'Update' : 'Assign' }}</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-3 py-8 text-center text-sm text-slate-500">No newly admitted students match this Shreny and Section.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="rounded border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">Select a Shreny and its mapped Section to list newly admitted students.</div>
    @endif
</section>
