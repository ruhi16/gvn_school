<div class="space-y-5">
    <header class="border-b border-slate-200 pb-5">
        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">Exam settings</p>
        <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">Exam marks entry</h2>
        <p class="mt-1 text-sm text-slate-500">Select a subject and exam combination to enter marks for enrolled students.</p>
    </header>

    @if (!$session)
        <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">No active session is configured.</div>
    @elseif ($configurations->isEmpty())
        <div class="rounded-lg border border-slate-200 bg-white px-4 py-8 text-center text-sm text-slate-500">No exam combinations are configured.</div>
    @elseif (!$showEntry)
        <div class="space-y-6">
            @forelse ($shrenySections as $shrenySection)
                @php($shreny = $shrenies[$shrenySection->shreny_id] ?? null)
                @php($section = $sections[$shrenySection->section_id] ?? null)
                @php($mappedSubjects = $examSubjects[$shrenySection->shreny_id] ?? collect())
                @if ($shreny && $section)
                    <section wire:key="marks-entry-{{ $shrenySection->id }}" class="overflow-hidden rounded-lg border border-slate-200 bg-white">
                        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-4 py-3">
                            <h3 class="font-semibold text-slate-900">{{ $shreny->name }} <span class="font-normal text-slate-500">&amp;</span> {{ $section->name }}</h3>
                            <span class="text-xs text-slate-500">{{ $session->name }}</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-[70rem] text-left text-sm">
                                <thead class="bg-white text-xs uppercase tracking-wider text-slate-500">
                                    <tr>
                                        <th class="sticky left-0 min-w-52 border-b border-slate-200 bg-white px-4 py-3">Subject</th>
                                        @foreach ($configurations as $configuration)
                                            <th class="min-w-48 border-b border-slate-200 px-4 py-3">
                                                <span class="block text-slate-900">{{ $examNames[$configuration->exam_name_id]?->name }}</span>
                                                <span class="font-normal">{{ $examTypes[$configuration->exam_type_id]?->name }} / {{ $examParts[$configuration->exam_part_id]?->name }}</span>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($mappedSubjects as $mappedSubject)
                                        @php($subject = $subjects[$mappedSubject->subject_id] ?? null)
                                        @if ($subject)
                                            <tr wire:key="marks-entry-row-{{ $shrenySection->id }}-{{ $subject->id }}" class="align-top hover:bg-slate-50/60">
                                                <td class="sticky left-0 bg-white px-4 py-4 font-medium text-slate-900">{{ $subject->name }}</td>
                                                @foreach ($configurations as $configuration)
                                                    @php($combinationKey = $shrenySection->shreny_id . ':' . $subject->id . ':' . $configuration->exam_name_id . ':' . $configuration->exam_type_id . ':' . $configuration->exam_part_id)
                                                    @php($isAllotted = isset($examAssignments[$combinationKey]))
                                                    <td class="px-4 py-3">
                                                        @if ($isAllotted)
                                                            <button type="button" wire:click="openEntry({{ $shrenySection->shreny_id }}, {{ $shrenySection->section_id }}, {{ $subject->id }}, {{ $configuration->exam_name_id }}, {{ $configuration->exam_type_id }}, {{ $configuration->exam_part_id }})" class="w-full rounded border border-slate-300 px-3 py-2 text-left text-xs text-cyan-700 hover:border-cyan-500 hover:bg-cyan-50">Enter marks</button>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endif
                                    @empty
                                        <tr><td colspan="{{ 1 + $configurations->count() }}" class="px-4 py-8 text-center text-sm text-slate-500">No subjects selected for examination.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif
            @empty
                <div class="rounded-lg border border-slate-200 bg-white px-4 py-8 text-center text-sm text-slate-500">No Shreny-Section combinations found for the active session.</div>
            @endforelse
        </div>
    @else
        <section class="overflow-hidden rounded-lg border border-slate-200 bg-white">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-slate-50 px-4 py-3">
                <div>
                    <button type="button" wire:click="closeEntry" class="mb-2 text-xs font-semibold text-cyan-700 hover:text-cyan-900">&larr; Back to combinations</button>
                    <h3 class="font-semibold text-slate-900">{{ $selectedShreny?->name }} <span class="font-normal text-slate-500">&amp;</span> {{ $selectedSection?->name }}</h3>
                    <p class="text-xs text-slate-500">{{ $selectedSubject?->name }} · {{ $examNames[$selectedExamNameId]?->name }} / {{ $examTypes[$selectedExamTypeId]?->name }} / {{ $examParts[$selectedExamPartId]?->name }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if ($isIssued)
                        <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-800">Issued</span>
                    @endif
                    @if ($isFinalized)
                        <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">Finalized</span>
                    @endif
                    @if ($isIssued)
                        <button type="button" wire:click="unfinalizeMarks" class="rounded border border-amber-300 px-3 py-2 text-xs font-semibold text-amber-800 hover:bg-amber-50">Unfinalize</button>
                    @elseif (!$isFinalized)
                        <button type="button" wire:click="finalizeMarks" class="rounded bg-cyan-600 px-3 py-2 text-xs font-semibold text-white hover:bg-cyan-700">Finalize marks</button>
                    @endif
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-[85rem] text-left text-sm">
                    <thead class="bg-white text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="w-20 border-b border-slate-200 px-4 py-3">Roll</th>
                            <th class="min-w-56 border-b border-slate-200 px-4 py-3">Student</th>
                            @foreach ($selectedCombinations as $combination)
                                @php($combinationPrefix = $combination->exam_name_id . ':' . $combination->exam_type_id . ':' . $combination->exam_part_id . ':')
                                @php($combinationTeacher = $selectedCombinationTeachers[$combination->exam_name_id . ':' . $combination->exam_type_id . ':' . $combination->exam_part_id] ?? null)
                                @php($combinationState = $selectedCombinationStates[$combinationPrefix] ?? ['is_finalized' => false, 'is_issued' => false])
                                @php($isCurrentCombination = $combination->exam_name_id === $selectedExamNameId && $combination->exam_type_id === $selectedExamTypeId && $combination->exam_part_id === $selectedExamPartId)
                                <th class="min-w-64 border-b border-slate-200 px-4 py-3 {{ $isCurrentCombination ? 'bg-cyan-50' : '' }}">
                                    <span class="block text-slate-900">{{ $examNames[$combination->exam_name_id]?->name }}</span>
                                    <span class="font-normal">{{ $examTypes[$combination->exam_type_id]?->name }} / {{ $examParts[$combination->exam_part_id]?->name }}</span>
                                    <span class="mt-1 block normal-case font-medium text-cyan-700">Teacher: {{ $combinationTeacher?->teacher_id ? ($teachers[$combinationTeacher->teacher_id]?->name ?? 'Not assigned') : 'Not assigned' }}</span>
                                    <span class="mt-1 block normal-case font-medium {{ $combinationState['is_finalized'] ? 'text-emerald-700' : 'text-amber-700' }}">{{ $combinationState['is_finalized'] ? 'Finalized' : 'Unfinalized' }}{{ $combinationState['is_issued'] ? ' · Issued' : '' }}</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($selectedStudents as $student)
                            <tr wire:key="mark-student-{{ $student->id }}" class="hover:bg-slate-50/60">
                                <td class="px-4 py-3 text-slate-500">{{ $student->curr_roll_no }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $student->student?->name }}</td>
                                @foreach ($selectedCombinations as $combination)
                                    @php($combinationPrefix = $combination->exam_name_id . ':' . $combination->exam_type_id . ':' . $combination->exam_part_id . ':')
                                    @php($isCurrentCombination = $combination->exam_name_id === $selectedExamNameId && $combination->exam_type_id === $selectedExamTypeId && $combination->exam_part_id === $selectedExamPartId)
                                    @php($combinationEntry = $selectedCombinationEntries[$combinationPrefix . $student->id] ?? null)
                                    <td class="px-4 py-3 {{ $isCurrentCombination ? 'bg-cyan-50/40' : '' }}">
                                        @if ($isCurrentCombination)
                                            <div class="flex max-w-sm items-center gap-3">
                                                @if ($absent[$student->id] ?? false)
                                                    <input type="text" value="AB" disabled class="w-32 rounded border-rose-300 bg-rose-50 px-3 py-2 text-sm font-semibold text-rose-600">
                                                @else
                                                    <input type="number" min="0" step="1" wire:model="marks.{{ $student->id }}" wire:change="saveMark({{ $student->id }})" @disabled($isFinalized) class="w-32 rounded border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:ring-cyan-500">
                                                @endif
                                                <label class="flex items-center gap-2 text-xs font-semibold {{ ($absent[$student->id] ?? false) ? 'text-rose-600' : 'text-slate-600' }}"><input type="checkbox" wire:model.live="absent.{{ $student->id }}" wire:change="saveMark({{ $student->id }})" @disabled($isFinalized) class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"> AB</label>
                                            </div>
                                        @elseif ($combinationEntry)
                                            <span class="font-medium {{ $combinationEntry->obtained_marks === -99 ? 'text-rose-600' : 'text-slate-700' }}">{{ $combinationEntry->obtained_marks === -99 ? 'AB' : $combinationEntry->obtained_marks }}</span>
                                        @else
                                            <span class="text-xs text-slate-400">Not entered</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr><td colspan="{{ 2 + $selectedCombinations->count() }}" class="px-4 py-8 text-center text-sm text-slate-500">No students are assigned to this Shreny and Section.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif
</div>
