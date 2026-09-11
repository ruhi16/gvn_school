<div class="space-y-8">
    <section>
        <header class="mb-4 border-b border-slate-200 pb-4">
            <h2 class="text-lg font-semibold text-slate-950">Exam type and part setup</h2>
            <p class="mt-1 text-sm text-slate-500">Select exam types, then choose their parts and mode.</p>
        </header>
        <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="w-16 px-4 py-3">SL</th><th class="min-w-40 px-4 py-3">Exam name</th><th class="min-w-72 px-4 py-3">Exam types</th><th class="min-w-[32rem] px-4 py-3">Exam parts and mode</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($examNames as $examName)
                    <tr wire:key="exam-name-{{ $examName->id }}" class="align-top hover:bg-slate-50/60">
                        <td class="px-4 py-4 font-medium text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-4 font-semibold text-slate-900">{{ $examName->name }}</td>
                        <td class="px-4 py-2"><div class="grid gap-x-5 sm:grid-cols-2">
                            @forelse ($examTypes as $examType)
                            @php($typeSelected = in_array($examType->id, $typeIds[$examName->id] ?? [], true))
                            <label class="flex cursor-pointer items-center gap-2 border-b border-slate-100 px-1 py-3"><input type="checkbox" wire:click="toggleExamType({{ $examName->id }}, {{ $examType->id }})" @checked($typeSelected) class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"><span>{{ $examType->name }}</span></label>
                            @empty <span class="py-3 text-slate-500">No exam types available.</span> @endforelse
                        </div></td>
                        <td class="px-4 py-2"><div class="space-y-2">
                            @foreach ($examTypes as $examType)
                            @if (in_array($examType->id, $typeIds[$examName->id] ?? [], true))
                            <div class="border-b border-slate-100 pb-2 last:border-0"><p class="pt-2 text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $examType->name }}</p><div class="grid gap-x-4 sm:grid-cols-2">
                                @foreach ($examParts as $examPart)
                                @php($configuration = $selectedParts[$examName->id . ':' . $examType->id][$examPart->id] ?? null)
                                <div class="flex items-center gap-2 py-2"><label class="flex min-w-0 flex-1 cursor-pointer items-center gap-2"><input type="checkbox" wire:click="toggleExamPart({{ $examName->id }}, {{ $examType->id }}, {{ $examPart->id }})" @checked($configuration) class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"><span class="truncate">{{ $examPart->name }}</span></label>@if ($configuration)<select wire:change="setExamMode({{ $configuration->id }}, $event.target.value)" class="w-28 rounded border-slate-300 px-2 py-1 text-xs"><option value="">Mode</option>@foreach ($examModes as $examMode)<option value="{{ $examMode->id }}" @selected($configuration->exam_mode_id === $examMode->id)>{{ $examMode->name }}</option>@endforeach</select>@endif</div>
                                @endforeach
                            </div></div>
                            @endif
                            @endforeach
                        </div></td>
                    </tr>
                    @empty <tr><td colspan="4" class="px-5 py-10 text-center text-sm text-slate-500">No exam names available.</td></tr> @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section>
        <header class="mb-4 border-b border-slate-200 pb-4"><h2 class="text-lg font-semibold text-slate-950">Shreny exam subjects</h2><p class="mt-1 text-sm text-slate-500">Assign subjects for each configured exam part.</p></header>
        <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white"><table class="min-w-[70rem] text-left text-sm"><thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="w-14 px-3 py-3">SL</th><th class="sticky left-0 min-w-36 bg-slate-50 px-3 py-3">Shreny</th>@foreach ($configurations as $configuration)<th class="min-w-36 px-3 py-3"><span class="block text-slate-900">{{ $examNames->firstWhere('id', $configuration->exam_name_id)?->name }}</span><span class="font-normal">{{ $examTypes->firstWhere('id', $configuration->exam_type_id)?->name }} / {{ $examParts->firstWhere('id', $configuration->exam_part_id)?->name }}</span>@if ($configuration->exam_mode_id)<span class="block font-normal text-cyan-700">{{ $examModes->firstWhere('id', $configuration->exam_mode_id)?->name }}</span>@endif</th>@endforeach</tr></thead><tbody class="divide-y divide-slate-100">@forelse ($shrenies as $shreny)<tr wire:key="settings-shreny-{{ $shreny->id }}" class="align-top"><td class="px-3 py-4 text-slate-500">{{ $loop->iteration }}</td><td class="sticky left-0 bg-white px-3 py-4 font-semibold text-slate-900">{{ $shreny->name }}</td>@foreach ($configurations as $configuration)@php($assignedKey = $shreny->id . ':' . $configuration->exam_name_id . ':' . $configuration->exam_type_id . ':' . $configuration->exam_part_id)<td class="px-3 py-3"><div class="space-y-1">@foreach ($subjects as $subject)<label class="flex cursor-pointer items-center gap-2 whitespace-nowrap"><input type="checkbox" wire:click="toggleSubject({{ $configuration->id }}, {{ $shreny->id }}, {{ $subject->id }})" @checked(isset($assignedSubjects[$assignedKey]) && $assignedSubjects[$assignedKey]->contains('subject_id', $subject->id)) class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"><span>{{ $subject->name }}</span></label>@endforeach</div></td>@endforeach</tr>@empty<tr><td colspan="{{ 2 + $configurations->count() }}" class="px-5 py-10 text-center text-sm text-slate-500">No shrenies available.</td></tr>@endforelse</tbody></table></div>
    </section>
</div>