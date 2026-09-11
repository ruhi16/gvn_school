<div class="space-y-5">
    <header class="border-b border-slate-200 pb-5">
        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">Exam Settings</p>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">Exam marks and time</h1>
        <p class="mt-1 text-sm text-slate-500">Set full marks, pass marks, and allotted minutes for every shreny subject.</p>
    </header>

    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
        <table class="min-w-[78rem] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="w-14 px-3 py-3">SL</th>
                    <th class="sticky left-0 min-w-40 bg-slate-50 px-3 py-3">Shreny</th>
                    <th class="sticky left-40 min-w-44 bg-slate-50 px-3 py-3">Subject</th>
                    @foreach ($configurations as $configuration)
                    <th class="min-w-64 px-3 py-3">
                        <span class="block text-slate-900">{{ $examNames[$configuration->exam_name_id]?->name }}</span>
                        <span class="font-normal">{{ $examTypes[$configuration->exam_type_id]?->name }} / {{ $examParts[$configuration->exam_part_id]?->name }}</span>
                        @if ($configuration->exam_mode_id)<span class="block font-normal text-cyan-700">Mode selected</span>@endif
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @php($serial = 0)
                @forelse ($shrenies as $shreny)
                    @foreach (($shrenySubjects[$shreny->id] ?? collect()) as $shrenySubject)
                        @if ($subjects->has($shrenySubject->subject_id))
                        @php($serial++)
                        <tr wire:key="marks-{{ $shreny->id }}-{{ $shrenySubject->subject_id }}" class="align-top hover:bg-slate-50/60">
                            <td class="px-3 py-4 text-slate-500">{{ $serial }}</td>
                            <td class="sticky left-0 bg-white px-3 py-4 font-semibold text-slate-900">{{ $shreny->name }}</td>
                            <td class="sticky left-40 bg-white px-3 py-4 text-slate-700">{{ $subjects[$shrenySubject->subject_id]->name }}</td>
                            @foreach ($configurations as $configuration)
                            @php($key = $shreny->id . ':' . $shrenySubject->subject_id . ':' . $configuration->exam_name_id . ':' . $configuration->exam_type_id . ':' . $configuration->exam_part_id)
                            @php($mark = $marks[$key] ?? null)
                            <td class="px-3 py-3">
                                <div class="grid grid-cols-3 gap-2">
                                    <label class="text-[10px] font-semibold uppercase text-slate-500">Full<input type="number" min="0" step="1" value="{{ $mark?->full_marks }}" wire:change="updateMarks({{ $configuration->id }}, {{ $shreny->id }}, {{ $shrenySubject->subject_id }}, 'full_marks', $event.target.value)" class="mt-1 w-full rounded border-slate-300 px-2 py-1.5 text-sm text-slate-900 focus:border-cyan-500 focus:ring-cyan-500"></label>
                                    <label class="text-[10px] font-semibold uppercase text-slate-500">Pass<input type="number" min="0" step="1" value="{{ $mark?->pass_marks }}" wire:change="updateMarks({{ $configuration->id }}, {{ $shreny->id }}, {{ $shrenySubject->subject_id }}, 'pass_marks', $event.target.value)" class="mt-1 w-full rounded border-slate-300 px-2 py-1.5 text-sm text-slate-900 focus:border-cyan-500 focus:ring-cyan-500"></label>
                                    <label class="text-[10px] font-semibold uppercase text-slate-500">Minutes<input type="number" min="0" step="1" value="{{ $mark?->time_alloted }}" wire:change="updateMarks({{ $configuration->id }}, {{ $shreny->id }}, {{ $shrenySubject->subject_id }}, 'time_alloted', $event.target.value)" class="mt-1 w-full rounded border-slate-300 px-2 py-1.5 text-sm text-slate-900 focus:border-cyan-500 focus:ring-cyan-500"></label>
                                </div>
                            </td>
                            @endforeach
                        </tr>
                        @endif
                    @endforeach
                @empty
                <tr><td colspan="{{ 3 + $configurations->count() }}" class="px-5 py-10 text-center text-sm text-slate-500">No shreny subjects available.</td></tr>
                @endforelse
                @if ($serial === 0)
                <tr><td colspan="{{ 3 + $configurations->count() }}" class="px-5 py-10 text-center text-sm text-slate-500">No shreny subjects available.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>