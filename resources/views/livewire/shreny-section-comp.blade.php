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

    <section class="space-y-4 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-200 pb-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">Student allocation</p>
                <h2 class="mt-1 text-lg font-semibold text-slate-950">Assign roll numbers</h2>
                <p class="mt-1 text-xs text-slate-500">Students are loaded from active admissions matching the selected
                    Shreny and Section.</p>
            </div>
            <div class="grid gap-2 sm:grid-cols-2">
                <label><span
                        class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Shreny</span><select
                        wire:model.live="selectedShrenyId" class="w-full rounded border-slate-300 px-3 py-2 text-xs">
                        <option value="">Select Shreny</option>@foreach($shrenies as $shreny)<option
                            value="{{ $shreny->id }}">{{ $shreny->name }}</option>@endforeach
                    </select></label>
                <label><span
                        class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Section</span><select
                        wire:model.live="selectedSectionId" class="w-full rounded border-slate-300 px-3 py-2 text-xs">
                        <option value="">Select Section</option>@foreach($sections as $section)<option
                            value="{{ $section->id }}">{{ $section->name }}</option>@endforeach
                    </select></label>
            </div>
        </div>

        @if($selectedShrenyId && $selectedSectionId)
        <div class="flex flex-wrap items-center justify-between gap-3 rounded bg-slate-50 px-3 py-2">
            <span class="text-xs text-slate-600">{{ $students->count() }} admitted student(s) · {{ count($assignedRolls)
                }} assigned</span>
            <div class="flex gap-2"><button type="button" wire:click="assignAutomatically"
                    class="rounded bg-cyan-600 px-3 py-2 text-xs font-semibold text-white hover:bg-cyan-500">Auto assign
                    1–{{ $students->count() }}</button><button type="button" wire:click="assignManually"
                    class="rounded border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-white">Save
                    manual rolls</button></div>
        </div>
        @error('rollNumbers')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
        <div class="overflow-x-auto rounded border border-slate-200">
            <table class="w-full min-w-[680px] text-left text-xs">
                <thead
                    class="border-b border-slate-200 bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-3 py-2">Student</th>
                        <th class="px-3 py-2">Father / guardian</th>
                        <th class="px-3 py-2">Admission ID</th>
                        <th class="px-3 py-2">Roll no.</th>
                        <th class="px-3 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">@forelse($students as $student)<tr>
                        <td class="px-3 py-2 font-semibold text-slate-900">{{ $student->name }}</td>
                        <td class="px-3 py-2 text-slate-600">{{ $student->fname ?: '-' }}</td>
                        <td class="px-3 py-2 text-slate-500">#{{ $student->id }}</td>
                        <td class="px-3 py-2"><input wire:model="rollNumbers.{{ $student->id }}" type="number" min="1"
                                class="w-24 rounded border-slate-300 px-2 py-1.5 text-xs" placeholder="Unassigned">
                            @error("rollNumbers.{$student->id}")<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </td>
                        <td class="px-3 py-2 text-right">
                            <div class="flex justify-end gap-3">
                                <button type="button" wire:click="updateRollNumber({{ $student->id }})"
                                    class="font-semibold text-cyan-700 hover:text-cyan-600">Update</button>
                                <a href="{{ route('admin.students.pdf', $student) }}" target="_blank" rel="noopener"
                                    class="font-semibold text-slate-600 hover:text-slate-900">PDF</a>
                                @if(isset($assignedRolls[$student->id]))<button type="button"
                                    wire:click="removeAssignment({{ $student->id }})"
                                    class="font-semibold text-rose-600 hover:text-rose-500">Remove</button>@endif
                            </div>
                        </td>
                    </tr>@empty<tr>
                        <td colspan="5" class="px-3 py-8 text-center text-sm text-slate-500">No active admissions match
                            this Shreny and Section.</td>
                    </tr>@endforelse</tbody>
            </table>
        </div>
        @else
        <div class="rounded border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">Select a
            Shreny and Section to list admitted students.</div>
        @endif
    </section>
</div>