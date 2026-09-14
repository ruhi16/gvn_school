<div class="space-y-5">
    <header class="flex flex-wrap items-end justify-between gap-3 border-b border-slate-200 pb-5">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">Academic records</p>
            <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">Exam marks register</h2>
            <p class="mt-1 text-sm text-slate-500">{{ $session?->name ?? 'No active session' }}</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="flex rounded border border-slate-300 bg-white p-0.5">
                <button type="button" wire:click="setViewMode('compact')" class="rounded px-2 py-1 text-xs {{ $viewMode === 'compact' ? 'bg-slate-900 text-white' : 'text-slate-600' }}">Compact</button>
                <button type="button" wire:click="setViewMode('classic')" class="rounded px-2 py-1 text-xs {{ $viewMode === 'classic' ? 'bg-slate-900 text-white' : 'text-slate-600' }}">Classic</button>
            </div>
            <a href="{{ route('admin.exam-marks.register.pdf') }}" target="_blank" rel="noopener" class="rounded bg-cyan-600 px-3 py-2 text-xs font-semibold text-white hover:bg-cyan-700">Download PDF</a>
        </div>
    </header>

    @if (!$session)
    <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">No active session is configured.</div>
    @elseif ($groups->isEmpty())
    <div class="rounded-lg border border-slate-200 bg-white px-4 py-8 text-center text-sm text-slate-500">No Shreny-Section combinations found.</div>
    @else
    @foreach ($groups as $group)
    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
            <h3 class="font-semibold text-slate-900">{{ $group['shreny']->name }} <span class="font-normal text-slate-500">&amp;</span> {{ $group['section']->name }}</h3>
            <p class="mt-1 text-xs text-slate-500">Each student has one row for every exam combination. Subjects follow ExamType order.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-max text-left {{ $viewMode === 'compact' ? 'text-[10px]' : 'text-xs' }}">
                <thead class="bg-white uppercase tracking-wider text-slate-500">
                    <tr>
                        <th rowspan="2" class="sticky left-0 z-10 border-b border-r border-slate-200 bg-white px-2 py-2">Roll</th>
                        <th rowspan="2" class="sticky left-12 z-10 min-w-48 border-b border-r border-slate-200 bg-white px-2 py-2">Student</th>
                        <th rowspan="2" class="min-w-48 border-b border-r border-slate-200 bg-white px-2 py-2">Exam combination</th>
                        @foreach ($group['subjects'] as $registerSubject)
                        <th class="border-b border-r border-slate-200 bg-cyan-50 px-3 py-2 text-center text-cyan-800">{{ $registerSubject['subject']->name }}<span class="block normal-case font-semibold text-slate-900">FM: {{ $registerSubject['display_full_marks'] ?? 'Varies' }}</span></th>
                        @endforeach
                        <th rowspan="2" class="min-w-24 border-b border-r border-slate-200 bg-slate-50 px-2 py-2 text-center">Overall total</th>
                        <th rowspan="2" class="min-w-16 border-b border-slate-200 bg-slate-50 px-2 py-2 text-center">Grade</th>
                    </tr>
                    <tr>
                        @foreach ($group['subjects'] as $registerSubject)
                        <th class="border-b border-r border-slate-200 px-2 py-1 text-center normal-case font-normal">{{ $registerSubject['full_marks'] }} total FM</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($group['students'] as $student)
                    @php($studentTotal = 0)
                    @php($studentFullMarks = 0)
                    @php($studentHasMark = false)
                    @foreach ($group['combinations'] as $combination)
                    @php($combinationTotal = 0)
                    <tr class="hover:bg-slate-50/60">
                        <td class="sticky left-0 z-10 border-r border-slate-200 bg-white px-2 py-2 text-slate-500">{{ $student->curr_roll_no }}</td>
                        <td class="sticky left-12 z-10 min-w-48 border-r border-slate-200 bg-white px-2 py-2 font-medium text-slate-900"><a href="{{ route('admin.exam-marks.sheet', $student) }}" class="hover:text-cyan-700">{{ $student->student?->name }}</a></td>
                        <td class="border-r border-slate-200 px-2 py-2 font-medium text-slate-700">{{ $examNames[$combination->exam_name_id]?->name }} / {{ $examTypes[$combination->exam_type_id]?->name }} / {{ $examParts[$combination->exam_part_id]?->name }}</td>
                        @foreach ($group['subjects'] as $registerSubject)
                        @php($part = $registerSubject['assignments']->first(fn ($assignment) => $assignment->exam_name_id === $combination->exam_name_id && $assignment->exam_type_id === $combination->exam_type_id && $assignment->exam_part_id === $combination->exam_part_id))
                        @php($mark = $part ? $registerData->mark($group['entries'], $student->id, $registerSubject['subject']->id, $part) : null)
                        @php($studentHasMark = $studentHasMark || $mark !== null)
                        @php($combinationTotal += is_numeric($mark) ? $mark : 0)
                        <td class="border-r border-slate-100 px-2 py-2 text-center">{{ $mark ?? '-' }}</td>
                        @endforeach
                        @php($studentTotal += $combinationTotal)
                        <td class="border-r border-slate-200 bg-slate-50 px-2 py-2 text-center font-semibold">{{ $combinationTotal }}</td>
                        <td class="bg-slate-50 px-2 py-2 text-center">-</td>
                    </tr>
                    @endforeach
                    @foreach ($group['subjects'] as $registerSubject)
                    @php($studentFullMarks += $registerSubject['full_marks'])
                    @endforeach
                    <tr class="bg-cyan-50/50 font-semibold">
                        <td class="sticky left-0 z-10 border-r border-slate-200 bg-cyan-50/50 px-2 py-2"></td>
                        <td class="sticky left-12 z-10 border-r border-slate-200 bg-cyan-50/50 px-2 py-2"></td>
                        <td class="border-r border-slate-200 px-2 py-2">Overall</td>
                        @foreach ($group['subjects'] as $registerSubject)
                        @php($subjectTotal = 0)
                        @php($subjectHasMark = false)
                        @foreach ($group['combinations'] as $combination)
                        @php($part = $registerSubject['assignments']->first(fn ($assignment) => $assignment->exam_name_id === $combination->exam_name_id && $assignment->exam_type_id === $combination->exam_type_id && $assignment->exam_part_id === $combination->exam_part_id))
                        @php($mark = $part ? $registerData->mark($group['entries'], $student->id, $registerSubject['subject']->id, $part) : null)
                        @php($subjectHasMark = $subjectHasMark || $mark !== null)
                        @php($subjectTotal += is_numeric($mark) ? $mark : 0)
                        @endforeach
                        <td class="border-r border-slate-200 px-2 py-2 text-center">{{ $subjectHasMark ? $subjectTotal : '-' }}</td>
                        @endforeach
                        <td class="border-r border-slate-200 px-2 py-2 text-center">{{ $studentHasMark ? $studentTotal : '-' }}</td>
                        <td class="px-2 py-2 text-center">{{ $studentHasMark ? ($registerData->grade($studentTotal, $studentFullMarks, collect()) ?? '-') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endforeach
    @endif
</div>
