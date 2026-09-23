<div class="space-y-5">
    <header class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">Academic setup</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">Shreny sections</h1>
            <p class="mt-1 text-sm text-slate-500">Assign available sections to each shreny.</p>
        </div>

        <label
            class="flex cursor-pointer items-center gap-3 self-start rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">
            <span class="font-medium">Assigned only</span>
            <button type="button" wire:click="toggleAssignedOnly" role="switch"
                aria-checked="{{ $showAssignedOnly ? 'true' : 'false' }}"
                class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition {{ $showAssignedOnly ? 'bg-cyan-600' : 'bg-slate-300' }}">
                <span class="sr-only">Toggle assigned sections filter</span>
                <span
                    class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition {{ $showAssignedOnly ? 'translate-x-6' : 'translate-x-1' }}"></span>
            </button>
        </label>
        <button type="button" wire:click="toggleMutations" role="switch" aria-checked="{{ $mutationsEnabled ? 'true' : 'false' }}" class="rounded border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">{{ $mutationsEnabled ? 'Editing enabled' : 'Enable editing' }}</button>
    </header>

    @if (session('success'))
    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" role="status">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($shrenies as $shreny)
        @php($selectedSections = $assignedSections[$shreny->id] ?? [])
        <section class="overflow-hidden rounded-lg border border-slate-200 bg-white">
            <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-4 py-3">
                <div>
                    <h2 class="font-semibold text-slate-900">{{ $shreny->name }}</h2>
                    @if ($shreny->desc)
                    <p class="mt-0.5 text-xs text-slate-500">{{ $shreny->desc }}</p>
                    @endif
                </div>
                <span class="rounded-full bg-cyan-50 px-2 py-1 text-xs font-semibold text-cyan-700">
                    {{ count($selectedSections) }} assigned
                </span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($sections as $section)
                <label class="flex cursor-pointer items-center gap-3 px-4 py-3 text-sm transition hover:bg-slate-50">
                    <input type="checkbox" wire:click="toggleAssignment({{ $shreny->id }}, {{ $section->id }})"
                        @checked(in_array($section->id, $selectedSections, true))
                        @disabled(!$mutationsEnabled)
                    class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"
                    >
                    <span class="flex-1 text-slate-700">{{ $section->name }}</span>
                    @if ($section->desc)
                    <span class="text-xs text-slate-400">{{ $section->desc }}</span>
                    @endif
                </label>
                @empty
                <p class="px-4 py-6 text-center text-sm text-slate-500">No sections available.</p>
                @endforelse
            </div>
        </section>
        @empty
        <div
            class="rounded-lg border border-dashed border-slate-300 bg-white px-5 py-10 text-center text-sm text-slate-500 md:col-span-2 xl:col-span-3">
            No shrenies available.
        </div>
        @endforelse
    </div>

    
</div>