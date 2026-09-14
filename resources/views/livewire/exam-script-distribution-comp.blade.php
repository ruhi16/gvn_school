<div class="space-y-5">
    <header class="border-b border-slate-200 pb-5">
        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">Exam settings</p>
        <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">Exam script distribution</h2>
        <p class="mt-1 text-sm text-slate-500">Assign subject scripts to teachers for each Shreny and Section.</p>
    </header>

    @if (!$session)
    <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">No active session is
        configured.</div>
    @elseif ($configurations->isEmpty())
    <div class="rounded-lg border border-slate-200 bg-white px-4 py-8 text-center text-sm text-slate-500">No exam
        combinations are configured.</div>
    @else
    <div class="space-y-6">
        @forelse ($shrenySections as $shrenySection)
        @php($shreny = $shrenies[$shrenySection->shreny_id] ?? null)
        @php($section = $sections[$shrenySection->section_id] ?? null)
        @php($mappedSubjects = $examSubjects[$shrenySection->shreny_id] ?? collect())
        @if ($shreny && $section)
        <section wire:key="script-distribution-{{ $shrenySection->id }}"
            class="overflow-hidden rounded-lg border border-slate-200 bg-white">
            <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-4 py-3">
                <h3 class="font-semibold text-slate-900">{{ $shreny->name }} <span
                        class="font-normal text-slate-500">&amp;</span> {{ $section->name }}</h3>
                <span class="text-xs text-slate-500">{{ $session->name }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-[70rem] text-left text-sm">
                    <thead class="bg-white text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="sticky left-0 min-w-52 border-b border-slate-200 bg-white px-4 py-3">Subject</th>
                            @foreach ($configurations as $configuration)
                            <th class="min-w-48 border-b border-slate-200 px-4 py-3">
                                <span class="block text-slate-900">{{ $examNames[$configuration->exam_name_id]?->name
                                    }}</span>
                                <span class="font-normal">{{ $examTypes[$configuration->exam_type_id]?->name }} / {{
                                    $examParts[$configuration->exam_part_id]?->name }}</span>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($mappedSubjects as $mappedSubject)
                        @php($subject = $subjects[$mappedSubject->subject_id] ?? null)
                        @if ($subject)
                        <tr wire:key="script-row-{{ $shrenySection->id }}-{{ $subject->id }}"
                            class="align-top hover:bg-slate-50/60">
                            <td class="sticky left-0 bg-white px-4 py-4 font-medium text-slate-900">{{ $subject->name }}
                            </td>
                            @foreach ($configurations as $configuration)
                            @php($key = $shrenySection->shreny_id . ':' . $shrenySection->section_id . ':' .
                            $subject->id . ':' . $configuration->exam_name_id . ':' . $configuration->exam_type_id . ':'
                            . $configuration->exam_part_id)
                            @php($distribution = $distributions[$key] ?? null)
                            <td class="px-4 py-3">
                                <button type="button"
                                    wire:click="openTeacherModal({{ $shrenySection->shreny_id }}, {{ $shrenySection->section_id }}, {{ $subject->id }}, {{ $configuration->exam_name_id }}, {{ $configuration->exam_type_id }}, {{ $configuration->exam_part_id }})"
                                    class="w-full rounded border border-slate-300 px-3 py-2 text-left text-xs hover:border-cyan-500 hover:bg-cyan-50">
                                    @if ($distribution && isset($teachers[$distribution->teacher_id]))
                                    <span class="font-medium text-cyan-700">{{
                                        $teachers[$distribution->teacher_id]->name }}</span>
                                    @else
                                    <span class="text-slate-400">Select teacher</span>
                                    @endif
                                </button>
                            </td>
                            @endforeach
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="{{ 1 + $configurations->count() }}"
                                class="px-4 py-8 text-center text-sm text-slate-500">No subjects selected for
                                examination.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
        @endif
        @empty
        <div class="rounded-lg border border-slate-200 bg-white px-4 py-8 text-center text-sm text-slate-500">No
            Shreny-Section combinations found for the active session.</div>
        @endforelse
    </div>
    @endif

    @if ($showModal)
    <div class="fixed inset-0 z-50 grid place-items-center bg-slate-950/40 p-4" wire:click.self="closeModal">
        <section class="w-full max-w-md rounded-lg bg-white p-5 shadow-xl" role="dialog" aria-modal="true"
            aria-labelledby="teacher-modal-title">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-cyan-600">Script distribution</p>
                    <h3 id="teacher-modal-title" class="mt-1 text-lg font-semibold text-slate-950">Select teacher</h3>
                </div>
                <button type="button" wire:click="closeModal"
                    class="text-xl leading-none text-slate-400 hover:text-slate-700" aria-label="Close">&times;</button>
            </div>
            <div class="mt-5">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Teacher</label>
                <select wire:model="selectedTeacherId"
                    class="mt-2 w-full rounded border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-cyan-500 focus:ring-cyan-500">
                    <option value="">Select teacher</option>
                    @foreach (($teacherSubjectIds[$selectedSubjectId] ?? collect()) as $teacherId)
                    @if (isset($teachers[$teacherId]))
                    <option value="{{ $teacherId }}">{{ $teachers[$teacherId]->name }}</option>
                    @endif
                    @endforeach
                </select>
                @error('selectedTeacherId')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" wire:click="closeModal"
                    class="rounded border border-slate-300 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="button" wire:click="saveTeacherAssignment"
                    class="rounded bg-cyan-600 px-3 py-2 text-sm font-semibold text-white hover:bg-cyan-700">Save
                    assignment</button>
            </div>
        </section>
    </div>
    @endif
</div>