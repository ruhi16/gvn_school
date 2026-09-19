<div class="space-y-5">
    <header class="relative flex items-end justify-between gap-3 border-b border-slate-200 pb-5">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">Student reports</p>
            <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">Exam marks sheets</h2>
            <p class="mt-1 text-sm text-slate-500">{{ $session?->name ?? 'No active session' }}</p>
            <button type="button" wire:click="toggleMutations" role="switch" aria-checked="{{ $mutationsEnabled ? 'true' : 'false' }}" class="mt-3 rounded border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">{{ $mutationsEnabled ? 'Editing enabled' : 'View only' }}</button>
        </div>
    </header>

    @if (!$session)
    <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">No active session is configured.</div>
    @elseif ($groups->isEmpty())
    <div class="rounded-lg border border-slate-200 bg-white px-4 py-8 text-center text-sm text-slate-500">No StudentCr records found for this session.</div>
    @else
    @foreach ($groups as $group)
    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white">
        <header class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-4 py-3">
            <h3 class="font-semibold text-slate-900">{{ $group['shreny']->name }} <span class="font-normal text-slate-500">&amp;</span> {{ $group['section']->name }}</h3>
            <span class="text-xs text-slate-500">{{ $group['students']->count() }} students</span>
        </header>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-white text-xs uppercase tracking-wider text-slate-500">
                    <tr><th class="px-4 py-3">Roll No</th><th class="px-4 py-3">Student</th><th class="px-4 py-3 text-right">Report</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($group['students'] as $studentCr)
                    <tr class="hover:bg-slate-50/60">
                        <td class="px-4 py-3 text-slate-500">{{ $studentCr->curr_roll_no ?? '-' }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $studentCr->student?->name ?? 'Unnamed student' }}</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('admin.exam-marks.sheet', $studentCr) }}" class="rounded bg-cyan-600 px-3 py-2 text-xs font-semibold text-white hover:bg-cyan-700">Open marks sheet</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endforeach
    @endif
</div>
