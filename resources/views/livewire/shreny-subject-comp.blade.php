<div class="space-y-5">
    <header class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">Academic setup</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">Shreny subjects</h1>
            <p class="mt-1 text-sm text-slate-500">Assign available subjects to each shreny.</p>
        </div>
        <label class="flex cursor-pointer items-center gap-3 self-start rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">
            <span class="font-medium">Assigned only</span>
            <button type="button" wire:click="toggleAssignedOnly" role="switch" aria-checked="{{ $showAssignedOnly ? 'true' : 'false' }}"
                class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition {{ $showAssignedOnly ? 'bg-cyan-600' : 'bg-slate-300' }}">
                <span class="sr-only">Toggle assigned subjects filter</span>
                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition {{ $showAssignedOnly ? 'translate-x-6' : 'translate-x-1' }}"></span>
            </button>
        </label>
    </header>

    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr><th class="w-16 px-4 py-3 font-semibold">SL</th><th class="min-w-48 px-4 py-3 font-semibold">Shreny</th><th class="px-4 py-3 font-semibold">Subjects</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($shrenies as $shreny)
                @php($selectedSubjects = $assignedSubjects[$shreny->id] ?? [])
                <tr wire:key="shreny-subjects-{{ $shreny->id }}" class="align-top hover:bg-slate-50/60">
                    <td class="px-4 py-4 font-medium text-slate-500">{{ $loop->iteration }}</td>
                    <td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $shreny->name }}</p>
                        @if ($shreny->desc)<p class="mt-0.5 text-xs text-slate-500">{{ $shreny->desc }}</p>@endif
                        <p class="mt-2 text-xs font-medium text-cyan-700">{{ count($selectedSubjects) }} assigned</p>
                    </td>
                    <td class="px-4 py-2"><div class="grid gap-x-6 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse ($subjects as $subject)
                        @if (!$showAssignedOnly || in_array($subject->id, $selectedSubjects, true))
                        <label class="flex cursor-pointer items-center gap-3 border-b border-slate-100 px-1 py-3 text-sm transition hover:bg-slate-50">
                            <input type="checkbox" wire:click="toggleAssignment({{ $shreny->id }}, {{ $subject->id }})" @checked(in_array($subject->id, $selectedSubjects, true)) class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                            <span class="text-slate-700">{{ $subject->name }}</span>
                        </label>
                        @endif
                        @empty
                        <p class="px-1 py-4 text-sm text-slate-500">No subjects available.</p>
                        @endforelse
                        @if ($showAssignedOnly && count($selectedSubjects) === 0)<p class="px-1 py-4 text-sm text-slate-500">No subjects assigned.</p>@endif
                    </div></td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-5 py-10 text-center text-sm text-slate-500">No shrenies available.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>